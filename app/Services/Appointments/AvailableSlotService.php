<?php

namespace App\Services\Appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailableSlotService
{
    private const BLOCKING_STATUSES = ['pending', 'approved'];
    private const CLINIC_TIMEZONE = 'Asia/Ho_Chi_Minh';
    private const SHIFT_END_TIMES = [
        'morning' => '12:00',
        'afternoon' => '18:00',
    ];

    public function findAvailableSlots(array $filters): Collection
    {
        $specialty = trim((string) ($filters['specialty'] ?? ''));
        $dateFrom = $filters['date_from'] ?? Carbon::today()->toDateString();
        $days = (int) ($filters['days'] ?? 14);
        $limit = (int) ($filters['limit'] ?? 5);
        $preferredShift = $filters['preferred_shift'] ?? null;

        $days = max(1, min($days, 30));
        $limit = max(1, min($limit, 20));

        $today = Carbon::now(self::CLINIC_TIMEZONE)->startOfDay();
        $startDate = Carbon::parse($dateFrom, self::CLINIC_TIMEZONE)->startOfDay();
        if ($startDate->lt($today)) {
            $startDate = $today;
        }

        $doctors = Doctor::with('user')
            ->when($specialty !== '', function ($query) use ($specialty) {
                $query->where('specialty', $specialty);
            })
            ->get();

        $slots = collect();

        foreach ($doctors as $doctor) {
            foreach ($this->dateRange($startDate, $days) as $date) {
                foreach ($this->workingShiftsForDate($doctor, $date, $preferredShift) as $shift) {
                    if (!$this->isShiftStillBookable($date->toDateString(), $shift)) {
                        continue;
                    }

                    if (!$this->isSlotAvailable($doctor->id, $date->toDateString(), $shift)) {
                        continue;
                    }

                    $slots->push([
                        'doctor_id' => $doctor->id,
                        'doctor_name' => optional($doctor->user)->name ?: 'N/A',
                        'specialty' => $doctor->specialty,
                        'appointment_date' => $date->toDateString(),
                        'shift' => $shift,
                        'shift_label' => $this->shiftLabel($shift),
                    ]);
                }
            }
        }

        return $slots
            ->sortBy(function ($slot) {
                $shiftOrder = ['morning' => 1, 'afternoon' => 2];

                return $slot['appointment_date'] . '-' . ($shiftOrder[$slot['shift']] ?? 9);
            })
            ->values()
            ->take($limit);
    }

    public function isSlotAvailable(int $doctorId, string $date, string $shift, ?int $ignoreAppointmentId = null): bool
    {
        $query = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->where('shift', $shift)
            ->whereIn('status', self::BLOCKING_STATUSES);

        if ($ignoreAppointmentId) {
            $query->where('id', '!=', $ignoreAppointmentId);
        }

        return !$query->exists();
    }

    public function doctorWorksOnShift(int $doctorId, string $date, string $shift): bool
    {
        if (!in_array($shift, ['morning', 'afternoon'], true)) {
            return false;
        }

        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            return false;
        }

        return in_array(
            $shift,
            $this->workingShiftsForDate($doctor, Carbon::parse($date, self::CLINIC_TIMEZONE), null),
            true
        );
    }

    public function blockingStatuses(): array
    {
        return self::BLOCKING_STATUSES;
    }

    public function isShiftStillBookable(string $date, string $shift, ?Carbon $now = null): bool
    {
        if (!isset(self::SHIFT_END_TIMES[$shift])) {
            return false;
        }

        $now = $now
            ? $now->copy()->setTimezone(self::CLINIC_TIMEZONE)
            : Carbon::now(self::CLINIC_TIMEZONE);

        $appointmentDate = Carbon::parse($date, self::CLINIC_TIMEZONE)->startOfDay();
        $today = $now->copy()->startOfDay();

        if ($appointmentDate->lt($today)) {
            return false;
        }

        if ($appointmentDate->gt($today)) {
            return true;
        }

        $shiftEnd = Carbon::parse($date . ' ' . self::SHIFT_END_TIMES[$shift], self::CLINIC_TIMEZONE);

        return $now->lt($shiftEnd);
    }

    private function dateRange(Carbon $startDate, int $days): array
    {
        $dates = [];

        for ($i = 0; $i < $days; $i++) {
            $dates[] = $startDate->copy()->addDays($i);
        }

        return $dates;
    }

    private function workingShiftsForDate(Doctor $doctor, Carbon $date, ?string $preferredShift): array
    {
        $workingHours = $doctor->working_hours;
        if (!is_array($workingHours)) {
            return [];
        }

        $dayName = $date->format('l');
        $shifts = [];

        foreach ($workingHours as $entry) {
            if (!isset($entry['day'], $entry['shift']) || $entry['day'] !== $dayName) {
                continue;
            }

            foreach ($this->expandShift((string) $entry['shift']) as $shift) {
                if ($preferredShift && $preferredShift !== $shift) {
                    continue;
                }

                $shifts[] = $shift;
            }
        }

        return array_values(array_unique($shifts));
    }

    private function expandShift(string $shift): array
    {
        if ($shift === 'both') {
            return ['morning', 'afternoon'];
        }

        if (in_array($shift, ['morning', 'afternoon'], true)) {
            return [$shift];
        }

        return [];
    }

    private function shiftLabel(string $shift): string
    {
        return $shift === 'morning' ? '08:00 - 12:00' : '14:00 - 18:00';
    }
}

<?php

namespace App\Services\Appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class AiBookingService
{
    private $availableSlotService;

    public function __construct(AvailableSlotService $availableSlotService)
    {
        $this->availableSlotService = $availableSlotService;
    }

    public function book(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            $doctor = Doctor::findOrFail($data['doctor_id']);

            if (!$this->availableSlotService->doctorWorksOnShift($doctor->id, $data['appointment_date'], $data['shift'])) {
                throw new RuntimeException('Bác sĩ không có lịch làm việc trong ngày và ca khám đã chọn.');
            }

            if (!$this->availableSlotService->isShiftStillBookable($data['appointment_date'], $data['shift'])) {
                throw new RuntimeException('Ca khám đã qua hoặc không còn nhận đặt lịch.');
            }

            $taken = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $data['appointment_date'])
                ->where('shift', $data['shift'])
                ->whereIn('status', $this->availableSlotService->blockingStatuses())
                ->lockForUpdate()
                ->exists();

            if ($taken) {
                throw new RuntimeException('Ca khám này vừa có người khác đặt. Vui lòng chọn một lịch trống khác.');
            }

            $user = $this->patientForBooking($data);

            $appointmentData = [
                'user_id' => $user->id,
                'doctor_id' => $doctor->id,
                'appointment_date' => $data['appointment_date'],
                'shift' => $data['shift'],
                'description' => $data['description'] ?? 'Đặt lịch qua Chatbot AI',
                'status' => 'pending',
            ];

            if (Schema::hasColumn('appointments', 'source')) {
                $appointmentData['source'] = 'ai_auto';
            }

            return Appointment::create($appointmentData)->load('doctor.user', 'user');
        });
    }

    private function patientForBooking(array $data): User
    {
        if (Auth::check() && Auth::user()->role === 'patient') {
            $user = Auth::user();
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'phone' => $data['phone'] ?? $user->phone,
            ]);

            return $user;
        }

        $phone = trim((string) $data['phone']);

        $user = User::where('phone', $phone)->first();

        if ($user && $user->role !== 'patient') {
            throw new RuntimeException('Số điện thoại này đang thuộc tài khoản không phải bệnh nhân. Vui lòng dùng số điện thoại khác hoặc liên hệ phòng khám.');
        }

        if (!$user) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? ($phone . '@chatbot.local'),
                'password' => Hash::make('12345678'),
                'role' => 'patient',
                'phone' => $phone,
            ]);
        }

        $user->update([
            'name' => $data['name'],
        ]);

        return $user;
    }
}

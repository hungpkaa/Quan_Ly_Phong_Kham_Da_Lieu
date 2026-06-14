<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use App\Services\Appointments\AvailableSlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function create()
    {
        $doctors = Doctor::all();
        $specialties = Doctor::distinct()->pluck('specialty');

        return view('appointmentcreate', compact('specialties', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer|min:1',
            'cccd' => 'required|string|max:20',
            'specialty' => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'shift' => 'required|in:morning,afternoon',
            'description' => 'nullable|string|max:500',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        if (trim($doctor->specialty) !== trim($request->specialty)) {
            return back()
                ->withInput()
                ->with('error', 'Bác sĩ đã chọn không thuộc chuyên khoa/dịch vụ này. Vui lòng chọn lại bác sĩ phù hợp.');
        }

        $slotError = $this->appointmentSlotError(
            app(AvailableSlotService::class),
            (int) $request->doctor_id,
            $request->appointment_date,
            $request->shift
        );

        if ($slotError) {
            return back()->withInput()->with('error', $slotError);
        }

        $user = Auth::check() && Auth::user()->role === 'patient'
            ? $this->patientForAuthenticatedBooking($request)
            : $this->patientForGuestBooking($request);

        Appointment::create([
            'user_id' => $user->id,
            'doctor_id' => $doctor->id,
            'specialty' => $doctor->specialty,
            'appointment_date' => $request->appointment_date,
            'shift' => $request->shift,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('appointments.create')
            ->with('success', 'Đặt lịch khám thành công! Vui lòng chờ phòng khám xác nhận duyệt lịch.');
    }

    public function searchAppointments(Request $request)
    {
        return redirect()->route('doctor.schedule', [
            'filter' => $request->input('filter', 'today'),
            'query' => $request->input('query'),
        ]);
    }

    public function index()
    {
        return redirect()->route('doctor.schedule');
    }

    private function patientForAuthenticatedBooking(Request $request): User
    {
        $user = Auth::user();
        $phone = trim((string) $request->phone);
        $email = trim((string) $request->email);

        if (User::where('phone', $phone)->where('id', '!=', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'Số điện thoại này đang thuộc một tài khoản khác. Vui lòng kiểm tra lại thông tin.',
            ]);
        }

        if (User::where('email', $email)->where('id', '!=', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email này đang thuộc một tài khoản khác. Vui lòng kiểm tra lại thông tin.',
            ]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $email,
            'phone' => $phone,
            'age' => $request->age,
            'cccd' => $request->cccd,
        ]);

        return $user;
    }

    private function patientForGuestBooking(Request $request): User
    {
        $phone = trim((string) $request->phone);
        $email = trim((string) $request->email);

        $userByPhone = User::where('phone', $phone)->first();
        $userByEmail = User::where('email', $email)->first();

        if ($userByPhone && $userByPhone->role !== 'patient') {
            throw ValidationException::withMessages([
                'phone' => 'Số điện thoại này đang thuộc tài khoản không phải bệnh nhân. Vui lòng dùng số điện thoại khác hoặc liên hệ phòng khám.',
            ]);
        }

        if ($userByEmail && $userByEmail->role !== 'patient') {
            throw ValidationException::withMessages([
                'email' => 'Email này đang thuộc tài khoản không phải bệnh nhân. Vui lòng dùng email khác.',
            ]);
        }

        if ($userByPhone && $userByEmail && $userByPhone->id !== $userByEmail->id) {
            throw ValidationException::withMessages([
                'email' => 'Email và số điện thoại đang thuộc hai bệnh nhân khác nhau. Vui lòng kiểm tra lại thông tin.',
            ]);
        }

        if ($userByPhone && $userByPhone->email !== $email) {
            throw ValidationException::withMessages([
                'email' => 'Email không khớp với bệnh nhân có số điện thoại này. Vui lòng đăng nhập hoặc liên hệ phòng khám để cập nhật thông tin.',
            ]);
        }

        if ($userByEmail && $userByEmail->phone !== $phone) {
            throw ValidationException::withMessages([
                'phone' => 'Số điện thoại không khớp với bệnh nhân có email này. Vui lòng đăng nhập hoặc liên hệ phòng khám để cập nhật thông tin.',
            ]);
        }

        if ($userByPhone || $userByEmail) {
            return $userByPhone ?: $userByEmail;
        }

        return User::create([
            'name' => $request->name,
            'email' => $email,
            'phone' => $phone,
            'age' => $request->age,
            'cccd' => $request->cccd,
            'password' => Hash::make(Str::random(32)),
            'role' => 'patient',
        ]);
    }

    private function appointmentSlotError(
        AvailableSlotService $slotService,
        int $doctorId,
        string $date,
        string $shift,
        ?int $ignoreAppointmentId = null
    ): ?string {
        if (!$slotService->doctorWorksOnShift($doctorId, $date, $shift)) {
            return 'Bác sĩ không có lịch làm việc trong ngày và ca khám đã chọn.';
        }

        if (!$slotService->isShiftStillBookable($date, $shift)) {
            return 'Ca khám đã qua hoặc không còn nhận đặt lịch.';
        }

        if (!$slotService->isSlotAvailable($doctorId, $date, $shift, $ignoreAppointmentId)) {
            return 'Ca khám này đã có người đặt. Vui lòng chọn lịch trống khác.';
        }

        return null;
    }
}

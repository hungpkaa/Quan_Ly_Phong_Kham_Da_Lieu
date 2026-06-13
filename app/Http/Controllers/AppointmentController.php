<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Services\Appointments\AvailableSlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'phone' => 'required|string',
            'age' => 'required|integer|min:1',
            'cccd' => 'required|string|max:20',
            'appointment_date' => 'required|date|after_or_equal:today',
            'shift' => 'required|in:morning,afternoon',
            'description' => 'nullable|string|max:500',
        ]);

        $slotService = app(AvailableSlotService::class);
        $slotError = $this->appointmentSlotError(
            $slotService,
            (int) $request->doctor_id,
            $request->appointment_date,
            $request->shift
        );

        if ($slotError) {
            return back()->withInput()->with('error', $slotError);
        }

        if (Auth::check() && Auth::user()->role === 'patient') {
            $user = Auth::user();
            
            // Update user info if missing or changed (only for authenticated user)
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'cccd' => $request->cccd,
            ]);
        } else {
            $user = \App\Models\User::where('phone', $request->phone)->first();

            if ($user && $user->role !== 'patient') {
                return back()
                    ->withInput()
                    ->with('error', 'Số điện thoại này đang thuộc tài khoản không phải bệnh nhân. Vui lòng dùng số điện thoại khác hoặc liên hệ phòng khám.');
            }

            if (!$user) {
                // If not found by phone, check email
                $userByEmail = \App\Models\User::where('email', $request->email)->first();
                if ($userByEmail && $userByEmail->role !== 'patient') {
                    return back()
                        ->withInput()
                        ->with('error', 'Email này đang thuộc tài khoản không phải bệnh nhân. Vui lòng dùng email khác.');
                }
                
                if (!$userByEmail) {
                    $user = \App\Models\User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'age' => $request->age,
                        'cccd' => $request->cccd,
                        'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                        'role' => 'patient',
                    ]);
                } else {
                    $user = $userByEmail;
                }
            }
            // For guest users, we intentionally DO NOT update their existing profile
            // to prevent unauthorized data overwriting. We just use their user ID.
        }

        Appointment::create([
            'user_id' => $user->id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'shift' => $request->shift,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.create')->with('success', 'Đặt lịch khám thành công! Vui lòng chờ phòng khám xác nhận duyệt lịch.');
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

    public function searchAppointments(Request $request)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('home.index')->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $query = $request->input('query');

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where(function ($appointmentQuery) use ($query) {
                $appointmentQuery->whereHas('user', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->orWhere('appointment_date', 'LIKE', "%$query%")
                ->orWhere('status', 'LIKE', "%$query%");
            })
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('role.schedule', compact('appointments'));
    }

    public function index()
    {
        $appointments = Appointment::orderBy('appointment_date', 'asc')->get();

        return view('role.schedule', compact('appointments'));
    }
}

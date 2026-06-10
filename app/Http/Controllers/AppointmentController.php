<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
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

        $user = Auth::check() && Auth::user()->role === 'patient' 
            ? Auth::user() 
            : \App\Models\User::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                    'role' => 'patient'
                ]
            );

        // Update user info if missing or changed
        $user->update([
            'age' => $request->age,
            'cccd' => $request->cccd,
        ]);

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

    public function searchAppointments(Request $request)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $query = $request->input('query');

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->orWhere('appointment_date', 'LIKE', "%$query%")
            ->orWhere('status', 'LIKE', "%$query%")
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

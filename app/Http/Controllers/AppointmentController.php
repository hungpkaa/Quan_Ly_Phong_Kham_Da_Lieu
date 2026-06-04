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

        Appointment::create([
            'user_id' => Auth::check() && Auth::user()->role === 'patient' ? Auth::id() : null,
            'doctor_id' => $request->doctor_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'cccd' => $request->cccd,
            'appointment_date' => $request->appointment_date,
            'shift' => $request->shift,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.create')->with('success', 'Dat lich thanh cong. Cho duyet!');
    }

    public function searchAppointments(Request $request)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->first();

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Khong tim thay thong tin bac si.');
        }

        $query = $request->input('query');

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
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

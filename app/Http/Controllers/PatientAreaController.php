<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class PatientAreaController extends Controller
{
    public function account()
    {
        $user = Auth::user();
        
        $appointments = Appointment::with('doctor.user')
            ->where('user_id', $user->id)
            ->orderByDesc('appointment_date')
            ->get();

        $medicalRecords = \App\Models\MedicalRecord::with('doctor.user')
            ->where('user_id', $user->id)
            ->orderByDesc('exam_date')
            ->get();
            
        $progresses = \App\Models\PatientProgress::with('doctor.user')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $doctorIds = \App\Models\MedicalRecord::where('user_id', $user->id)->pluck('doctor_id')
            ->merge(\App\Models\Appointment::where('user_id', $user->id)->pluck('doctor_id'))
            ->unique();

        $doctors = \App\Models\Doctor::with('user')->whereIn('id', $doctorIds)->get();

        return view('patient.account', compact('user', 'appointments', 'medicalRecords', 'progresses', 'doctors'));
    }

    public function storeProgress(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'image' => 'required|image|max:5120',
            'notes' => 'nullable|string',
        ]);

        $path = $request->file('image')->store('patient_progress', 'public');

        \App\Models\PatientProgress::create([
            'user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'image_path' => $path,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Đã tải lên tiến độ thành công!');
    }
}

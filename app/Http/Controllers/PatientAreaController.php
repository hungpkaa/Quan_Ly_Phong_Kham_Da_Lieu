<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class PatientAreaController extends Controller
{
    public function account()
    {
        return view('patient.account', [
            'user' => Auth::user(),
        ]);
    }

    public function appointments()
    {
        $appointments = Appointment::with('doctor')
            ->where('user_id', Auth::id())
            ->orderByDesc('appointment_date')
            ->get();

        return view('patient.appointments', compact('appointments'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    private function currentDoctor()
    {
        return Doctor::where('email', Auth::user()->email)->first();
    }

    public function index()
    {
        $doctors = Doctor::all();
        return view('doctors', compact('doctors'));
    }

    public function adminIndex()
    {
        $doctors = Doctor::all();
        return view('role.adminfixdoctors', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:doctors',
            'password' => 'required|string|min:8',
            'specialty' => 'required|string',
            'working_hours' => 'required|array',
            'working_hours.*.day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'working_hours.*.shift' => 'required|in:morning,afternoon'
        ]);

        Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'specialty' => $request->specialty,
            'working_hours' => $request->working_hours,
        ]);

        return redirect()->back()->with('success', 'Bac si da duoc them thanh cong!');
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:doctors,email,' . $id,
            'specialty' => 'required|string',
            'working_hours' => 'nullable|array',
            'working_hours.*.day' => 'required_with:working_hours|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'working_hours.*.shift' => 'required_with:working_hours|in:morning,afternoon',
        ]);

        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
            'specialty' => $request->specialty,
            'working_hours' => $request->has('working_hours') ? $request->working_hours : $doctor->working_hours,
        ]);

        return redirect()->back()->with('success', 'Bac si da duoc cap nhat thanh cong!');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->back()->with('success', 'Bac si da duoc xoa thanh cong!');
    }

    public function getDoctorsBySpecialty($specialty)
    {
        $doctors = Doctor::where('specialty', $specialty)->get(['id', 'name']);
        return response()->json($doctors);
    }

    public function showDashboard()
    {
        $doctor = $this->currentDoctor();

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Khong tim thay thong tin bac si.');
        }

        return view('role.admindoctor', compact('doctor'));
    }

    public function showSchedule()
    {
        if (Auth::user()->role !== 'admindoctor') {
            return redirect()->route('home')->with('error', 'Ban khong co quyen truy cap trang nay.');
        }

        $doctor = $this->currentDoctor();

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Khong tim thay thong tin bac si.');
        }

        $appointments = Appointment::with(['patient', 'doctor'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('role.schedule', compact('appointments'));
    }

    public function search_doctors_list(Request $request)
    {
        $query = $request->input('query');

        $doctors = Doctor::where('name', 'like', '%' . $query . '%')
            ->orWhere('specialty', 'like', '%' . $query . '%')
            ->get();

        return view('doctors', compact('doctors'));
    }

    public function showPatients()
    {
        $doctor = $this->currentDoctor();

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Khong tim thay thong tin bac si.');
        }

        $patients = Appointment::where('doctor_id', $doctor->id)
            ->with('patient')
            ->get();

        return view('role.patients', compact('patients'));
    }
}

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
        return Auth::user()->doctor;
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
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'specialty' => 'required|string',
            'working_hours' => 'required|array',
            'working_hours.*.day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'working_hours.*.shift' => 'required|in:morning,afternoon'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'admindoctor',
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'specialty' => $request->specialty,
                'working_hours' => $request->working_hours,
            ]);
        });

        return redirect()->back()->with('success', 'Bác sĩ đã được thêm thành công!');
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $user = $doctor->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . ($user ? $user->id : ''),
            'specialty' => 'required|string',
            'working_hours' => 'nullable|array',
            'working_hours.*.day' => 'required_with:working_hours|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'working_hours.*.shift' => 'required_with:working_hours|in:morning,afternoon',
        ]);

        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        $doctor->update([
            'specialty' => $request->specialty,
            'working_hours' => $request->has('working_hours') ? $request->working_hours : $doctor->working_hours,
        ]);

        return redirect()->back()->with('success', 'Bác sĩ đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->back()->with('success', 'Bác sĩ đã được xóa thành công!');
    }

    public function getDoctorsBySpecialty($specialty)
    {
        $doctors = Doctor::with('user')->where('specialty', $specialty)->get();
        $formattedDoctors = $doctors->map(function($doc) {
            return [
                'id' => $doc->id,
                'name' => $doc->user ? $doc->user->name : 'Unknown',
            ];
        });
        return response()->json($formattedDoctors);
    }

    public function showDashboard()
    {
        $doctor = $this->currentDoctor();

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        return view('role.admindoctor', compact('doctor'));
    }

    public function showSchedule()
    {
        if (Auth::user()->role !== 'admindoctor') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        $doctor = $this->currentDoctor();

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $appointments = Appointment::with(['user', 'doctor'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('role.schedule', compact('appointments'));
    }

    public function search_doctors_list(Request $request)
    {
        $query = $request->input('query');

        $doctors = Doctor::whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orWhere('specialty', 'like', '%' . $query . '%')
            ->get();

        return view('doctors', compact('doctors'));
    }

    public function showPatients()
    {
        $doctor = $this->currentDoctor();

        if (!$doctor) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }

        $patients = Appointment::where('doctor_id', $doctor->id)
            ->with('user')
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->unique('user_id');

        return view('role.patients', compact('patients'));
    }
}

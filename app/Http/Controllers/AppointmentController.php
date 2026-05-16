<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor; // Thêm dòng này
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // Hiển thị form đặt Lịch
    public function create()
    {
        $doctors = Doctor::all(); // Lấy tất cả bác sĩ từ bảng `doctors`
        $specialties = Doctor::distinct()->pluck('specialty');
        return view('appointmentcreate', compact('specialties', 'doctors'));
    }

    // Xử lý Lưu Lịch hẹn
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'age' => 'required|integer|min:1',
            'cccd' => 'required|string|max:20',
            'appointment_date' => 'required|date|after:today',
            'shift' => 'required|in:morning,afternoon',
            'description' => 'nullable|string|max:500',
        ]);

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'cccd' => $request->cccd,
            'appointment_date' => $request->appointment_date,
            'shift' => $request->shift,
            'description' => $request->description,
            'status' => 'approved',
        ]);

        return redirect()->route('appointments.create')->with('success', 'Đặt lịch thành công. Chờ duyệt!');
    }


    public function searchAppointments(Request $request)
    {
        $doctorId = auth()->user()->id; // Lấy ID của bác sĩ đăng nhậpppppp
        $query = $request->input('query');

        // Tìm kiếm lịch hẹn của bác sĩ hiện tại theo tên bệnh nhân, ngày khám hoặc trạng thái
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('appointment_date', 'LIKE', "%$query%")
                    ->orWhere('status', 'LIKE', "%$query%");
            })
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('role.schedule', compact('appointments'));

    }



    // Hiển thị danh sách lịch hẹn của bệnh nhân
    public function index()
    {
        $appointments = Appointment::orderBy('appointment_date', 'asc')->get();

        return view('role.schedule', compact('appointments'));
    }

}
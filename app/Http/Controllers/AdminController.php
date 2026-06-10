<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $filter = $request->query('filter', '7days');

        $totalPatients = \App\Models\User::where('role', 'patient')->count();
        $totalDoctors = \App\Models\Doctor::count();
        $appointmentsToday = \App\Models\Appointment::whereDate('appointment_date', \Carbon\Carbon::today())->count();
        $revenueMonth = \App\Models\Invoice::whereMonth('created_at', \Carbon\Carbon::now()->month)
                                            ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                            ->sum('total_amount');
        
        if ($revenueMonth >= 1000000) {
            $revenueFormatted = round($revenueMonth / 1000000, 1) . 'M';
        } elseif ($revenueMonth >= 1000) {
            $revenueFormatted = round($revenueMonth / 1000, 1) . 'K';
        } else {
            $revenueFormatted = $revenueMonth;
        }

        $recentPatients = \App\Models\User::where('role', 'patient')->latest()->take(5)->get();
        $recentAppointments = \App\Models\Appointment::with('doctor')->latest()->take(5)->get();
        $pendingAppointmentsCount = \App\Models\Appointment::where('status', 'pending')->count();

        // Chart Data logic based on filter
        if ($filter == 'today') {
            $appointmentsChartData = \App\Models\Appointment::selectRaw('DATE(appointment_date) as date, count(*) as count')
                ->whereDate('appointment_date', \Carbon\Carbon::today())
                ->groupBy('date')->orderBy('date')->get();
        } elseif ($filter == 'this_month') {
            $appointmentsChartData = \App\Models\Appointment::selectRaw('DATE(appointment_date) as date, count(*) as count')
                ->whereMonth('appointment_date', \Carbon\Carbon::now()->month)
                ->whereYear('appointment_date', \Carbon\Carbon::now()->year)
                ->groupBy('date')->orderBy('date')->get();
        } else {
            // default 7 days
            $appointmentsChartData = \App\Models\Appointment::selectRaw('DATE(appointment_date) as date, count(*) as count')
                ->whereDate('appointment_date', '>=', \Carbon\Carbon::today()->subDays(6))
                ->groupBy('date')->orderBy('date')->get();
        }

        // Chart Data: Doanh thu 6 tháng qua
        $revenueChartData = \App\Models\Invoice::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, sum(total_amount) as total')
            ->whereDate('created_at', '>=', \Carbon\Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();

        return view('role.admin', compact(
            'totalPatients', 'totalDoctors', 'appointmentsToday', 'revenueFormatted', 
            'recentPatients', 'recentAppointments', 'pendingAppointmentsCount',
            'appointmentsChartData', 'revenueChartData', 'filter'
        ));
    }

    public function showDoctors(Request $request)
    {
        $search = $request->input('search');
        $editDoctor = null;

        // Nếu có yêu cầu sửa bác sĩ
        if ($request->has('edit_id')) {
            $editDoctor = Doctor::find($request->input('edit_id'));
        }

        // Lọc danh sách bác sĩ theo từ khóa tìm kiếm
        $doctors = Doctor::with('user')->when($search, function ($query, $search) {
            return $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('specialty', 'like', "%{$search}%");
        })->get();

        return view('role.adminfixdoctors', compact('doctors', 'search', 'editDoctor'));
    }


    public function storeDoctor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors|unique:users',
            'password' => 'required|string|min:6',
            'specialty' => 'required|string',
            'phone' => 'required|string',
            'bio' => 'nullable|string',
            'image' => 'nullable|file|max:5120|mimes:jpeg,png,jpg,gif', // Giống như dịch vụ
            'working_hours' => 'nullable|array', // Cho phép lịch làm việc rỗng
            'working_hours.*.day' => 'required_with:working_hours|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'working_hours.*.shift' => 'required_with:working_hours|in:morning,afternoon',
        ]);

        if ($request->hasFile('image')) {
            // Tạo tên file duy nhất để tránh trùng lặp
            $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'), $imageName);
            $filePath = 'uploads/' . $imageName; // Lưu đường dẫn vào cơ sở dữ liệu
        } else {
            $filePath = null;
        }

        // Thêm tài khoản vào bảng users
        DB::transaction(function () use ($request, $filePath) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'admindoctor',
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'specialty' => $request->specialty,
                'bio' => $request->bio,
                'image' => $filePath,
                'working_hours' => $request->working_hours,
            ]);
        });

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Bác sĩ đã được thêm thành công và có thể đăng nhập.');
    }




    public function updateDoctor(Request $request, $id)
    {
        // Lấy thông tin bác sĩ cần sửa
        $doctor = Doctor::findOrFail($id);
        $user = $doctor->user;

        // Validate dữ liệu từ form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id : ''),
            'specialty' => 'required|string',
            'phone' => 'required|string',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'working_hours' => 'nullable|array', // Cho phép lịch làm việc rỗng
            'working_hours.*.day' => 'required_with:working_hours|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'working_hours.*.shift' => 'required_with:working_hours|in:morning,afternoon',
        ]);

        // Xử lý ảnh (nếu có)
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($doctor->image && file_exists(public_path($doctor->image))) {
                unlink(public_path($doctor->image));
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('img'), $imageName);
            $doctor->image = 'img/' . $imageName;
        }


        $updateData = [
            'specialty' => $request->specialty,
            'bio' => $request->bio,
            'working_hours' => $request->filled('working_hours') ? $request->working_hours : null,
        ];

        // Cập nhật bác sĩ
        $doctor->update($updateData);

        // Nếu có bảng `users` liên kết với bác sĩ, cập nhật cả tài khoản user
        if ($user) {
            $userUpdateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            if ($request->filled('password')) {
                $userUpdateData['password'] = Hash::make($request->password);
            }

            $user->update($userUpdateData);
        }

        // Chuyển hướng về danh sách bác sĩ kèm thông báo
        return redirect()->route('admin.doctors.index')->with('success', 'Thông tin bác sĩ đã được cập nhật.');
    }


    public function destroyDoctor($id)
    {
        // Tìm bác sĩ với ID
        $doctor = Doctor::findOrFail($id);

        // Tìm user liên kết với bác sĩ
        $user = $doctor->user;

        // Xóa bác sĩ trong bảng doctors
        $doctor->delete();

        // Xóa user trong bảng users nếu tồn tại
        if ($user) {
            $user->delete();
        }

        // Chuyển hướng kèm thông báo
        return redirect()->route('admin.doctors.index')->with('success', 'Bác sĩ và tài khoản liên kết đã được xóa thành công.');
    }

    // 📌 Hiển thị danh sách bệnh nhân
    public function showAllPatients(Request $request)
    {
        $search = $request->input('search');

        $patients = User::where('role', 'patient')
            ->withCount(['appointments', 'medicalRecords'])
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('role.adminpatients', compact('patients', 'search'));
    }


    public function getDoctorsBySpecialty(Request $request)
    {
        $doctors = Doctor::with('user')->where('specialty', $request->specialty)->get();
        // Since we are returning json, we probably want to format it so frontend easily gets name
        $formattedDoctors = $doctors->map(function($doc) {
            return [
                'id' => $doc->id,
                'name' => $doc->user ? $doc->user->name : 'Unknown',
            ];
        });
        return response()->json($formattedDoctors);
    }


    // 📌 Hiển thị danh sách lịch hẹn với tìm kiếm và chỉnh sửa
    public function showAppointments(Request $request)
    {
        $search = $request->input('search');
        $editAppointment = null;

        // Nếu có yêu cầu sửa lịch hẹn
        if ($request->has('edit_id')) {
            $editAppointment = Appointment::find($request->input('edit_id'));
        }

        // Lấy danh sách các chuyên khoa từ bảng bác sĩ
        $specialties = Doctor::distinct()->pluck('specialty');

        // Lấy danh sách bác sĩ để hiển thị
        $doctors = Doctor::all();

        // Truy vấn lịch hẹn với tìm kiếm
        $appointments = Appointment::with(['doctor', 'user'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('appointment_date', 'ASC')
            ->get();

        return view('role.manageappointments', compact('appointments', 'editAppointment', 'search', 'specialties', 'doctors'));
    }


    // 📌 Duyệt lịch hẹn
    public function approveAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Lịch hẹn đã được duyệt.');
    }

    // 📌 Từ chối lịch hẹn
    public function rejectAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Lịch hẹn đã bị từ chối.');
    }

    // 📌 Xóa lịch hẹn
    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return redirect()->back()->with('success', 'Lịch hẹn đã được xóa.');
    }

    // 📌 Thêm lịch hẹn mới
    public function storeAppointment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'age' => 'required|integer',
            'cccd' => 'required|string',
            'appointment_date' => 'required|date',
            'shift' => 'required|in:morning,afternoon',
            'description' => 'nullable|string',
            'doctor_id' => 'required|exists:doctors,id',
            'specialty' => 'required|string',
        ]);

        // Kiểm tra bác sĩ
        $doctor = Doctor::find($request->doctor_id);
        if (!$doctor) {
            return back()->with('error', 'Bác sĩ không tồn tại');
        }

        $user = \App\Models\User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'role' => 'patient'
            ]
        );

        $user->update([
            'age' => $request->age,
            'cccd' => $request->cccd,
        ]);

        // Tạo lịch hẹn mới
        Appointment::create([
            'user_id' => $user->id,
            'appointment_date' => $request->appointment_date,
            'shift' => $request->shift,
            'description' => $request->description,
            'doctor_id' => $doctor->id,
            'specialty' => $request->specialty,
            'status' => 'approved',
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Lịch hẹn đã được thêm.');
    }




    // 📌 Cập nhật thông tin lịch hẹn
    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'specialty' => 'required|string',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'shift' => 'required|in:morning,afternoon',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'age' => 'required|integer',
            'cccd' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($appointment->user) {
            $appointment->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'cccd' => $request->cccd,
            ]);
        }

        $appointment->update([
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'shift' => $request->shift,
            'description' => $request->description,
            'specialty' => $request->specialty,
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Lịch hẹn đã được cập nhật.');
    }

    public function getDoctorScheduleWithFutureDates($doctorId)
    {
        // Lấy lịch làm việc của bác sĩ
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)->get();

        if ($schedules->isEmpty()) {
            return response()->json(['error' => 'Không có lịch làm việc'], 404);
        }

        $formattedSchedules = [];

        foreach ($schedules as $schedule) {
            $currentDate = now(); // Ngày hiện tại

            for ($i = 0; $i < 6; $i++) { // Lấy 6 tuần tiếp theo của lịch làm việc
                $nextDate = $currentDate->copy()->next($schedule->day_of_week)->addWeeks($i);
                $shiftText = $schedule->shift === 'morning' ? '08h-12h' : '14h-18h';

                $formattedSchedules[] = [
                    'id' => $schedule->id,
                    'display' => "{$schedule->day_of_week} - {$shiftText} ({$nextDate->format('d/m/Y')})",
                    'date' => $nextDate->format('Y-m-d'),
                    'shift' => $schedule->shift
                ];
            }
        }

        return response()->json($formattedSchedules);
    }
    public function getWorkingHours(Request $request)
    {
        $doctor = Doctor::findOrFail($request->doctor_id);
        $selectedDate = $request->query('date');
        $dayOfWeek = date('l', strtotime($selectedDate)); // Lấy thứ trong tuần (Monday, Tuesday, ...)



        $workingHours = $doctor->working_hours;

        if (!is_array($workingHours)) {
            return response()->json(['error' => 'Lịch làm việc không hợp lệ'], 400);
        }

        $availableShifts = ['morning' => false, 'afternoon' => false];

        foreach ($workingHours as $entry) {
            if (isset($entry['day'], $entry['shift']) && $entry['day'] === $dayOfWeek) {
                if ($entry['shift'] === 'morning' || $entry['shift'] === 'both') {
                    $availableShifts['morning'] = true;
                }
                if ($entry['shift'] === 'afternoon' || $entry['shift'] === 'both') {
                    $availableShifts['afternoon'] = true;
                }
            }
        }

        return response()->json($availableShifts);
    }
    public function showshift(Request $request)
    {
        // Lấy tất cả bác sĩ
        $doctors = Doctor::with('user')->get();

        // Nhóm theo chuyên môn để hiển thị
        $specialtyGroups = $doctors->groupBy('specialty');

        // Xác định bác sĩ được chọn để sửa lịch
        $selectedDoctor = null;
        if ($request->has('doctor_id')) {
            $selectedDoctor = Doctor::find($request->doctor_id);
        }

        return view('role.workingschedule', compact('doctors', 'selectedDoctor', 'specialtyGroups'));
    }


    public function updateSchedule(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'working_hours' => 'required|array',
            'working_hours.*.day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'working_hours.*.shift' => 'required|in:morning,afternoon',
        ]);

        $doctor->update([
            'working_hours' => $request->filled('working_hours') ? $request->working_hours : null,
        ]);

        return redirect()->route('admin.workingschedule', ['doctor_id' => $id])->with('success', 'Lịch làm việc đã được cập nhật.');
    }
}

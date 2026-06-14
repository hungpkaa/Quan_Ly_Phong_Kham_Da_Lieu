<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Appointments\AvailableSlotService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $filter = $request->query('filter', '7days');

        $totalPatients = \App\Models\User::where('role', 'patient')->count();
        $totalDoctors = \App\Models\Doctor::count();
        $appointmentsToday = \App\Models\Appointment::whereDate('appointment_date', \Carbon\Carbon::today())->count();
        $revenueMonth = \App\Models\Invoice::whereIn('status', \App\Models\Invoice::paidStatusValues())
                                            ->whereMonth('created_at', \Carbon\Carbon::now()->month)
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
        $unpaidInvoicesCount = \App\Models\Invoice::whereNotIn('status', \App\Models\Invoice::paidStatusValues())->count();
        $incompleteMedicalRecordsCount = \App\Models\MedicalRecord::whereNull('diagnosis')
                                        ->orWhereNull('prescription')
                                        ->orWhere('diagnosis', '')
                                        ->orWhere('prescription', '')
                                        ->count();

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
        $revenueChartDataDB = \App\Models\Invoice::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, sum(total_amount) as total')
            ->whereIn('status', \App\Models\Invoice::paidStatusValues())
            ->whereDate('created_at', '>=', \Carbon\Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();

        $revenueChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            
            $record = $revenueChartDataDB->first(function($item) use ($year, $month) {
                return $item->year == $year && $item->month == $month;
            });
            
            $revenueChartData[] = [
                'year' => $year,
                'month' => $month,
                'total' => $record ? $record->total : 0
            ];
        }

        return view('role.admin', compact(
            'totalPatients', 'totalDoctors', 'appointmentsToday', 'revenueFormatted', 
            'recentPatients', 'recentAppointments', 'pendingAppointmentsCount',
            'appointmentsChartData', 'revenueChartData', 'filter',
            'unpaidInvoicesCount', 'incompleteMedicalRecordsCount'
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
            'specialty' => 'required|string|max:255',
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
        $filter = $request->input('filter');

        $patients = User::where('role', 'patient')
            ->withCount(['appointments', 'medicalRecords'])
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($filter === 'incomplete', function ($query) {
                return $query->where(function($q) {
                    $q->whereNull('age')
                      ->orWhereNull('cccd')
                      ->orWhereNull('phone')
                      ->orWhere('age', 0)
                      ->orWhere('cccd', '')
                      ->orWhere('phone', '');
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
        $appointments = Appointment::with(['doctor', 'user', 'medicalRecord'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderByRaw("status = 'pending' DESC")
            ->orderBy('appointment_date', 'ASC')
            ->orderBy('created_at', 'ASC')
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
    private function ensureDoctorMatchesSpecialty(Doctor $doctor, string $specialty): void
    {
        if (trim($doctor->specialty) !== trim($specialty)) {
            throw ValidationException::withMessages([
                'specialty' => 'Bác sĩ đã chọn không thuộc chuyên khoa/dịch vụ này. Vui lòng chọn lại bác sĩ phù hợp.',
            ]);
        }
    }

    private function patientForNewAdminAppointment(Request $request): User
    {
        $phone = trim((string) $request->phone);
        $email = trim((string) $request->email);

        $userByPhone = User::where('phone', $phone)->first();
        $userByEmail = User::where('email', $email)->first();

        if ($userByPhone && $userByPhone->role !== 'patient') {
            throw ValidationException::withMessages([
                'phone' => 'Số điện thoại này đang thuộc tài khoản không phải bệnh nhân. Vui lòng dùng số điện thoại khác.',
            ]);
        }

        if ($userByEmail && $userByEmail->role !== 'patient') {
            throw ValidationException::withMessages([
                'email' => 'Email này đang thuộc tài khoản không phải bệnh nhân. Vui lòng dùng email khác.',
            ]);
        }

        if ($userByPhone && $userByEmail && $userByPhone->id !== $userByEmail->id) {
            throw ValidationException::withMessages([
                'email' => 'Email này đang thuộc một bệnh nhân khác. Vui lòng kiểm tra lại thông tin bệnh nhân.',
            ]);
        }

        $user = $userByPhone ?: $userByEmail;

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'phone' => $phone,
                'age' => $request->age,
                'cccd' => $request->cccd,
                'password' => Hash::make(Str::random(32)),
                'role' => 'patient',
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

    private function updatePatientForAdminAppointment(Appointment $appointment, Request $request): User
    {
        if (!$appointment->user) {
            return $this->patientForNewAdminAppointment($request);
        }

        $user = $appointment->user;
        $phone = trim((string) $request->phone);
        $email = trim((string) $request->email);

        if ($user->role !== 'patient') {
            throw ValidationException::withMessages([
                'phone' => 'Lịch hẹn này đang gắn với tài khoản không phải bệnh nhân. Vui lòng kiểm tra lại dữ liệu.',
            ]);
        }

        $phoneOwner = User::where('phone', $phone)->where('id', '!=', $user->id)->first();
        if ($phoneOwner) {
            throw ValidationException::withMessages([
                'phone' => 'Số điện thoại này đang thuộc một tài khoản khác. Vui lòng kiểm tra lại thông tin bệnh nhân.',
            ]);
        }

        $emailOwner = User::where('email', $email)->where('id', '!=', $user->id)->first();
        if ($emailOwner) {
            throw ValidationException::withMessages([
                'email' => 'Email này đang thuộc một tài khoản khác. Vui lòng kiểm tra lại thông tin bệnh nhân.',
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

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'age' => 'required|integer|min:1',
            'cccd' => 'required|string|max:20',
            'appointment_date' => 'required|date',
            'shift' => 'required|in:morning,afternoon',
            'description' => 'nullable|string|max:500',
            'doctor_id' => 'required|exists:doctors,id',
            'specialty' => 'required|string|max:255',
        ]);

        // Kiểm tra bác sĩ
        $doctor = Doctor::findOrFail($request->doctor_id);
        $this->ensureDoctorMatchesSpecialty($doctor, $request->specialty);
        if (!$doctor) {
            return back()->with('error', 'Bác sĩ không tồn tại');
        }

        $slotService = app(AvailableSlotService::class);
        if (!$slotService->doctorWorksOnShift($doctor->id, $request->appointment_date, $request->shift)) {
            return back()->withInput()->with('error', 'Bác sĩ không có lịch làm việc trong ngày và ca khám đã chọn.');
        }

        // if (!$slotService->isShiftStillBookable($request->appointment_date, $request->shift)) {
        //     return back()->withInput()->with('error', 'Ca khám đã qua hoặc không còn nhận đặt lịch.');
        // } // Admin can optionally book for past dates if they want, but let's keep it restricted?
        // Actually the user just complained about double booking, so we definitely need isSlotAvailable.

        if (!$slotService->isShiftStillBookable($request->appointment_date, $request->shift)) {
            return back()->withInput()->with('error', 'Ca khám đã qua hoặc không còn nhận đặt lịch.');
        }

        if (!$slotService->isSlotAvailable($doctor->id, $request->appointment_date, $request->shift)) {
            return back()->withInput()->with('error', 'Ca khám này đã có người đặt. Vui lòng chọn lịch trống khác.');
        }

        $user = $this->patientForNewAdminAppointment($request);

        // Tạo lịch hẹn mới
        Appointment::create([
            'user_id' => $user->id,
            'doctor_id' => $doctor->id,
            'specialty' => $doctor->specialty,
            'appointment_date' => $request->appointment_date,
            'shift' => $request->shift,
            'description' => $request->description,
            'status' => 'approved',
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Lịch hẹn đã được thêm.');
    }




    // 📌 Cập nhật thông tin lịch hẹn
    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'specialty' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'shift' => 'required|in:morning,afternoon',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'age' => 'required|integer|min:1',
            'cccd' => 'required|string|max:20',
            'description' => 'nullable|string|max:500',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $this->ensureDoctorMatchesSpecialty($doctor, $request->specialty);

        $slotChanged = (int) $appointment->doctor_id !== (int) $request->doctor_id
            || $appointment->appointment_date !== $request->appointment_date
            || $appointment->shift !== $request->shift;

        $slotService = app(AvailableSlotService::class);
        if ($slotChanged && !$slotService->doctorWorksOnShift($request->doctor_id, $request->appointment_date, $request->shift)) {
            return back()->withInput()->with('error', 'Bác sĩ không có lịch làm việc trong ngày và ca khám đã chọn.');
        }

        if ($slotChanged && !$slotService->isShiftStillBookable($request->appointment_date, $request->shift)) {
            return back()->withInput()->with('error', 'Ca khám đã qua hoặc không còn nhận đặt lịch.');
        }

        if ($slotChanged && !$slotService->isSlotAvailable($request->doctor_id, $request->appointment_date, $request->shift, $id)) {
            return back()->withInput()->with('error', 'Ca khám này đã có người đặt. Vui lòng chọn lịch trống khác.');
        }

        $user = $this->updatePatientForAdminAppointment($appointment, $request);

        $appointment->update([
            'user_id' => $user->id,
            'doctor_id' => $request->doctor_id,
            'specialty' => $doctor->specialty,
            'appointment_date' => $request->appointment_date,
            'shift' => $request->shift,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Lịch hẹn đã được cập nhật.');
    }

    public function getDoctorScheduleWithFutureDates($doctorId)
    {
        // Lấy lịch làm việc của bác sĩ
        $doctor = Doctor::findOrFail($doctorId);
        $workingHours = is_array($doctor->working_hours) ? $doctor->working_hours : [];

        if (empty($workingHours)) {
            return response()->json(['error' => 'Bác sĩ chưa có lịch làm việc.'], 404);
        }

        $slotService = app(AvailableSlotService::class);
        $dayLabels = [
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
            'Sunday' => 'Chủ Nhật',
        ];
        $shiftLabels = [
            'morning' => '08:00 - 12:00',
            'afternoon' => '14:00 - 18:00',
        ];

        $formattedSchedules = [];
        $seen = [];
        $startDate = Carbon::today('Asia/Ho_Chi_Minh');

        for ($dayOffset = 0; $dayOffset < 42; $dayOffset++) {
            $date = $startDate->copy()->addDays($dayOffset);
            $dayName = $date->format('l');

            foreach ($workingHours as $entry) {
                if (($entry['day'] ?? null) !== $dayName) {
                    continue;
                }

                $entryShift = (string) ($entry['shift'] ?? '');
                $shifts = $entryShift === 'both' ? ['morning', 'afternoon'] : [$entryShift];

                foreach ($shifts as $shift) {
                    if (!isset($shiftLabels[$shift])) {
                        continue;
                    }

                    $key = $date->toDateString() . '|' . $shift;
                    if (isset($seen[$key])) {
                        continue;
                    }

                    if (!$slotService->isShiftStillBookable($date->toDateString(), $shift)) {
                        continue;
                    }

                    if (!$slotService->isSlotAvailable($doctor->id, $date->toDateString(), $shift)) {
                        continue;
                    }

                    $seen[$key] = true;
                    $formattedSchedules[] = [
                        'id' => $key,
                        'display' => ($dayLabels[$dayName] ?? $dayName) . ' - ' . $shiftLabels[$shift] . ' (' . $date->format('d/m/Y') . ')',
                        'date' => $date->toDateString(),
                        'shift' => $shift,
                    ];
                }
            }
        }

        return response()->json($formattedSchedules);


        if (false && false) {
            return response()->json(['error' => 'Không có lịch làm việc'], 404);
        }

        $formattedSchedules = [];

        foreach ([] as $schedule) {
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
        $ignoreAppointmentId = $request->query('ignore_appointment_id')
            ? (int) $request->query('ignore_appointment_id')
            : null;
        $slotService = app(AvailableSlotService::class);
        $dayOfWeek = date('l', strtotime($selectedDate)); // Lấy thứ trong tuần (Monday, Tuesday, ...)



        $workingHours = $doctor->working_hours;

        if (!is_array($workingHours)) {
            return response()->json(['error' => 'Lịch làm việc không hợp lệ'], 400);
        }

        $availableShifts = ['morning' => false, 'afternoon' => false];

        foreach ($workingHours as $entry) {
            if (isset($entry['day'], $entry['shift']) && $entry['day'] === $dayOfWeek) {
                if (($entry['shift'] === 'morning' || $entry['shift'] === 'both')
                    && $slotService->isShiftStillBookable($selectedDate, 'morning')
                    && $slotService->isSlotAvailable($doctor->id, $selectedDate, 'morning', $ignoreAppointmentId)) {
                    $availableShifts['morning'] = true;
                }
                if (($entry['shift'] === 'afternoon' || $entry['shift'] === 'both')
                    && $slotService->isShiftStillBookable($selectedDate, 'afternoon')
                    && $slotService->isSlotAvailable($doctor->id, $selectedDate, 'afternoon', $ignoreAppointmentId)) {
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

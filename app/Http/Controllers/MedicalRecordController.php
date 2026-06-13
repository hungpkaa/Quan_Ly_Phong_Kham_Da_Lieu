<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Doctor;

class MedicalRecordController extends Controller
{

    // Hiển thị danh sách hồ sơ bệnh án cho Admin và AdminDoctor
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');

        // Tạo truy vấn tìm kiếm
        $query = MedicalRecord::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhere('phone', 'like', "%{$search}%")
                       ->orWhere('cccd', 'like', "%{$search}%");
                })
                ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }

        if ($filter === 'incomplete') {
            $query->where(function ($q) {
                $q->whereNull('diagnosis')
                  ->orWhereNull('prescription')
                  ->orWhere('diagnosis', '')
                  ->orWhere('prescription', '');
            });
        }

        // Áp dụng tìm kiếm vào danh sách hồ sơ bệnh án
        $medicalRecords = $query->orderBy('id', 'asc')->paginate(10);


        // Lấy danh sách bác sĩ cho dropdown
        $doctors = Doctor::all();

        // Kiểm tra nếu có hồ sơ cần chỉnh sửa
        $editMedicalRecord = null;
        if ($request->has('edit_id')) {
            $editMedicalRecord = MedicalRecord::find($request->input('edit_id'));
        }

        return view('role.managemedicalrecords', compact('medicalRecords', 'doctors', 'search', 'editMedicalRecord'));
    }


    // Hiển thị giao diện tạo hồ sơ bệnh án mới
    public function create()
    {
        return redirect()->route('admin.medicalrecords.index');
    }

    public function edit($id)
    {
        MedicalRecord::findOrFail($id);

        return redirect()->route('admin.medicalrecords.index', ['edit_id' => $id]);
    }

    // Lưu hồ sơ bệnh án mới vào database
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'cccd' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,unpaid',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date|after:exam_date',
        ]);

        $user = \App\Models\User::where('phone', $request->phone)->first();

        if ($user && $user->role !== 'patient') {
            return back()
                ->withInput()
                ->with('error', 'Số điện thoại này đang thuộc tài khoản không phải bệnh nhân. Vui lòng dùng số điện thoại khác.');
        }

        if (!$user) {
            // Validate if the inputted email is already taken before creating new user
            if (\App\Models\User::where('email', $request->email)->exists()) {
                return back()->withInput()->withErrors(['email' => 'Email này đã được sử dụng bởi một tài khoản khác! Vui lòng nhập đúng số điện thoại của tài khoản đó.']);
            }
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'role' => 'patient',
                'age' => $request->age,
                'cccd' => $request->cccd,
            ]);
        } else {
            // Validate if the inputted email is taken by ANOTHER user
            if (\App\Models\User::where('email', $request->email)->where('id', '!=', $user->id)->exists()) {
                return back()->withInput()->withErrors(['email' => 'Email này đã được sử dụng bởi một tài khoản khác có số điện thoại khác!']);
            }
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'cccd' => $request->cccd,
            ]);
        }

        $request->merge([
            'user_id' => $user->id,
        ]);

        $record = MedicalRecord::create($request->all());

        // Create invoice automatically if cost is set
        if ($request->filled('cost') && $request->cost > 0) {
            \App\Models\Invoice::create([
                'medical_record_id' => $record->id,
                'services_medicines' => $record->service . "; " . $record->prescription,
                'invoice_date' => now()->format('Y-m-d'),
                'total_amount' => $request->cost,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('admin.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được tạo thành công.');
    }

    public function update(Request $request, $id)
    {
        $record = MedicalRecord::findOrFail($id);

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'cccd' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,unpaid',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date|after:exam_date',
        ]);
        
        if ($record->user) {
            if (\App\Models\User::where('email', $request->email)->where('id', '!=', $record->user->id)->exists()) {
                return back()->withInput()->withErrors(['email' => 'Email này đã được sử dụng bởi một tài khoản khác!']);
            }
            if (\App\Models\User::where('phone', $request->phone)->where('id', '!=', $record->user->id)->exists()) {
                return back()->withInput()->withErrors(['phone' => 'Số điện thoại này đã được sử dụng bởi một tài khoản khác!']);
            }
            $record->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'cccd' => $request->cccd,
            ]);
        }

        $record->update($request->all());

        // Sync with invoice if exists
        if ($record->invoice) {
            $record->invoice->update([
                'total_amount' => $request->cost ?? 0,
                'status' => $request->status,
                'services_medicines' => $record->service . "; " . $record->prescription,
            ]);
        } else if ($request->filled('cost') && $request->cost > 0) {
            \App\Models\Invoice::create([
                'medical_record_id' => $record->id,
                'services_medicines' => $record->service . "; " . $record->prescription,
                'invoice_date' => now()->format('Y-m-d'),
                'total_amount' => $request->cost,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('admin.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được cập nhật thành công.');
    }


    // Xóa hồ sơ bệnh án
    public function destroy($id)
    {
        $record = MedicalRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('admin.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được xóa thành công.');
    }
}

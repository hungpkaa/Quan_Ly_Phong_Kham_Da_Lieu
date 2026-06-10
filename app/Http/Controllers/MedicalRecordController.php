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
        $doctors = Doctor::all();
        return view('role.managemedicalrecords.create', compact('doctors'));
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
            'cost' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        // Nhân giá trị cost với 1000 nếu có
        if ($request->filled('cost')) {
            $request->merge(['cost' => $request->input('cost') * 1000]);
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

        $request->merge(['user_id' => $user->id]);

        MedicalRecord::create($request->all());

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
            'cost' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        if ($request->filled('cost')) {
            $request->merge(['cost' => $request->input('cost') * 1000]);
        }
        
        if ($record->user) {
            $record->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'cccd' => $request->cccd,
            ]);
        }

        $record->update($request->all());

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
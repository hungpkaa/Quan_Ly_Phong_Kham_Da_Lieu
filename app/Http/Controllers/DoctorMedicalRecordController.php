<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorMedicalRecordController extends Controller
{
    private function currentDoctor()
    {
        return Auth::user()->doctor;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $doctorId = $this->currentDoctor()->id;

        $query = MedicalRecord::where('doctor_id', $doctorId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('cccd', 'like', "%{$search}%")
                    ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }

        $medicalRecords = $query->latest()->paginate(10);

        $editMedicalRecord = null;
        if ($request->has('edit_id')) {
            $editMedicalRecord = MedicalRecord::where('doctor_id', $doctorId)->find($request->input('edit_id'));
        }

        return view('role.doctormanagemedicalrecords', compact('medicalRecords', 'search', 'editMedicalRecord'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'cccd' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $cost = $request->filled('cost') ? $request->input('cost') * 1000 : null;

        $user = \App\Models\User::where('phone', $request->phone)->first();
        if ($user) {
            // Validate if the inputted email is taken by ANOTHER user
            if (\App\Models\User::where('email', $request->email)->where('id', '!=', $user->id)->exists()) {
                return back()->withInput()->withErrors(['email' => 'Email này đã được sử dụng bởi một tài khoản khác có số điện thoại khác!']);
            }
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'age' => $request->age,
                'cccd' => $request->cccd,
            ]);
        } else {
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
        }

        $record = MedicalRecord::create([
            'doctor_id' => $this->currentDoctor()->id,
            'user_id' => $user->id,
            'service' => $request->service,
            'exam_date' => $request->exam_date,
            'cost' => $cost,
            'status' => $request->status ?? 'unpaid',
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
            'follow_up_date' => $request->follow_up_date,
        ]);

        \App\Models\Invoice::create([
            'medical_record_id' => $record->id,
            'services_medicines' => $record->service . ($record->prescription ? "; " . $record->prescription : ""),
            'invoice_date' => now()->format('Y-m-d'),
            'total_amount' => $cost ?? 0,
            'status' => 'unpaid',
        ]);

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được tạo thành công.');
    }

    public function edit($id)
    {
        $doctorId = $this->currentDoctor()->id;
        $record = MedicalRecord::where('doctor_id', $doctorId)->findOrFail($id);
        $medicalRecords = MedicalRecord::where('doctor_id', $doctorId)->latest()->paginate(10);

        return view('role.doctormanagemedicalrecords', compact('record', 'medicalRecords'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'cccd' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $record = MedicalRecord::where('doctor_id', $this->currentDoctor()->id)->findOrFail($id);

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

        $record->update([
            'service' => $request->service,
            'exam_date' => $request->exam_date,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
            'follow_up_date' => $request->follow_up_date,
        ]);

        if ($record->invoice) {
            $record->invoice->update([
                'services_medicines' => $record->service . ($record->prescription ? "; " . $record->prescription : ""),
            ]);
        }

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $record = MedicalRecord::where('doctor_id', $this->currentDoctor()->id)->findOrFail($id);
        $record->delete();

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Hồ sơ bệnh án đã được xóa thành công.');
    }

    public function createFromAppointment(Request $request)
    {
        $doctorId = $this->currentDoctor()->id;
        $appointment = Appointment::with('user')->where('doctor_id', $doctorId)
            ->findOrFail($request->input('appointment_id'));

        $editMedicalRecord = new MedicalRecord([
            'exam_date' => $appointment->appointment_date,
            'service' => $appointment->specialty ?: ('Khám ' . ($appointment->doctor && $appointment->doctor->specialty ? mb_strtolower($appointment->doctor->specialty, 'UTF-8') : 'da liễu')),
        ]);

        if ($appointment->user) {
            $editMedicalRecord->setRelation('user', $appointment->user);
        }

        $editMedicalRecord->id = null;

        $medicalRecords = MedicalRecord::where('doctor_id', $doctorId)->latest()->paginate(10);
        $search = null;

        return view('role.doctormanagemedicalrecords', compact('editMedicalRecord', 'medicalRecords', 'search'));
    }
}

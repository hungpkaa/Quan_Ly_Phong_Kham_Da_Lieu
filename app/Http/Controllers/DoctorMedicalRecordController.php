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
        return Doctor::where('email', Auth::user()->email)->firstOrFail();
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
            'cost' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $cost = $request->filled('cost') ? $request->input('cost') * 1000 : null;

        MedicalRecord::create([
            'doctor_id' => $this->currentDoctor()->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'cccd' => $request->cccd,
            'service' => $request->service,
            'exam_date' => $request->exam_date,
            'cost' => $cost,
            'status' => $request->status,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Ho so benh an da duoc tao thanh cong.');
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
        $record = MedicalRecord::where('doctor_id', $this->currentDoctor()->id)->findOrFail($id);

        $request->validate([
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
        ]);

        $cost = $request->filled('cost') ? $request->input('cost') * 1000 : null;

        $record->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'cccd' => $request->cccd,
            'service' => $request->service,
            'exam_date' => $request->exam_date,
            'cost' => $cost,
            'status' => $request->status,
            'diagnosis' => $request->diagnosis,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Ho so benh an da duoc cap nhat thanh cong.');
    }

    public function destroy($id)
    {
        $record = MedicalRecord::where('doctor_id', $this->currentDoctor()->id)->findOrFail($id);
        $record->delete();

        return redirect()->route('admindoctor.medicalrecords.index')
            ->with('success', 'Ho so benh an da duoc xoa thanh cong.');
    }

    public function createFromAppointment(Request $request)
    {
        $doctorId = $this->currentDoctor()->id;
        $appointment = Appointment::where('doctor_id', $doctorId)
            ->findOrFail($request->input('appointment_id'));

        $editMedicalRecord = new MedicalRecord([
            'name' => $appointment->name,
            'email' => $appointment->email,
            'phone' => $appointment->phone,
            'age' => $appointment->age,
            'cccd' => $appointment->cccd,
            'exam_date' => $appointment->appointment_date,
        ]);

        $editMedicalRecord->id = null;

        $medicalRecords = MedicalRecord::where('doctor_id', $doctorId)->latest()->get();

        return view('role.doctormanagemedicalrecords', compact('editMedicalRecord', 'medicalRecords'));
    }
}

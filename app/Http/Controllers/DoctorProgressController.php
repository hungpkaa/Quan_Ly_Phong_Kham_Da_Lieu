<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientProgress;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DoctorProgressController extends Controller
{
    private function currentDoctor()
    {
        return Auth::user()->doctor;
    }

    public function index(Request $request)
    {
        $doctorId = $this->currentDoctor()->id;
        $search = $request->input('search');

        $query = PatientProgress::with('user')->where('doctor_id', $doctorId);

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $progresses = $query->orderByDesc('created_at')->paginate(12);

        return view('role.doctorprogress', compact('progresses', 'search'));
    }

    public function destroy($id)
    {
        $doctorId = $this->currentDoctor()->id;
        $progress = PatientProgress::where('doctor_id', $doctorId)->findOrFail($id);

        if (Storage::disk('public')->exists($progress->image_path)) {
            Storage::disk('public')->delete($progress->image_path);
        }
        $progress->delete();

        return redirect()->route('admindoctor.progress.index')->with('success', 'Đã xóa ảnh tiến độ thành công!');
    }
}

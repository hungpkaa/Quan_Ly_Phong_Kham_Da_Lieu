<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PatientApiController extends Controller
{
    public function index()
    {
        $patients = User::where('role', 'patient')->latest()->get();

        return response()->json(['data' => $patients], 200);
    }

    public function show($id)
    {
        $patient = User::where('role', 'patient')->find($id);

        if (!$patient) {
            return response()->json(['error' => 'Không tìm thấy bệnh nhân.'], 404);
        }

        return response()->json(['data' => $patient], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'age' => 'nullable|integer|min:1',
            'cccd' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        $patient = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? ($validated['phone'] . '@patient.local'),
            'phone' => $validated['phone'],
            'age' => $validated['age'] ?? null,
            'cccd' => $validated['cccd'] ?? null,
            'password' => Hash::make($validated['password'] ?? '12345678'),
            'role' => 'patient',
        ]);

        return response()->json([
            'data' => $patient,
            'message' => 'Tạo bệnh nhân thành công.',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $patient = User::where('role', 'patient')->find($id);

        if (!$patient) {
            return response()->json(['error' => 'Không tìm thấy bệnh nhân.'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($patient->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($patient->id),
            ],
            'age' => 'nullable|integer|min:1',
            'cccd' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        $patient->fill([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? $patient->email,
            'phone' => $validated['phone'],
            'age' => $validated['age'] ?? null,
            'cccd' => $validated['cccd'] ?? null,
        ]);

        if (!empty($validated['password'])) {
            $patient->password = Hash::make($validated['password']);
        }

        $patient->save();

        return response()->json([
            'data' => $patient,
            'message' => 'Cập nhật bệnh nhân thành công.',
        ], 200);
    }

    public function destroy($id)
    {
        $patient = User::where('role', 'patient')->find($id);

        if (!$patient) {
            return response()->json(['error' => 'Không tìm thấy bệnh nhân.'], 404);
        }

        $patient->delete();

        return response()->json(['message' => 'Xóa bệnh nhân thành công.'], 200);
    }
}

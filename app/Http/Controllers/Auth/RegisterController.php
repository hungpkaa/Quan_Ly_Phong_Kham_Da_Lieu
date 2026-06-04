<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private const DEFAULT_REGISTER_ROLE = 'patient';

    public function showRegistrationForm()
    {
        return view('auth.register'); // Hiển thị form đăng ký
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => self::DEFAULT_REGISTER_ROLE,
        ]);

        return redirect('/login')->with('success', 'Tài khoản đã được tạo thành công!');
    }
}

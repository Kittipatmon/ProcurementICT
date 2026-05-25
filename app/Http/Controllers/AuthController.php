<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'emp_code' => 'required|string',
            'password' => 'required|string',
        ]);

        $employee = Employee::where('emp_code', $credentials['emp_code'])->first();

        if ($employee && Hash::check($credentials['password'], $employee->password)) {
            Auth::login($employee, $request->has('remember'));
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Fallback for cleartext checking if necessary (for seed data / existing users without bcrypt)
        if ($employee && $employee->password === $credentials['password']) {
            // Update password to hashed version
            $employee->password = $credentials['password'];
            $employee->save();
            
            Auth::login($employee, $request->has('remember'));
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'emp_code' => 'รหัสพนักงานหรือรหัสผ่านไม่ถูกต้อง',
        ])->onlyInput('emp_code');
    }

    public function showRegister()
    {
        $departments = Department::all();
        return view('auth.register', compact('departments'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'emp_code' => 'required|string|unique:employees,emp_code|max:20',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|string|email|unique:employees,email|max:100',
            'username' => 'required|string|unique:employees,username|max:50',
            'password' => 'required|string|min:6|confirmed',
            'dept_id' => 'required|exists:departments,id',
        ]);

        $employee = Employee::create([
            'emp_code' => $data['emp_code'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $data['password'], // hashed by cast
            'dept_id' => $data['dept_id'],
            'status' => 'active',
            'role' => 'staff',
            'procurement_role' => 'user',
        ]);

        Auth::login($employee);

        return redirect()->route('dashboard')->with('success', 'ลงทะเบียนผู้ใช้และเข้าสู่ระบบสำเร็จ');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

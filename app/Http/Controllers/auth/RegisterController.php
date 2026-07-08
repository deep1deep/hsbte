<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Show the register form (with departments for the dropdown)
    public function show()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('auth.register', compact('departments'));
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'enrollment_no' => ['required', 'string', 'max:50', 'unique:users,enrollment_no'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'         => ['nullable', 'string', 'max:15'],
            'department_id' => ['required', 'exists:departments,id'],
            'semester'      => ['required', 'string', 'max:10'],
            'institute'     => ['nullable', 'string', 'max:255'],
            'password'      => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name'          => $validated['name'],
            'enrollment_no' => $validated['enrollment_no'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'department_id' => $validated['department_id'],
            'semester'      => $validated['semester'],
            'institute'     => $validated['institute'] ?? null,
            'password'      => $validated['password'],   // model cast auto-hashes
            'role'          => 'student',   // 🔒 FORCED — never from the form
            'is_active'     => true,
        ]);

        // Auto-login the new student, then send to their dashboard
        Auth::login($user);
        $request->session()->regenerate();   // session-fixation protection

        return redirect()->route('student.dashboard');
    }
}
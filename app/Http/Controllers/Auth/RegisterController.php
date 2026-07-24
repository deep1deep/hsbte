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
            'email'         => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'phone'         => ['required', 'regex:/^[6-9]\d{9}$/', 'unique:users,phone'],
            'department_id' => ['required', 'exists:departments,id'],
            'semester'      => ['required', 'string', 'max:10'],
            'institute'     => ['nullable', 'string', 'max:255'],
            'aadhaar'       => ['required', 'digits:12', 'regex:/^[2-9]\d{11}$/'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique'         => 'This email is already registered.',
            'email.email'          => 'Enter a valid email address.',
            'phone.required'       => 'Mobile number is required.',
            'phone.regex'          => 'Enter a valid 10-digit Indian mobile number.',
            'phone.unique'         => 'This mobile number is already registered.',
            'aadhaar.required'     => 'Aadhaar number is required.',
            'aadhaar.digits'       => 'Aadhaar must be exactly 12 digits.',
            'aadhaar.regex'        => 'Enter a valid Aadhaar number.',
        ]);

        // Verhoeff check-digit — Aadhaar's real 12th digit. Rejects random 12-digit fakes.
        if (! $this->verhoeffValid($validated['aadhaar'])) {
            return back()
                ->withErrors(['aadhaar' => 'This Aadhaar number is not valid.'])
                ->withInput();
        }

        // Duplicate-account check — we store ONLY the hash, never the raw Aadhaar number.
        $aadhaarHash = hash('sha256', $validated['aadhaar']);
        if (User::where('aadhaar_hash', $aadhaarHash)->exists()) {
            return back()
                ->withErrors(['aadhaar' => 'This Aadhaar is already registered.'])
                ->withInput();
        }

        $user = User::create([
            'name'          => $validated['name'],
            'email'         => strtolower($validated['email']),
            'phone'         => $validated['phone'],
            'department_id' => $validated['department_id'],
            'semester'      => $validated['semester'],
            'institute'     => $validated['institute'] ?? null,
            'aadhaar_hash'  => $aadhaarHash,
            'password'      => $validated['password'],   // model cast auto-hashes
            'role'          => 'student',   // 🔒 FORCED — never from the form
            'is_active'     => true,
        ]);

        // Auto-login the new student, then send to their dashboard
        Auth::login($user);
        $request->session()->regenerate();   // session-fixation protection

        return redirect()->route('student.dashboard');
    }

    // Verhoeff algorithm — validates Aadhaar's built-in checksum digit
    private function verhoeffValid(string $number): bool
    {
        $d = [
            [0,1,2,3,4,5,6,7,8,9],
            [1,2,3,4,0,6,7,8,9,5],
            [2,3,4,0,1,7,8,9,5,6],
            [3,4,0,1,2,8,9,5,6,7],
            [4,0,1,2,3,9,5,6,7,8],
            [5,9,8,7,6,0,4,3,2,1],
            [6,5,9,8,7,1,0,4,3,2],
            [7,6,5,9,8,2,1,0,4,3],
            [8,7,6,5,9,3,2,1,0,4],
            [9,8,7,6,5,4,3,2,1,0],
        ];
        $p = [
            [0,1,2,3,4,5,6,7,8,9],
            [1,5,7,6,2,8,3,0,9,4],
            [5,8,0,9,1,6,7,3,4,2],
            [8,9,1,6,0,4,3,5,2,7],
            [9,4,5,3,1,2,6,8,7,0],
            [4,2,8,6,5,7,3,9,0,1],
            [2,7,9,3,8,0,6,4,1,5],
            [7,0,4,6,9,1,3,2,5,8],
        ];

        $c = 0;
        foreach (array_reverse(str_split($number)) as $i => $digit) {
            $c = $d[$c][$p[$i % 8][(int) $digit]];
        }
        return $c === 0;
    }
}
@extends('layouts.app')

@section('title', 'Create Account - HSBTE Training Portal')

@section('content')

<section class="auth-wrap">
    <div class="container">
        <div class="auth-card auth-card-wide">

            <!-- Left brand panel -->
            <div class="auth-brand">
                <div class="brand-badge"></div>
                <h2>Create your free student account</h2>
                <p>One account for all courses, progress tracking and certificates.</p>
                <ul class="auth-points">
                    <li><i class="bi bi-check-circle-fill"></i> 100% free for Haryana students</li>
                    <li><i class="bi bi-check-circle-fill"></i> Certificates employers can verify</li>
                    <li><i class="bi bi-check-circle-fill"></i> Resume lessons on any device</li>
                </ul>
            </div>

            <!-- Right form panel -->
            <div class="auth-form">
                <h4>Create Student Account</h4>
                <p class="auth-sub">Fill in your details to get started</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.attempt') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control" placeholder="Rahul Sharma" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Enrollment number</label>
                            <input type="text" name="enrollment_no" value="{{ old('enrollment_no') }}"
                                   class="form-control" placeholder="210012345678" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email address</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="you@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="form-control" placeholder="98XXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">Select department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="">Select semester</option>
                                @foreach(['1st','2nd','3rd','4th','5th','6th'] as $sem)
                                    <option value="{{ $sem }}" @selected(old('semester') == $sem)>{{ $sem }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Institute</label>
                            <input type="text" name="institute" value="{{ old('institute') }}"
                                   class="form-control" placeholder="GP Nilokheri">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control" placeholder="Min. 8 characters" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-navy w-100 mt-4">Create Free Account</button>
                </form>

                <div class="auth-links">
                    Already have an account? <a href="/login">Login here</a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
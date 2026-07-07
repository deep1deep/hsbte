@extends('layouts.app')

@section('title', $role . ' Login - HSBTE Training Portal')

@section('content')

<section class="auth-wrap">
    <div class="container">
        <div class="auth-card">

            <!-- Left brand panel -->
            <div class="auth-brand">
                <div class="brand-badge"></div>
                <h2>Welcome back to HSBTE Training Portal</h2>
                <p>Continue your courses, track progress and download certificates.</p>
                <ul class="auth-points">
                    <li><i class="bi bi-check-circle-fill"></i> 120+ free courses</li>
                    <li><i class="bi bi-check-circle-fill"></i> Government verified certificates</li>
                    <li><i class="bi bi-check-circle-fill"></i> Learn on any device</li>
                </ul>
            </div>

            <!-- Right form panel -->
            <div class="auth-form">
                <h4>{{ $role }} Login</h4>
                <p class="auth-sub">Enter your registered email and password</p>

                <form method="POST" action="#">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" class="form-control" placeholder="you@example.com" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Password</label>
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                            <input type="password" class="form-control" placeholder="Enter password" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 auth-row">
                        <label class="auth-remember">
                            <input type="checkbox"> Remember me
                        </label>
                        <a href="#">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-navy w-100">Login</button>
                </form>

                <div class="auth-links">
                    @if($role === 'Student')
                        New student? <a href="/register">Create free account</a>
                        <span class="mx-1">·</span>
                        <a href="/trainer/login">Trainer login</a>
                    @else
                        <a href="/login">Student login</a>
                        <span class="mx-1">·</span>
                        Want to teach? Contact your institute
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
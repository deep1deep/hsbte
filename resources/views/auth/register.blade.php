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

                <form method="POST" action="#">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full name</label>
                            <input type="text" class="form-control" placeholder="Rahul Sharma" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Enrollment number</label>
                            <input type="text" class="form-control" placeholder="210012345678" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" placeholder="you@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" placeholder="98XXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select class="form-select" required>
                                <option value="">Select department</option>
                                <option>Computer Science</option>
                                <option>Electronics</option>
                                <option>Mechanical</option>
                                <option>Civil</option>
                                <option>Electrical</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select class="form-select" required>
                                <option value="">Select semester</option>
                                <option>1st</option><option>2nd</option><option>3rd</option>
                                <option>4th</option><option>5th</option><option>6th</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Institute</label>
                            <input type="text" class="form-control" placeholder="GP Nilokheri">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" placeholder="Min. 8 characters" required>
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
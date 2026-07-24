@extends('layouts.app')

@section('title', 'My Profile - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container" style="max-width:960px;">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">My Profile</h2>
                <p class="text-muted mb-0">Manage your account details and password</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-navy btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <div class="row g-4">

            {{-- LEFT: identity summary --}}
            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card-body text-center">
                        <div class="profile-avatar">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                        <h3 class="h6 mt-3 mb-1" style="color:#1f2f4d;">{{ $user->name }}</h3>
                        <p class="text-muted small mb-3">{{ $user->email }}</p>

                        <ul class="profile-facts">
                            <li>
                                <span><i class="bi bi-diagram-3"></i> Department</span>
                                <strong>{{ $user->department->code ?? '—' }}</strong>
                            </li>
                            <li>
                                <span><i class="bi bi-journal-bookmark"></i> Courses</span>
                                <strong>{{ $user->enrollments_count }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- RIGHT: editable forms --}}
            <div class="col-lg-8">

                {{-- Account details --}}
                <div class="admin-card">
                    <div class="admin-card-head"><i class="bi bi-person-lines-fill me-1"></i> Account details</div>
                    <div class="admin-card-body">
                        <form method="POST" action="{{ route('student.profile.update') }}">
                            @csrf
                            @method('PATCH')

                            @error('name') <div class="alert alert-danger py-2 small">{{ $message }}</div> @enderror
                            @error('phone') <div class="alert alert-danger py-2 small">{{ $message }}</div> @enderror

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small">Full name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Email <span class="text-muted">(cannot be changed)</span></label>
                                    <input type="email" value="{{ $user->email }}" class="form-control" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Phone</label>
                                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Semester</label>
                                    <input type="text" name="semester" value="{{ old('semester', $user->semester) }}" class="form-control" placeholder="e.g. 4">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small">Institute</label>
                                    <input type="text" name="institute" value="{{ old('institute', $user->institute) }}" class="form-control" placeholder="Your polytechnic / institute name">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-navy mt-3">Save changes</button>
                        </form>
                    </div>
                </div>

                {{-- Change password --}}
                <div class="admin-card">
                    <div class="admin-card-head"><i class="bi bi-shield-lock-fill me-1"></i> Change password</div>
                    <div class="admin-card-body">
                        <form method="POST" action="{{ route('student.profile.password') }}">
                            @csrf
                            @method('PATCH')

                            @error('current_password') <div class="alert alert-danger py-2 small">{{ $message }}</div> @enderror
                            @error('password') <div class="alert alert-danger py-2 small">{{ $message }}</div> @enderror

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small">Current password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">New password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Confirm new password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-navy mt-3">Update password</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
@endsection

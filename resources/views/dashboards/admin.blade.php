@extends('layouts.app')

@section('title', 'Admin Dashboard - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Admin Dashboard</h2>
                <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-navy btn-sm">Logout</button>
            </form>
        </div>

        {{-- Success message after adding trainer --}}
        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['students'] }}</div><div class="stat-label">Students</div></div>
                    <div class="stat-ico stat-ico-navy"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['trainers'] }}</div><div class="stat-label">Trainers</div></div>
                    <div class="stat-ico stat-ico-gold"><i class="bi bi-easel-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['courses'] }}</div><div class="stat-label">Courses</div></div>
                    <div class="stat-ico stat-ico-teal"><i class="bi bi-book-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['enrollments'] }}</div><div class="stat-label">Enrollments</div></div>
                    <div class="stat-ico stat-ico-purple"><i class="bi bi-clipboard-check-fill"></i></div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- LEFT: students + enrollments --}}
            <div class="col-lg-7">

                <div class="admin-card">
                    <div class="admin-card-head">Recent Students</div>
                    <div class="admin-card-body">
                        <table class="admin-table">
                            <thead>
                                <tr><th>Name</th><th>Email</th><th>Dept</th><th>Sem</th></tr>
                            </thead>
                            <tbody>
                                @forelse($students as $s)
                                    <tr>
                                        <td>{{ $s->name }}</td>
                                        <td>{{ $s->email }}</td>
                                        <td>
                                            @if($s->department)
                                                <span class="badge-dept">{{ $s->department->code }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $s->semester ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted">No students yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-head">Enrollments per Course</div>
                    <div class="admin-card-body">
                        <table class="admin-table">
                            <thead>
                                <tr><th>Course</th><th style="text-align:right;">Enrolled</th></tr>
                            </thead>
                            <tbody>
                                @forelse($courseEnrollments as $c)
                                    <tr>
                                        <td>{{ $c->title }}</td>
                                        <td style="text-align:right;font-weight:600;color:#0d2a5c;">{{ $c->enrollments_count }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-muted">No courses yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- RIGHT: add trainer + trainer list --}}
            <div class="col-lg-5">

                <div class="admin-card">
                    <div class="admin-card-head"><i class="bi bi-person-plus-fill me-1"></i> Add Trainer</div>
                    <div class="admin-card-body">

                        @if($errors->any())
                            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('admin.trainers.store') }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small">Full name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Department</label>
                                <select name="department_id" class="form-select" required>
                                    <option value="">Select department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation') }}" class="form-control" placeholder="e.g. Assistant Professor">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Qualification</label>
                                <input type="text" name="qualification" value="{{ old('qualification') }}" class="form-control" placeholder="e.g. M.Tech CSE">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                            </div>
                            <button type="submit" class="btn btn-navy w-100">Add Trainer</button>
                        </form>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-head">Trainers ({{ $trainers->count() }})</div>
                    <div class="admin-card-body">
                        <table class="admin-table">
                            <tbody>
                                @forelse($trainers as $t)
                                    <tr>
                                        <td>
                                            <div>{{ $t->name }}</div>
                                            <div class="text-muted small">{{ $t->designation ?? 'Trainer' }}</div>
                                        </td>
                                        <td>
                                            @if($t->department)
                                                <span class="badge-dept">{{ $t->department->code }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="text-muted">No trainers yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
@endsection
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
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.students') }}" class="btn btn-outline-navy btn-sm">
                    <i class="bi bi-people"></i> Students
                </a>
                <a href="{{ route('admin.courses') }}" class="btn btn-outline-navy btn-sm">
                    <i class="bi bi-book"></i> Courses
                </a>
                <a href="{{ route('admin.announcements') }}" class="btn btn-navy btn-sm">
                    <i class="bi bi-megaphone"></i> Manage Notices
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-navy btn-sm">Logout</button>
                </form>
            </div>
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

        {{-- ENROLLMENT TREND (last 30 days) --}}
        <div class="admin-card mb-4">
            <div class="admin-card-head d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>Enrollment Trend <span class="text-muted small">— last 30 days</span></span>
                <span class="text-muted small">{{ $trend['total'] }} new enrollment{{ $trend['total'] == 1 ? '' : 's' }}</span>
            </div>
            <div class="admin-card-body">
                <div class="trend-chart" role="img"
                     aria-label="Bar chart of new enrollments per day over the last 30 days, {{ $trend['total'] }} total.">
                    @foreach($trend['counts'] as $i => $count)
                        @php($h = round($count / $trend['max'] * 100))
                        <div class="trend-col">
                            <div class="trend-bar-wrap">
                                <div class="trend-bar" style="height:{{ max($count > 0 ? 6 : 0, $h) }}%;"
                                     title="{{ $trend['labels'][$i] }}: {{ $count }}"></div>
                            </div>
                            @if($i % 5 === 0)
                                <div class="trend-tick">{{ $trend['labels'][$i] }}</div>
                            @else
                                <div class="trend-tick">&nbsp;</div>
                            @endif
                        </div>
                    @endforeach
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
                            <div class="alert alert-danger py-2 small">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
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
                        <table class="admin-table align-middle">
                            <tbody>
                                @forelse($trainers as $t)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span>{{ $t->name }}</span>
                                                @unless($t->is_active)
                                                    <span class="badge text-bg-secondary">Disabled</span>
                                                @endunless
                                            </div>
                                            <div class="text-muted small">{{ $t->designation ?? 'Trainer' }}</div>
                                        </td>
                                        <td>
                                            @if($t->department)
                                                <span class="badge-dept">{{ $t->department->code }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-navy" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item" type="button"
                                                                data-bs-toggle="modal" data-bs-target="#editTrainer{{ $t->id }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit details
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" type="button"
                                                                data-bs-toggle="modal" data-bs-target="#resetTrainer{{ $t->id }}">
                                                            <i class="bi bi-key me-2"></i> Reset password
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.trainers.toggle', $t) }}">
                                                            @csrf @method('PATCH')
                                                            <button class="dropdown-item {{ $t->is_active ? 'text-danger' : 'text-success' }}" type="submit">
                                                                @if($t->is_active)
                                                                    <i class="bi bi-slash-circle me-2"></i> Disable account
                                                                @else
                                                                    <i class="bi bi-check-circle me-2"></i> Enable account
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
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

{{-- ===== TRAINER EDIT / RESET-PASSWORD MODALS ===== --}}
@foreach($trainers as $t)
    {{-- Edit details --}}
    <div class="modal fade" id="editTrainer{{ $t->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.trainers.update', $t) }}" class="modal-content">
                @csrf @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Edit trainer — {{ $t->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label small">Full name</label>
                        <input type="text" name="name" value="{{ $t->name }}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Phone</label>
                        <input type="tel" name="phone" value="{{ $t->phone }}" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Department</label>
                        <select name="department_id" class="form-select" required>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" @selected($t->department_id == $dept->id)>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Designation</label>
                        <input type="text" name="designation" value="{{ $t->designation }}" class="form-control">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small">Qualification</label>
                        <input type="text" name="qualification" value="{{ $t->qualification }}" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-navy btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-navy btn-sm">Save changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reset password --}}
    <div class="modal fade" id="resetTrainer{{ $t->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.trainers.password', $t) }}" class="modal-content">
                @csrf @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Reset password — {{ $t->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Set a new password for this trainer. They can change it later after logging in.</p>
                    <div class="mb-2">
                        <label class="form-label small">New password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-navy btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-navy btn-sm">Reset password</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection
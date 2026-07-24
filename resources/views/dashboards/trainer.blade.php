@extends('layouts.app')

@section('title', 'Trainer Dashboard - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Trainer Dashboard</h2>
                <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div class="d-flex gap-2">
                {{-- Certificate "Design" button hidden — feature is not wired up yet.
                     Re-enable by restoring the link to route('trainer.certificate.design'). --}}
                <a href="{{ route('trainer.certificates') }}" class="btn btn-outline-navy position-relative">
                    <i class="bi bi-award me-1"></i> Certificates
                    @if($stats['pending'] > 0)
                        <span class="badge rounded-pill text-bg-danger position-absolute top-0 start-100 translate-middle">
                            {{ $stats['pending'] }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('trainer.courses.create') }}" class="btn btn-navy">
                    <i class="bi bi-plus-lg me-1"></i> Create New Course
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-navy">Logout</button>
                </form>
            </div>
        </div>

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        {{-- Pending certificates alert --}}
        @if($stats['pending'] > 0)
            <div class="alert alert-warning d-flex justify-content-between align-items-center py-2 flex-wrap gap-2">
                <span>
                    <i class="bi bi-clock-history me-1"></i>
                    <strong>{{ $stats['pending'] }}</strong>
                    {{ $stats['pending'] == 1 ? 'certificate is' : 'certificates are' }} waiting —
                    these students have completed the course, your upload is pending.
                </span>
                <a href="{{ route('trainer.certificates') }}" class="btn btn-sm btn-navy">Upload now</a>
            </div>
        @endif

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['courses'] }}</div><div class="stat-label">My Courses</div></div>
                    <div class="stat-ico stat-ico-navy"><i class="bi bi-book-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['published'] }}</div><div class="stat-label">Published</div></div>
                    <div class="stat-ico stat-ico-teal"><i class="bi bi-check-circle-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['students'] }}</div><div class="stat-label">Enrolled Students</div></div>
                    <div class="stat-ico stat-ico-gold"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['pending'] }}</div><div class="stat-label">Pending Certificates</div></div>
                    <div class="stat-ico stat-ico-navy"><i class="bi bi-award-fill"></i></div>
                </div>
            </div>
        </div>

        {{-- MY COURSES --}}
        <div class="admin-card">
            <div class="admin-card-head d-flex justify-content-between align-items-center">
                <span>My Courses</span>
            </div>
            <div class="admin-card-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Dept</th>
                            <th class="text-center">Modules</th>
                            <th class="text-center">Enrolled</th>
                            <th>Status</th>
                            <th>Certificate</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>
                                    <a href="{{ route('trainer.courses.manage', $course) }}"
                                       class="text-decoration-none" style="font-weight:600;color:#1f2f4d;">
                                        {{ $course->title }}
                                    </a>
                                </td>
                                <td>
                                    @if($course->department)
                                        <span class="badge-dept">{{ $course->department->code }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $course->modules_count }}</td>
                                <td class="text-center">
                                    @if($course->enrollments_count > 0)
                                        <a href="{{ route('trainer.courses.students', $course) }}"
                                           class="text-decoration-none" style="font-weight:600;color:#0d2a5c;">
                                            {{ $course->enrollments_count }}
                                        </a>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($course->status === 'published')
                                        <span class="badge text-bg-success">Published</span>
                                    @elseif($course->status === 'draft')
                                        <span class="badge text-bg-secondary">Draft</span>
                                    @else
                                        <span class="badge text-bg-dark">Archived</span>
                                    @endif
                                </td>
                                <td>
                                    @if($course->usesManualCertificates())
                                        <span class="badge text-bg-secondary">Manual</span>
                                    @else
                                        <span class="badge text-bg-info">Auto</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        @if($course->enrollments_count > 0)
                                            <a href="{{ route('trainer.courses.students', $course) }}"
                                               class="btn btn-sm btn-outline-navy" title="View enrolled students">
                                                <i class="bi bi-people"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('trainer.courses.manage', $course) }}" class="btn btn-sm btn-outline-navy">Manage</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No courses yet. Click "Create New Course" to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RECENT ENROLLMENTS --}}
        <div class="admin-card">
            <div class="admin-card-head">Recent Enrollments</div>
            <div class="admin-card-body">
                @forelse($recentEnrollments as $enrollment)
                    <div class="d-flex align-items-center gap-3 py-2 {{ ! $loop->last ? 'border-bottom' : '' }}">
                        <div class="enroll-avatar">
                            {{ strtoupper(mb_substr($enrollment->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div style="font-weight:600;color:#1f2f4d;">
                                {{ $enrollment->user->name }}
                                @if($enrollment->user->department)
                                    <span class="badge-dept ms-1">{{ $enrollment->user->department->code }}</span>
                                @endif
                            </div>
                            <div class="text-muted small text-truncate">
                                enrolled in <strong>{{ $enrollment->course->title }}</strong>
                            </div>
                        </div>
                        <div class="text-muted small text-nowrap">
                            {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->diffForHumans() : '—' }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4 mb-0">
                        No enrollments yet. Publish a course to start receiving students.
                    </p>
                @endforelse
            </div>
        </div>

    </div>
</section>
@endsection
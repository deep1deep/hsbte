@extends('layouts.app')

@section('title', 'Enrolled Students - ' . $course->title)

@section('content')
<section class="section-pad">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <div class="text-muted small mb-1">
                    <a href="{{ route('trainer.dashboard') }}" class="text-decoration-none">Dashboard</a>
                    <i class="bi bi-chevron-right" style="font-size:.7rem;"></i>
                    <a href="{{ route('trainer.courses.manage', $course) }}" class="text-decoration-none">{{ $course->title }}</a>
                </div>
                <h2 class="mb-1">Enrolled Students</h2>
                <p class="text-muted mb-0">{{ $course->title }}</p>
            </div>
            <a href="{{ route('trainer.courses.manage', $course) }}" class="btn btn-outline-navy btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to course
            </a>
        </div>

        {{-- Quick stats --}}
        @php
            $completedCount = $enrollments->where('status', 'completed')->count();
            $activeCount    = $enrollments->count() - $completedCount;
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $enrollments->count() }}</div><div class="stat-label">Total Enrolled</div></div>
                    <div class="stat-ico stat-ico-navy"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $completedCount }}</div><div class="stat-label">Completed</div></div>
                    <div class="stat-ico stat-ico-teal"><i class="bi bi-check-circle-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $activeCount }}</div><div class="stat-label">In Progress</div></div>
                    <div class="stat-ico stat-ico-gold"><i class="bi bi-hourglass-split"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $totalLessons }}</div><div class="stat-label">Lessons in Course</div></div>
                    <div class="stat-ico stat-ico-purple"><i class="bi bi-collection-play-fill"></i></div>
                </div>
            </div>
        </div>

        {{-- Students table --}}
        <div class="admin-card">
            <div class="admin-card-head">Student Progress</div>
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="admin-table align-middle">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Dept</th>
                                <th>Enrolled</th>
                                <th style="min-width:180px;">Progress</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                                @php
                                    $done = $enrollment->completed_lessons_count ?? 0;
                                    $pct  = $totalLessons > 0 ? (int) round($done / $totalLessons * 100) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div style="font-weight:600;color:#1f2f4d;">{{ $enrollment->user->name }}</div>
                                        <div class="text-muted small">
                                            {{ $enrollment->user->email }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($enrollment->user->department)
                                            <span class="badge-dept">{{ $enrollment->user->department->code }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('d M Y') : '—' }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:7px;max-width:160px;">
                                                <div class="progress-bar" role="progressbar"
                                                     style="width:{{ $pct }}%;background:{{ $pct == 100 ? '#0f6e56' : '#0d2a5c' }};"
                                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="small text-muted" style="white-space:nowrap;">
                                                {{ $done }}/{{ $totalLessons }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($enrollment->status === 'completed')
                                            <span class="badge text-bg-success">Completed</span>
                                        @elseif($pct > 0)
                                            <span class="badge text-bg-primary">In progress</span>
                                        @else
                                            <span class="badge text-bg-secondary">Not started</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-people" style="font-size:28px;color:#a5b0c6;"></i>
                                        <p class="mt-2 mb-0">No students have enrolled in this course yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

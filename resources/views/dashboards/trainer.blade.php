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

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-4">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['courses'] }}</div><div class="stat-label">My Courses</div></div>
                    <div class="stat-ico stat-ico-navy"><i class="bi bi-book-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['published'] }}</div><div class="stat-label">Published</div></div>
                    <div class="stat-ico stat-ico-teal"><i class="bi bi-check-circle-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $stats['students'] }}</div><div class="stat-label">Enrolled Students</div></div>
                    <div class="stat-ico stat-ico-gold"><i class="bi bi-people-fill"></i></div>
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
                                <td class="text-center">{{ $course->enrollments_count }}</td>
                                <td>
                                    @if($course->status === 'published')
                                        <span class="badge text-bg-success">Published</span>
                                    @elseif($course->status === 'draft')
                                        <span class="badge text-bg-secondary">Draft</span>
                                    @else
                                        <span class="badge text-bg-dark">Archived</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    {{-- T3 me wire hoga --}}
                                    <a href="{{ route('trainer.courses.manage', $course) }}" class="btn btn-sm btn-outline-navy">Manage</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No courses yet. Click "Create New Course" to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
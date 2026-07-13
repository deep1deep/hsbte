@extends('layouts.app')

@section('title', 'Explore Courses - HSBTE Training Portal')

@section('content')

<!-- ================= COURSES HERO ================= -->
<section class="courses-hero">
    <div class="container text-center">
        <h1>Explore Courses</h1>
        <p>Free certified programmes for Haryana students</p>
    </div>
</section>

<!-- ================= COURSE GRID ================= -->
<section class="section-pad">
    <div class="container">

        <div class="results-bar">
            <span>Showing {{ $courses->count() }} {{ $courses->count() === 1 ? 'course' : 'courses' }}</span>
        </div>

        <div class="row g-4">
            @forelse($courses as $course)
                <div class="col-md-6 col-lg-4">
                    <div class="course-card h-100 d-flex flex-column">
                        <div class="course-thumb course-thumb-navy">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        <div class="course-body d-flex flex-column flex-grow-1">
                            <span class="course-dept">
                                {{ $course->department->name ?? 'General' }}
                            </span>
                            <h5>{{ $course->title }}</h5>

                            <p class="text-muted small flex-grow-1">
                                {{ Str::limit($course->description, 80) ?: 'No description yet.' }}
                            </p>

                            <div class="course-meta">
                                <span><i class="bi bi-diagram-3"></i> {{ $course->modules_count ?? 0 }} modules</span>
                                @if($course->duration_weeks)
                                    <span><i class="bi bi-clock"></i> {{ $course->duration_weeks }} weeks</span>
                                @endif
                            </div>

                            <div class="course-foot">
                                <span class="course-free">FREE</span>
                                <a href="{{ route('course.detail', $course) }}" class="btn btn-sm btn-navy">View Course</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-journal-x" style="font-size:32px;color:#a5b0c6;"></i>
                        <p class="mt-2 mb-0">No published courses yet. Check back soon.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</section>

@endsection
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

        {{-- ---------- SEARCH + DEPARTMENT FILTER ---------- --}}
        {{-- GET form: filters URL me rehte hain, so link share/bookmark ho sakta hai --}}
        <form method="GET" action="{{ route('courses') }}" class="row g-2 align-items-center mb-4">
            <div class="col-12 col-md-6">
                <label for="q" class="visually-hidden">Search courses</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" class="form-control" id="q" name="q"
                           value="{{ $search }}"
                           placeholder="Search by course name or description">
                </div>
            </div>

            <div class="col-8 col-md-4">
                <label for="department" class="visually-hidden">Filter by department</label>
                <select class="form-select" id="department" name="department">
                    <option value="">All departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            @selected($departmentId === $department->id)>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-4 col-md-2 d-grid">
                <button type="submit" class="btn btn-navy">Search</button>
            </div>
        </form>

        <div class="results-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>
                @if($courses->total() > 0)
                    Showing {{ $courses->firstItem() }}–{{ $courses->lastItem() }}
                    of {{ $courses->total() }} {{ $courses->total() === 1 ? 'course' : 'courses' }}
                @else
                    No courses found
                @endif
            </span>

            @if($search !== '' || $departmentId)
                <a href="{{ route('courses') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-lg"></i> Clear filters
                </a>
            @endif
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

                            <div class="mb-2">
                                @include('partials.stars', ['avg' => $course->averageRating(), 'count' => $course->reviewsCount()])
                            </div>

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
                        @if($search !== '' || $departmentId)
                            <p class="mt-2 mb-2">No courses match your search.</p>
                            <a href="{{ route('courses') }}" class="btn btn-sm btn-outline-navy">
                                Show all courses
                            </a>
                        @else
                            <p class="mt-2 mb-0">No published courses yet. Check back soon.</p>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ---------- PAGINATION (6 per page) ---------- --}}
        @if($courses->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $courses->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</section>

@endsection
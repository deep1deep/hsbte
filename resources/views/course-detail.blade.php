@extends('layouts.app')

@section('title', $course->title . ' - HSBTE')

@section('content')

{{-- ===== NAVY BANNER ===== --}}
<section style="background:#0d2a5c;color:#fff;padding:48px 0;">
    <div class="container">
        <div class="mb-2">
            <a href="{{ route('courses') }}" class="text-decoration-none" style="color:#a5b0c6;font-size:14px;">
                <i class="bi bi-arrow-left"></i> All courses
            </a>
        </div>
        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            @if($course->department)
                <span class="badge" style="background:#f0a500;color:#0d2a5c;">{{ $course->department->name }}</span>
            @endif
            @if(!$course->is_paid)
                <span class="badge" style="background:#0f6e56;">Free</span>
            @endif
        </div>
        <h1 class="mb-2" style="font-weight:700;">{{ $course->title }}</h1>
        <p class="mb-3" style="color:#cdd6e8;max-width:680px;">{{ $course->description }}</p>
        <div class="d-flex gap-4 flex-wrap" style="color:#a5b0c6;font-size:14px;">
            <span><i class="bi bi-collection-play"></i> {{ $lessonCount }} {{ $lessonCount === 1 ? 'lesson' : 'lessons' }}</span>
            <span><i class="bi bi-diagram-3"></i> {{ $course->modules->count() }} modules</span>
            @if($course->duration_weeks)
                <span><i class="bi bi-clock"></i> {{ $course->duration_weeks }} weeks</span>
            @endif
            @if($course->trainer)
                <span><i class="bi bi-person-circle"></i> {{ $course->trainer->name }}</span>
            @endif
        </div>
    </div>
</section>

{{-- ===== BODY ===== --}}
<section class="section-pad">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <div class="row g-4">

            {{-- LEFT: content --}}
            <div class="col-lg-8">
                <h4 class="mb-3" style="color:#1f2f4d;">Course content</h4>

                @forelse($course->modules as $module)
                    <div class="admin-card mb-3">
                        <div class="admin-card-head d-flex justify-content-between align-items-center">
                            <span>{{ $module->title }}</span>
                            <span class="text-muted small">
                                {{ $module->lessons->count() }} {{ $module->lessons->count() === 1 ? 'lesson' : 'lessons' }}
                            </span>
                        </div>
                        <div class="admin-card-body">
                            @forelse($module->lessons as $lesson)
                                <div class="d-flex align-items-center gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <i class="bi {{ $lesson->isVideo() ? 'bi-play-circle' : 'bi-file-earmark-text' }}" style="color:#0d2a5c;"></i>
                                    <span class="flex-grow-1" style="color:#3d4f73;">{{ $lesson->title }}</span>
                                    @if($lesson->duration_minutes)
                                        <span class="text-muted small">{{ $lesson->duration_minutes }} min</span>
                                    @endif
                                    <i class="bi bi-lock text-muted small" title="Enroll to watch"></i>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Lessons coming soon.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="admin-card">
                        <div class="admin-card-body text-center text-muted py-4">
                            Course content will be added soon.
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- RIGHT: sticky enroll card --}}
            <div class="col-lg-4">
                <div class="admin-card" style="position:sticky;top:90px;">
                    <div class="admin-card-body text-center">

                        <div style="font-size:28px;font-weight:700;color:#0f6e56;" class="mb-1">
                            {{ $course->is_paid ? '₹' . number_format($course->price / 100) : 'Free' }}
                        </div>
                        <p class="text-muted small mb-3">Full lifetime access</p>

                        @auth
                            @if(auth()->user()->isStudent())
                                @if($isEnrolled)
                                    <a href="{{ route('student.course.show', $course) }}" class="btn btn-navy w-100 mb-2">
                                        <i class="bi bi-play-fill"></i> Go to course
                                    </a>
                                    <p class="text-success small mb-0"><i class="bi bi-check-circle"></i> You're enrolled</p>
                                @else
                                    <form method="POST" action="{{ route('student.enroll', $course) }}">
                                        @csrf
                                        <button class="btn w-100 mb-2" style="background:#f0a500;color:#0d2a5c;font-weight:600;">
                                            Enroll now
                                        </button>
                                    </form>
                                    <p class="text-muted small mb-0">Enroll to unlock all lessons</p>
                                @endif
                            @else
                                <p class="text-muted small mb-0">Log in as a student to enroll.</p>
                            @endif
                        @else
                            {{-- GUEST: Enroll button dikhe, dabate hi login pe jaayega, login ke baad wapas isi course pe --}}
                            <form method="POST" action="{{ route('student.enroll', $course) }}">
                                @csrf
                                <button class="btn w-100 mb-2" style="background:#f0a500;color:#0d2a5c;font-weight:600;">
                                    Enroll now
                                </button>
                            </form>
                            <p class="text-muted small mb-0">You'll be asked to log in first</p>
                        @endauth

                        <hr>
                        <div class="text-start small text-muted">
                            <div class="mb-1"><i class="bi bi-collection-play"></i> {{ $lessonCount }} lessons</div>
                            <div class="mb-1"><i class="bi bi-diagram-3"></i> {{ $course->modules->count() }} modules</div>
                            @if($course->trainer)
                                <div><i class="bi bi-person-circle"></i> By {{ $course->trainer->name }}</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
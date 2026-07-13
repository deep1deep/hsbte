@extends('layouts.app')

@section('title', $course->title . ' - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container" style="max-width:920px;">

        {{-- Header --}}
        <div class="mb-4">
            <a href="{{ route('student.dashboard') }}" class="text-muted text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Back to my learning
            </a>
            <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                <h2 class="mb-0">{{ $course->title }}</h2>
                @if($enrollment->status === 'completed')
                    <span class="badge text-bg-success">Completed</span>
                @endif
            </div>

            {{-- overall progress --}}
            @php($pct = round($enrollment->progressPercent()))
            <div class="progress mt-3" style="height:9px;max-width:520px;">
                <div class="progress-bar" role="progressbar"
                     style="width:{{ $pct }}%;background:#0d2a5c;"
                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="text-muted small mt-1">{{ $pct }}% complete</div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        @if($pct == 100)
            <div class="alert alert-success d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><i class="bi bi-trophy-fill"></i> Congratulations! You've completed this course. 🎉</span>
                @if($enrollment->certificate)
                    <a href="{{ route('certificate.download', $enrollment->certificate) }}"
                       class="btn btn-sm" style="background:#f0a500;color:#0d2a5c;font-weight:600;">
                        <i class="bi bi-download"></i> Download Certificate
                    </a>
                @endif
            </div>
        @endif

        {{-- MODULES + LESSONS --}}
        @forelse($course->modules as $module)
            <div class="admin-card mb-3">
                <div class="admin-card-head">{{ $module->title }}</div>
                <div class="admin-card-body">

                    @forelse($module->lessons as $lesson)
                        @php($isDone = in_array($lesson->id, $completedLessonIds))
                        <div class="py-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                            {{-- lesson title row --}}
                            <div class="d-flex align-items-center gap-2 mb-2">
                                @if($isDone)
                                    <i class="bi bi-check-circle-fill" style="color:#0f6e56;"></i>
                                @else
                                    <i class="bi {{ $lesson->isVideo() ? 'bi-play-circle' : 'bi-file-earmark-text' }}" style="color:#0d2a5c;"></i>
                                @endif
                                <span class="flex-grow-1" style="font-weight:500;color:#1f2f4d;">{{ $lesson->title }}</span>
                                @if($lesson->duration_minutes)
                                    <span class="text-muted small">{{ $lesson->duration_minutes }} min</span>
                                @endif
                            </div>

                            {{-- video / pdf --}}
                            @if($lesson->isVideo() && $lesson->video_path)
                                <video controls preload="metadata"
                                       src="{{ asset('storage/' . $lesson->video_path) }}"
                                       style="width:100%;max-width:640px;border-radius:8px;display:block;">
                                </video>
                            @elseif($lesson->isPdf() && $lesson->file_path)
                                <a href="{{ asset('storage/' . $lesson->file_path) }}" target="_blank"
                                   class="btn btn-sm btn-outline-navy">
                                    <i class="bi bi-file-earmark-text"></i> Open PDF
                                </a>
                            @else
                                <p class="text-muted small mb-0">Content not available.</p>
                            @endif

                            {{-- mark complete --}}
                            <div class="mt-2">
                                @if($isDone)
                                    <span class="text-success small"><i class="bi bi-check-circle"></i> Completed</span>
                                @else
                                    <form method="POST" action="{{ route('student.lessons.complete', $lesson) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i> Mark as complete
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @empty
                        <p class="text-muted small mb-0">No lessons in this module yet.</p>
                    @endforelse

                </div>
            </div>
        @empty
            <div class="admin-card">
                <div class="admin-card-body text-center text-muted py-4">
                    This course has no content yet. Check back soon.
                </div>
            </div>
        @endforelse

    </div>
</section>
@endsection
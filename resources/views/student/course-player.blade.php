@extends('layouts.app')

@section('title', $course->title . ' - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container" style="max-width:920px;">

        {{-- Header --}}
        <div class="mb-4" id="course-top">
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

        {{-- Resume / next-up banner --}}
        @if($pct < 100 && $nextLessonId)
            <a href="#lesson-{{ $nextLessonId }}" class="resume-banner">
                <div class="resume-icon"><i class="bi bi-play-fill"></i></div>
                <div class="flex-grow-1">
                    <div class="resume-kicker">{{ $pct > 0 ? 'Pick up where you left off' : 'Start learning' }}</div>
                    <div class="resume-text">Jump to your next lesson</div>
                </div>
                <i class="bi bi-arrow-down-circle resume-arrow"></i>
            </a>
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

            {{-- rate & review --}}
            <div class="admin-card review-card mb-4">
                <div class="admin-card-body">
                    <h3 class="h6 mb-1" style="color:#1f2f4d;">
                        <i class="bi bi-star-fill" style="color:#f0a500;"></i>
                        {{ $myReview ? 'Your feedback' : 'Rate this course' }}
                    </h3>
                    <p class="text-muted small mb-3">
                        {{ $myReview ? 'You can update your rating any time.' : 'Help other students by sharing how it was.' }}
                    </p>

                    <form method="POST" action="{{ route('student.course.review', $course) }}">
                        @csrf
                        @error('rating') <div class="alert alert-danger py-2 small">{{ $message }}</div> @enderror

                        <div class="star-rate mb-3">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                       @checked($myReview && $myReview->rating === $i) required>
                                <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            @endfor
                        </div>

                        <textarea name="comment" rows="2" class="form-control mb-2"
                                  placeholder="Add a short review (optional)…">{{ $myReview->comment ?? '' }}</textarea>

                        <button type="submit" class="btn btn-sm btn-navy">
                            <i class="bi bi-send"></i> {{ $myReview ? 'Update feedback' : 'Submit feedback' }}
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- MODULES + LESSONS --}}
        @forelse($course->modules as $module)
            <div class="admin-card mb-3">
                <div class="admin-card-head">{{ $module->title }}</div>
                <div class="admin-card-body">

                    @forelse($module->lessons as $lesson)
                        @php($isDone = in_array($lesson->id, $completedLessonIds))
                        @php($isNext = $lesson->id === $nextLessonId)
                        <div class="py-3 lesson-row {{ $isNext ? 'lesson-next' : '' }} {{ !$loop->last ? 'border-bottom' : '' }}"
                             id="lesson-{{ $lesson->id }}">

                            {{-- lesson title row --}}
                            <div class="d-flex align-items-center gap-2 mb-2">
                                @if($isDone)
                                    <i class="bi bi-check-circle-fill" style="color:#0f6e56;"></i>
                                @else
                                    <i class="bi {{ $lesson->isVideo() ? 'bi-play-circle' : 'bi-file-earmark-text' }}" style="color:#0d2a5c;"></i>
                                @endif
                                <span class="flex-grow-1" style="font-weight:500;color:#1f2f4d;">{{ $lesson->title }}</span>
                                @if($isNext)
                                    <span class="badge next-badge">Next up</span>
                                @endif
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

                            {{-- mark complete + notes toggle --}}
                            @php($noteBody = $notes[$lesson->id] ?? '')
                            <div class="mt-2 d-flex align-items-center gap-3 flex-wrap">
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

                                <button class="btn btn-sm btn-outline-navy" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#note-{{ $lesson->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                    {{ $noteBody !== '' ? 'My note' : 'Add note' }}
                                    @if($noteBody !== '')
                                        <span class="note-dot" title="You have a saved note"></span>
                                    @endif
                                </button>
                            </div>

                            {{-- personal note --}}
                            <div class="collapse {{ $noteBody !== '' ? 'show' : '' }} mt-2" id="note-{{ $lesson->id }}">
                                <form method="POST" action="{{ route('student.lessons.note', $lesson) }}" class="lesson-note">
                                    @csrf
                                    <textarea name="body" rows="3" class="form-control"
                                              placeholder="Write your own notes for this lesson… (only you can see these)">{{ $noteBody }}</textarea>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-navy" type="submit">
                                            <i class="bi bi-save"></i> Save note
                                        </button>
                                    </div>
                                </form>
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

@push('scripts')
<script>
    // Smooth-scroll to the lesson in the URL hash and give it a brief highlight.
    (function () {
        function focusLesson(hash) {
            if (!hash) return;
            var el = document.querySelector(hash);
            if (!el || !el.classList.contains('lesson-row')) return;
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.classList.add('lesson-flash');
            setTimeout(function () { el.classList.remove('lesson-flash'); }, 1600);
        }
        // on load (e.g. after "mark complete" redirect) and on in-page anchor clicks
        window.addEventListener('load', function () { focusLesson(window.location.hash); });
        document.querySelectorAll('a[href^="#lesson-"]').forEach(function (a) {
            a.addEventListener('click', function () {
                setTimeout(function () { focusLesson(a.getAttribute('href')); }, 10);
            });
        });
    })();
</script>
@endpush
@endsection

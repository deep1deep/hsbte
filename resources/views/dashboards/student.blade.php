@extends('layouts.app')

@section('title', 'My Learning - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">My Learning</h2>
                <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('student.profile') }}" class="btn btn-outline-navy">
                    <i class="bi bi-person-circle me-1"></i> Profile
                </a>
                <a href="{{ route('courses') }}" class="btn btn-outline-navy">
                    <i class="bi bi-search me-1"></i> Browse courses
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-navy">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-warning py-2">{{ session('error') }}</div>
        @endif

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-4">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $enrollments->count() }}</div><div class="stat-label">Enrolled Courses</div></div>
                    <div class="stat-ico stat-ico-navy"><i class="bi bi-journal-bookmark-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $enrollments->where('status','completed')->count() }}</div><div class="stat-label">Completed</div></div>
                    <div class="stat-ico stat-ico-teal"><i class="bi bi-check-circle-fill"></i></div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="admin-stat">
                    <div><div class="stat-num">{{ $enrollments->where('status','!=','completed')->count() }}</div><div class="stat-label">In Progress</div></div>
                    <div class="stat-ico stat-ico-gold"><i class="bi bi-hourglass-split"></i></div>
                </div>
            </div>
        </div>

        {{-- ===== LEARNING STREAK ===== --}}
        <div class="streak-strip mb-4 {{ $streak > 0 ? 'streak-on' : '' }}">
            <div class="streak-flame">
                <i class="bi {{ $streak > 0 ? 'bi-fire' : 'bi-emoji-smile' }}"></i>
            </div>
            <div class="flex-grow-1">
                @if($streak > 0)
                    <div class="streak-num">{{ $streak }}-day streak</div>
                    <div class="streak-sub">
                        @if($studiedToday)
                            Nice work — you've studied today. Keep it going tomorrow!
                        @else
                            You're on a roll. Complete a lesson today to keep your streak alive.
                        @endif
                    </div>
                @else
                    <div class="streak-num">Start your streak</div>
                    <div class="streak-sub">Complete a lesson today and build a daily learning habit. 🔥</div>
                @endif
            </div>
            @if($streak == 0 && $continue)
                <a href="{{ route('student.course.show', $continue->course) }}" class="btn btn-sm streak-btn">
                    <i class="bi bi-play-fill"></i> Learn now
                </a>
            @endif
        </div>

        {{-- ===== CONTINUE LEARNING ===== --}}
        @if($continue)
            @php $cpct = round($continue->progressPercent()); @endphp
            <div class="continue-card mb-4">
                <div class="continue-body">
                    <span class="continue-kicker">
                        <i class="bi bi-play-circle-fill"></i>
                        {{ $cpct > 0 ? 'Continue learning' : 'Start learning' }}
                    </span>
                    <h3 class="continue-title">{{ $continue->course->title }}</h3>
                    <div class="continue-meta">
                        @if($continue->course->department)
                            <span class="badge-dept">{{ $continue->course->department->code }}</span>
                        @endif
                        <span>{{ $continue->completed_lessons_count ?? 0 }} of
                            {{ $continue->course->lessons_count ?? 0 }} lessons done</span>
                    </div>
                    <div class="continue-progress">
                        <div class="continue-progress-bar" style="width:{{ $cpct }}%;"></div>
                    </div>
                    <div class="continue-pct">{{ $cpct }}% complete</div>
                </div>
                <div class="continue-action">
                    <a href="{{ route('student.course.show', $continue->course) }}" class="btn continue-btn">
                        @if($cpct > 0)
                            <i class="bi bi-play-fill"></i> Resume course
                        @else
                            <i class="bi bi-play-fill"></i> Start course
                        @endif
                    </a>
                </div>
            </div>
        @endif

        {{-- ===== MY CERTIFICATES ===== --}}
        @php
            $certified = $enrollments->filter(fn($e) => $e->certificate);
            $pendingCerts = $certified->filter(fn($e) => $e->certificate->isPending());
        @endphp

        @if($certified->count())
            <h5 class="mb-3" style="color:#1f2f4d;"><i class="bi bi-award-fill" style="color:#f0a500;"></i> My Certificates</h5>

            @if($pendingCerts->count())
                <div class="alert alert-info py-2">
                    <i class="bi bi-info-circle me-1"></i>
                    {{ $pendingCerts->count() == 1 ? 'Ek certificate' : $pendingCerts->count() . ' certificates' }}
                    trainer ke paas approval ke liye hai. Issue hote hi download link yahin aa jaayega.
                </div>
            @endif

            <div class="row g-3 mb-4">
                @foreach($certified as $enrollment)
                    @php($cert = $enrollment->certificate)
                    <div class="col-md-6 col-lg-4">
                        <div class="admin-card h-100"
                             style="border-top:3px solid {{ $cert->isPending() ? '#a5b0c6' : '#f0a500' }};">
                            <div class="admin-card-body d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if($cert->isPending())
                                        <i class="bi bi-clock-history" style="color:#7a8aa8;font-size:20px;"></i>
                                    @else
                                        <i class="bi bi-patch-check-fill" style="color:#0f6e56;font-size:20px;"></i>
                                    @endif
                                    <span style="font-weight:600;color:#1f2f4d;">{{ $enrollment->course->title }}</span>
                                </div>

                                <div class="text-muted small mb-1">
                                    No: {{ $cert->certificate_no }}
                                </div>

                                @if($cert->isPending())
                                    <div class="text-muted small mb-3">
                                        Course completed —
                                        {{ $enrollment->completed_at ? $enrollment->completed_at->format('d M Y') : '—' }}
                                    </div>
                                    <button class="btn btn-sm mt-auto" disabled
                                            style="background:#eef2fa;color:#7a8aa8;font-weight:600;border:1px solid #dde4f0;">
                                        <i class="bi bi-hourglass-split"></i> Pending with trainer
                                    </button>
                                @else
                                    <div class="text-muted small mb-3">
                                        Issued: {{ $cert->issued_at ? $cert->issued_at->format('d M Y') : '—' }}
                                    </div>
                                    <a href="{{ route('certificate.download', $cert) }}"
                                       class="btn btn-sm mt-auto" style="background:#f0a500;color:#0d2a5c;font-weight:600;">
                                        <i class="bi bi-download"></i> Download Certificate
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- MY COURSES --}}
        <h5 class="mb-3" style="color:#1f2f4d;">My Courses</h5>

        @foreach($enrollments as $enrollment)
            @php($pct = round($enrollment->progressPercent()))
            <div class="admin-card mb-3">
                <div class="admin-card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div class="flex-grow-1" style="min-width:220px;">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span style="font-weight:600;color:#1f2f4d;">{{ $enrollment->course->title }}</span>
                                @if($enrollment->course->department)
                                    <span class="badge-dept">{{ $enrollment->course->department->code }}</span>
                                @endif
                                @if($enrollment->status === 'completed')
                                    <span class="badge text-bg-success">Completed</span>
                                    @if($enrollment->certificate)
                                        @if($enrollment->certificate->isPending())
                                            <span class="small" style="color:#7a8aa8;font-weight:600;">
                                                <i class="bi bi-clock-history"></i> Certificate pending
                                            </span>
                                        @else
                                            <a href="{{ route('certificate.download', $enrollment->certificate) }}"
                                               class="small text-decoration-none" style="color:#f0a500;font-weight:600;">
                                                <i class="bi bi-award"></i> Certificate
                                            </a>
                                        @endif
                                    @endif
                                @endif
                            </div>

                            {{-- progress bar --}}
                            <div class="progress mt-2" style="height:8px;max-width:420px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width:{{ $pct }}%;background:#0d2a5c;"
                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="text-muted small mt-1">{{ $pct }}% complete</div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('student.course.show', $enrollment->course) }}" class="btn btn-navy btn-sm">
                                @if($enrollment->status === 'completed')
                                    <i class="bi bi-arrow-repeat"></i> Review
                                @elseif($pct > 0)
                                    <i class="bi bi-play-fill"></i> Continue
                                @else
                                    <i class="bi bi-play-fill"></i> Start
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($enrollments->isEmpty())
            <div class="admin-card">
                <div class="admin-card-body text-center text-muted py-5">
                    <i class="bi bi-journal-x" style="font-size:32px;color:#a5b0c6;"></i>
                    <p class="mt-2 mb-3">You haven't enrolled in any course yet.</p>
                    <a href="{{ route('courses') }}" class="btn btn-navy">Browse courses</a>
                </div>
            </div>
        @endif

        {{-- ===== RECOMMENDED COURSES ===== --}}
        @if($recommended->isNotEmpty())
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mt-5 mb-3">
                <div>
                    <h5 class="mb-1" style="color:#1f2f4d;">
                        <i class="bi bi-stars" style="color:#f0a500;"></i> Courses you may like
                    </h5>
                    <p class="text-muted small mb-0">Published programmes you haven't enrolled in yet.</p>
                </div>
                <a href="{{ route('courses') }}" class="btn btn-sm btn-outline-navy">View all courses</a>
            </div>

            <div class="row g-3">
                @foreach($recommended as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="reco-card">
                            <div class="reco-top">
                                <div class="reco-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                                @if($course->department)
                                    <span class="badge-dept">{{ $course->department->code }}</span>
                                @endif
                            </div>
                            <h3 class="reco-title">{{ $course->title }}</h3>
                            <div class="mb-2">
                                @include('partials.stars', ['avg' => $course->averageRating(), 'count' => $course->reviewsCount()])
                            </div>
                            <div class="reco-meta">
                                <span><i class="bi bi-people"></i> {{ $course->enrollments_count }} enrolled</span>
                                @if($course->duration_weeks)
                                    <span><i class="bi bi-clock"></i> {{ $course->duration_weeks }} wk</span>
                                @endif
                            </div>
                            <a href="{{ route('course.detail', $course) }}" class="btn btn-sm reco-btn mt-auto">
                                View course <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@endsection
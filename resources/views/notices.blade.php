@extends('layouts.app')

@section('title', 'Notices & Circulars - HSBTE')

@section('content')

{{-- NAVY HERO --}}
<section style="background:#0d2a5c;color:#fff;padding:56px 0;">
    <div class="container text-center">
        <h1 style="font-weight:700;">Notices &amp; Circulars</h1>
        <p style="color:#cdd6e8;margin-bottom:0;">Latest announcements and updates from HSBTE</p>
    </div>
</section>

{{-- NOTICES LIST --}}
<section class="section-pad">
    <div class="container" style="max-width:820px;">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <span class="text-muted">
                {{ $announcements->count() }} {{ $announcements->count() === 1 ? 'notice' : 'notices' }}
            </span>
            <a href="{{ route('home') }}" class="btn btn-outline-navy btn-sm">
                <i class="bi bi-house"></i> Home
            </a>
        </div>

        @forelse($announcements as $a)
            <div class="admin-card mb-3">
                <div class="admin-card-body">
                    <div class="d-flex align-items-start gap-2 mb-1 flex-wrap">
                        <i class="bi bi-megaphone-fill" style="color:#f0a500;font-size:18px;"></i>
                        <h5 class="mb-0 flex-grow-1" style="color:#1f2f4d;">{{ $a->title }}</h5>
                        @if($a->published_at && $a->published_at->gt(now()->subDays(7)))
                            <span class="badge-new">NEW</span>
                        @endif
                    </div>
                    @if($a->body)
                        <p class="text-muted mb-2" style="white-space:pre-line;">{{ $a->body }}</p>
                    @endif
                    @if($a->published_at)
                        <div class="text-muted small">
                            <i class="bi bi-calendar3"></i> {{ $a->published_at->format('d M Y') }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="admin-card">
                <div class="admin-card-body text-center text-muted py-5">
                    <i class="bi bi-megaphone" style="font-size:32px;color:#a5b0c6;"></i>
                    <p class="mt-2 mb-0">No notices yet. Check back soon.</p>
                </div>
            </div>
        @endforelse

    </div>
</section>
@endsection
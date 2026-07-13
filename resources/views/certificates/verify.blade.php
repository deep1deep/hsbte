@extends('layouts.app')

@section('title', 'Verify Certificate - HSBTE')

@section('content')

{{-- ===== NAVY BANNER ===== --}}
<section style="background:#0d2a5c;color:#fff;padding:44px 0;">
    <div class="container text-center">
        <h1 style="font-weight:700;"><i class="bi bi-patch-check"></i> Verify Certificate</h1>
        <p style="color:#cdd6e8;margin-bottom:0;">Enter a certificate number to check if it's genuine</p>
    </div>
</section>

<section class="section-pad">
    <div class="container" style="max-width:640px;">

        {{-- Search box --}}
        <form method="GET" action="{{ route('certificate.verify') }}" class="mb-4">
            <div class="d-flex gap-2 flex-wrap">
                <input type="text" name="number" value="{{ $number }}"
                       class="form-control form-control-lg" style="flex:1;min-width:220px;"
                       placeholder="e.g. HSBTE-2026-000001" required>
                <button class="btn btn-navy btn-lg">
                    <i class="bi bi-search"></i> Verify
                </button>
            </div>
        </form>

        {{-- Result --}}
        @if($searched)
            @if($certificate)
                <div class="admin-card" style="border-top:4px solid #0f6e56;">
                    <div class="admin-card-body">
                        <div class="text-center mb-3">
                            <i class="bi bi-patch-check-fill" style="font-size:44px;color:#0f6e56;"></i>
                            <h4 class="mt-2 mb-0" style="color:#0f6e56;">Certificate Verified</h4>
                            <p class="text-muted small">This is a genuine HSBTE certificate.</p>
                        </div>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width:40%;">Certificate No.</td>
                                <td style="font-weight:600;color:#1f2f4d;">{{ $certificate->certificate_no }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Student Name</td>
                                <td style="font-weight:600;color:#1f2f4d;">{{ $certificate->enrollment->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Course</td>
                                <td style="font-weight:600;color:#1f2f4d;">{{ $certificate->enrollment->course->title }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Issued On</td>
                                <td style="font-weight:600;color:#1f2f4d;">{{ $certificate->issued_at->format('d F Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @else
                <div class="admin-card" style="border-top:4px solid #a32d2d;">
                    <div class="admin-card-body text-center py-4">
                        <i class="bi bi-x-circle-fill" style="font-size:44px;color:#a32d2d;"></i>
                        <h4 class="mt-2 mb-0" style="color:#a32d2d;">Not Found</h4>
                        <p class="text-muted mb-0">
                            No certificate matches <strong>{{ $number }}</strong>.
                            Please check the number and try again.
                        </p>
                    </div>
                </div>
            @endif
        @endif

    </div>
</section>
@endsection
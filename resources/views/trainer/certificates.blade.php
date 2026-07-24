@extends('layouts.app')

@section('title', 'Certificates - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Certificates</h2>
                <p class="text-muted mb-0">Upload certificates for students who have completed your courses</p>
            </div>
            <a href="{{ route('trainer.dashboard') }}" class="btn btn-outline-navy">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger py-2">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        {{-- ================= PENDING ================= --}}
        <div class="admin-card mb-4">
            <div class="admin-card-head d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-1"></i> Pending — Upload Required</span>
                <span class="badge text-bg-danger">{{ $pending->count() }}</span>
            </div>
            <div class="admin-card-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Certificate No.</th>
                            <th>Completed</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending as $cert)
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:#1f2f4d;">{{ $cert->enrollment->user->name }}</div>
                                    <div class="text-muted" style="font-size:.85rem;">
                                        {{ $cert->enrollment->user->email }}
                                    </div>
                                </td>
                                <td>{{ $cert->enrollment->course->title }}</td>
                                <td><code>{{ $cert->certificate_no }}</code></td>
                                <td>
                                    {{ $cert->enrollment->completed_at
                                        ? $cert->enrollment->completed_at->format('d M Y')
                                        : '—' }}
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-navy" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#up{{ $cert->id }}">
                                        <i class="bi bi-upload me-1"></i> Upload
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="up{{ $cert->id }}">
                                <td colspan="5" style="background:#f7f9fc;">
                                    <form method="POST"
                                          action="{{ route('trainer.certificates.upload', $cert) }}"
                                          enctype="multipart/form-data"
                                          class="d-flex align-items-end gap-2 flex-wrap py-2">
                                        @csrf
                                        <div style="flex:1;min-width:260px;">
                                            <label class="form-label mb-1" style="font-size:.85rem;font-weight:600;">
                                                Certificate file — {{ $cert->enrollment->user->name }}
                                            </label>
                                            <input type="file" name="file" class="form-control form-control-sm"
                                                   accept=".pdf,.jpg,.jpeg,.png" required>
                                            <div class="text-muted mt-1" style="font-size:.78rem;">
                                                PDF / JPG / PNG · max 5MB
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-navy">Issue Certificate</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle me-1"></i> All clear — no certificates pending.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= ISSUED ================= --}}
        <div class="admin-card">
            <div class="admin-card-head d-flex justify-content-between align-items-center">
                <span><i class="bi bi-check-circle me-1"></i> Issued</span>
                <span class="badge text-bg-success">{{ $issued->count() }}</span>
            </div>
            <div class="admin-card-body">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Certificate No.</th>
                            <th>Issued</th>
                            <th>Type</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issued as $cert)
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:#1f2f4d;">{{ $cert->enrollment->user->name }}</div>
                                </td>
                                <td>{{ $cert->enrollment->course->title }}</td>
                                <td><code>{{ $cert->certificate_no }}</code></td>
                                <td>{{ $cert->issued_at ? $cert->issued_at->format('d M Y') : '—' }}</td>
                                <td>
                                    @if($cert->isManual())
                                        <span class="badge text-bg-secondary">Uploaded</span>
                                    @else
                                        <span class="badge text-bg-info">Auto</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($cert->isManual())
                                        <button class="btn btn-sm btn-outline-navy" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#re{{ $cert->id }}">
                                            Replace
                                        </button>
                                    @else
                                        <span class="text-muted" style="font-size:.85rem;">—</span>
                                    @endif
                                </td>
                            </tr>
                            @if($cert->isManual())
                                <tr class="collapse" id="re{{ $cert->id }}">
                                    <td colspan="6" style="background:#f7f9fc;">
                                        <form method="POST"
                                              action="{{ route('trainer.certificates.upload', $cert) }}"
                                              enctype="multipart/form-data"
                                              class="d-flex align-items-end gap-2 flex-wrap py-2">
                                            @csrf
                                            <div style="flex:1;min-width:260px;">
                                                <label class="form-label mb-1" style="font-size:.85rem;font-weight:600;">
                                                    New file — this will replace the current certificate
                                                </label>
                                                <input type="file" name="file" class="form-control form-control-sm"
                                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                            </div>
                                            <button class="btn btn-sm btn-navy">Replace</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No certificates issued yet.
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
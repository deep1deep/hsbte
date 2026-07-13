@extends('layouts.app')

@section('title', 'Manage Notices - Admin - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container" style="max-width:900px;">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left"></i> Back to dashboard
                </a>
                <h2 class="mt-2 mb-0">Manage Notices</h2>
                <p class="text-muted mb-0">Add, edit or remove announcements shown on the site.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        {{-- ADD NEW NOTICE --}}
        <div class="admin-card mb-4">
            <div class="admin-card-head"><i class="bi bi-plus-lg"></i> Add a new notice</div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.announcements.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="form-control" placeholder="e.g. Semester exam form last date extended" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Details (optional)</label>
                        <textarea name="body" class="form-control" rows="3"
                                  placeholder="Full text of the notice...">{{ old('body') }}</textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="addActive" checked>
                        <label class="form-check-label" for="addActive">
                            Active (visible on site)
                        </label>
                    </div>
                    <button class="btn btn-navy">Add Notice</button>
                </form>
            </div>
        </div>

        {{-- EXISTING NOTICES --}}
        <h5 class="mb-3" style="color:#1f2f4d;">All notices ({{ $announcements->count() }})</h5>

        @forelse($announcements as $a)
            <div class="admin-card mb-3">
                <div class="admin-card-body">

                    <div class="d-flex align-items-start gap-2 flex-wrap">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <span style="font-weight:600;color:#1f2f4d;">{{ $a->title }}</span>
                                @if($a->is_active)
                                    <span class="badge text-bg-success">Active</span>
                                @else
                                    <span class="badge text-bg-secondary">Hidden</span>
                                @endif
                            </div>
                            @if($a->body)
                                <p class="text-muted small mb-1" style="white-space:pre-line;">{{ $a->body }}</p>
                            @endif
                            @if($a->published_at)
                                <div class="text-muted small"><i class="bi bi-calendar3"></i> {{ $a->published_at->format('d M Y') }}</div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            {{-- Edit toggle --}}
                            <button class="btn btn-sm btn-outline-navy" title="Edit"
                                    data-bs-toggle="collapse" data-bs-target="#edit-{{ $a->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}"
                                  onsubmit="return confirm('Delete this notice? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit form (collapse) --}}
                    <div class="collapse mt-3" id="edit-{{ $a->id }}">
                        <form method="POST" action="{{ route('admin.announcements.update', $a) }}"
                              class="p-3 rounded" style="background:#f7f9fc;">
                            @csrf
                            @method('PATCH')
                            <div class="mb-2">
                                <label class="form-label small">Title</label>
                                <input type="text" name="title" value="{{ $a->title }}" class="form-control form-control-sm" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Details</label>
                                <textarea name="body" class="form-control form-control-sm" rows="3">{{ $a->body }}</textarea>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                       id="active-{{ $a->id }}" @checked($a->is_active)>
                                <label class="form-check-label small" for="active-{{ $a->id }}">Active (visible on site)</label>
                            </div>
                            <button class="btn btn-sm btn-navy">Save changes</button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="admin-card">
                <div class="admin-card-body text-center text-muted py-4">
                    No notices yet. Add your first one above.
                </div>
            </div>
        @endforelse

    </div>
</section>
@endsection
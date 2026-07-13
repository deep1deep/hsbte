@extends('layouts.app')

@section('title', $course->title . ' - Manage - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container" style="max-width:860px;">

        {{-- Header --}}
        <div class="mb-4">
            <a href="{{ route('trainer.dashboard') }}" class="text-muted text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Back to dashboard
            </a>
            <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                <h2 class="mb-0">{{ $course->title }}</h2>
                @if($course->status === 'published')
                    <span class="badge text-bg-success">Published</span>
                @elseif($course->status === 'draft')
                    <span class="badge text-bg-secondary">Draft</span>
                @else
                    <span class="badge text-bg-dark">Archived</span>
                @endif

                {{-- Edit + Publish buttons --}}
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('trainer.courses.edit', $course) }}" class="btn btn-sm btn-outline-navy">
                        <i class="bi bi-pencil"></i> Edit details
                    </a>
                    <form method="POST" action="{{ route('trainer.courses.publish', $course) }}">
                        @csrf
                        @method('PATCH')
                        @if($course->status === 'published')
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye-slash"></i> Unpublish
                            </button>
                        @else
                            <button class="btn btn-sm btn-success">
                                <i class="bi bi-broadcast"></i> Publish
                            </button>
                        @endif
                    </form>
                </div>
            </div>
            <p class="text-muted mb-0 mt-1">Manage modules and lessons for this course.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <h5 class="mb-3" style="color:#1f2f4d;">Course content</h5>

        {{-- MODULES --}}
        @forelse($course->modules as $module)
            <div class="admin-card mb-3">
                <div class="admin-card-head d-flex justify-content-between align-items-center">
                    <span>{{ $module->title }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">
                            {{ $module->lessons->count() }} {{ $module->lessons->count() === 1 ? 'lesson' : 'lessons' }}
                        </span>
                        {{-- Edit module --}}
                        <button class="btn btn-sm btn-link text-secondary p-0" title="Edit module"
                                data-bs-toggle="collapse" data-bs-target="#edit-module-{{ $module->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        {{-- Delete module --}}
                        <form method="POST" action="{{ route('trainer.modules.destroy', $module) }}"
                              onsubmit="return confirm('Delete this whole module and all its lessons? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-link text-danger p-0" title="Delete module">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="admin-card-body">

                    {{-- Edit module form (collapse) --}}
                    <div class="collapse mb-3" id="edit-module-{{ $module->id }}">
                        <form method="POST" action="{{ route('trainer.modules.update', $module) }}"
                              class="d-flex gap-2 flex-wrap pb-3 border-bottom">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="title" value="{{ $module->title }}"
                                   class="form-control form-control-sm" style="flex:1;min-width:180px;" required>
                            <button class="btn btn-sm btn-navy">Save</button>
                        </form>
                    </div>

                    {{-- lessons list --}}
                    @forelse($module->lessons as $lesson)
                        <div class="py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $lesson->isVideo() ? 'bi-play-circle' : 'bi-file-earmark-text' }}" style="color:#0d2a5c;"></i>
                                <span class="flex-grow-1" style="color:#3d4f73;">{{ $lesson->title }}</span>
                                @if($lesson->duration_minutes)
                                    <span class="text-muted small">{{ $lesson->duration_minutes }} min</span>
                                @endif
                                {{-- Edit lesson --}}
                                <button class="btn btn-sm btn-link text-secondary p-0 ms-1" title="Edit lesson"
                                        data-bs-toggle="collapse" data-bs-target="#edit-lesson-{{ $lesson->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                {{-- Delete lesson --}}
                                <form method="POST" action="{{ route('trainer.lessons.destroy', $lesson) }}"
                                      onsubmit="return confirm('Delete this lesson? File will be removed too.');" class="ms-1">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-link text-danger p-0" title="Delete lesson">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- Edit lesson form (collapse) --}}
                            <div class="collapse mt-2" id="edit-lesson-{{ $lesson->id }}">
                                <form method="POST" action="{{ route('trainer.lessons.update', $lesson) }}"
                                      enctype="multipart/form-data" class="p-2 rounded" style="background:#f7f9fc;">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row g-2">
                                        <div class="col-12 col-md-6">
                                            <input type="text" name="title" value="{{ $lesson->title }}"
                                                   class="form-control form-control-sm" placeholder="Lesson title" required>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <input type="number" name="duration_minutes" value="{{ $lesson->duration_minutes }}"
                                                   class="form-control form-control-sm" placeholder="Min" min="0">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <button class="btn btn-sm btn-navy w-100">Save</button>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small text-muted mb-1">
                                                Replace {{ $lesson->isVideo() ? 'video' : 'PDF' }} (optional — leave empty to keep current)
                                            </label>
                                            <input type="file" name="file" class="form-control form-control-sm"
                                                   accept="{{ $lesson->isVideo() ? 'video/*' : 'application/pdf' }}">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- player / open --}}
                            @if($lesson->isVideo() && $lesson->video_path)
                                <video controls preload="metadata"
                                       src="{{ asset('storage/' . $lesson->video_path) }}"
                                       style="width:100%;max-width:520px;border-radius:8px;margin-top:8px;display:block;">
                                </video>
                            @elseif($lesson->isPdf() && $lesson->file_path)
                                <a href="{{ asset('storage/' . $lesson->file_path) }}" target="_blank"
                                   class="btn btn-sm btn-outline-navy mt-2">
                                    <i class="bi bi-file-earmark-text"></i> Open PDF
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted small text-center py-2 mb-0">No lessons yet. Add the first one below.</p>
                    @endforelse

                    {{-- add lesson form (VIDEO/PDF UPLOAD) --}}
                    <form method="POST" action="{{ route('trainer.lessons.store', $module) }}"
                          enctype="multipart/form-data" class="mt-3 pt-3 border-top">
                        @csrf
                        <div class="row g-2">
                            <div class="col-12 col-md-5">
                                <input type="text" name="title" class="form-control form-control-sm"
                                       placeholder="Lesson title" required>
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="type" class="form-select form-select-sm" required>
                                    <option value="video">Video</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <input type="number" name="duration_minutes" class="form-control form-control-sm"
                                       placeholder="Min" min="0">
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="file" name="file" class="form-control form-control-sm"
                                       accept="video/*,application/pdf" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <button class="btn btn-navy btn-sm w-100">Add lesson</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        @empty
            <div class="admin-card mb-3">
                <div class="admin-card-body text-center text-muted py-4">
                    No modules yet. Add your first module below.
                </div>
            </div>
        @endforelse

        {{-- ADD MODULE --}}
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="mb-2" style="font-weight:600;color:#1f2f4d;">
                    <i class="bi bi-plus-lg"></i> Add a new module
                </div>
                <form method="POST" action="{{ route('trainer.modules.store', $course) }}">
                    @csrf
                    <div class="d-flex gap-2 flex-wrap">
                        <input type="text" name="title" class="form-control"
                               placeholder="e.g. Week 3 · Advanced Topics" style="flex:1;min-width:200px;" required>
                        <button class="btn btn-navy">Add module</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>
@endsection
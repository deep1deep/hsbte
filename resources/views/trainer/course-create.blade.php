@extends('layouts.app')

@section('title', 'Create Course - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container" style="max-width:720px;">

        <div class="mb-4">
            <a href="{{ route('trainer.dashboard') }}" class="text-muted text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Back to dashboard
            </a>
            <h2 class="mt-2 mb-1">Create New Course</h2>
            <p class="text-muted mb-0">Fill in the course details. You can add modules and lessons after.</p>
        </div>

        <div class="admin-card">
            <div class="admin-card-body">

                @if($errors->any())
                    <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('trainer.courses.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Course title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="form-control" placeholder="e.g. Web Development Basics" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                                  placeholder="What will students learn in this course?">{{ old('description') }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">Select department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Duration (weeks)</label>
                            <input type="number" name="duration_weeks" value="{{ old('duration_weeks') }}"
                                   class="form-control" placeholder="e.g. 8" min="1" max="104">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" @selected(old('status')=='draft')>Draft (not visible to students yet)</option>
                            <option value="published" @selected(old('status')=='published')>Published (live for students)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Certificate mode</label>
                        <select name="cert_mode" id="certMode" class="form-select" required onchange="toggleDesignWarn()">
                            <option value="auto" @selected(old('cert_mode','auto')=='auto')>Auto — generated from my certificate design</option>
                            <option value="manual" @selected(old('cert_mode')=='manual')>Manual — I will upload each student's certificate</option>
                        </select>
                        <div class="text-muted mt-1" style="font-size:.8rem;">
                            You can change this later. It only affects certificates issued from now on.
                        </div>

                        @if(! $hasDesign)
                            <div class="alert alert-warning py-2 mt-2 mb-0" id="designWarn" style="font-size:.85rem;">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                You haven't saved a certificate design yet — students will get the default HSBTE certificate.
                                <a href="{{ route('trainer.certificate.design') }}" class="alert-link">Set up design</a>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-navy w-100 mt-2">Create Course</button>
                </form>

            </div>
        </div>

    </div>
</section>

@if(! $hasDesign)
<script>
function toggleDesignWarn() {
    const warn = document.getElementById('designWarn');
    if (warn) warn.style.display = document.getElementById('certMode').value === 'auto' ? '' : 'none';
}
toggleDesignWarn();
</script>
@endif
@endsection
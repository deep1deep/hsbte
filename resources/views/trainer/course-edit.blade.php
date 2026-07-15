@extends('layouts.app')

@section('title', 'Edit Course - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container" style="max-width:720px;">

        <div class="mb-4">
            <a href="{{ route('trainer.courses.manage', $course) }}" class="text-muted text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Back to course
            </a>
            <h2 class="mt-2 mb-1">Edit Course</h2>
            <p class="text-muted mb-0">Update the course details. The course link (URL) stays the same.</p>
        </div>

        <div class="admin-card">
            <div class="admin-card-body">

                @if($errors->any())
                    <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('trainer.courses.update', $course) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Course title</label>
                        <input type="text" name="title" value="{{ old('title', $course->title) }}"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">Select department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" @selected(old('department_id', $course->department_id) == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Duration (weeks)</label>
                            <input type="number" name="duration_weeks" value="{{ old('duration_weeks', $course->duration_weeks) }}"
                                   class="form-control" min="1" max="104">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" @selected(old('status', $course->status)=='draft')>Draft (not visible to students)</option>
                            <option value="published" @selected(old('status', $course->status)=='published')>Published (live for students)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Certificate mode</label>
                        <select name="cert_mode" id="certMode" class="form-select" required onchange="toggleDesignWarn()">
                            <option value="auto" @selected(old('cert_mode', $course->cert_mode)=='auto')>Auto — generated from my certificate design</option>
                            <option value="manual" @selected(old('cert_mode', $course->cert_mode)=='manual')>Manual — I will upload each student's certificate</option>
                        </select>
                        <div class="text-muted mt-1" style="font-size:.8rem;">
                            Only affects certificates issued from now on — already issued certificates never change.
                        </div>

                        @if(! $hasDesign)
                            <div class="alert alert-warning py-2 mt-2 mb-0" id="designWarn" style="font-size:.85rem;">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                You haven't saved a certificate design yet — students will get the default HSBTE certificate.
                                <a href="{{ route('trainer.certificate.design') }}" class="alert-link">Set up design</a>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-navy w-100 mt-2">Save changes</button>
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
@extends('layouts.app')

@section('title', 'Courses - Admin - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <div class="text-muted small mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                    <i class="bi bi-chevron-right" style="font-size:.7rem;"></i> Courses
                </div>
                <h2 class="mb-1">Course Oversight</h2>
                <p class="text-muted mb-0">{{ $counts['all'] }} course{{ $counts['all'] == 1 ? '' : 's' }} across all trainers</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        {{-- Status filter pills --}}
        <div class="d-flex gap-2 flex-wrap mb-3">
            @php($pills = ['' => 'All ('.$counts['all'].')', 'published' => 'Published ('.$counts['published'].')', 'draft' => 'Draft ('.$counts['draft'].')', 'archived' => 'Archived ('.$counts['archived'].')'])
            @foreach($pills as $key => $label)
                <a href="{{ route('admin.courses', $key ? ['status' => $key] : []) }}"
                   class="btn btn-sm {{ (string)$status === (string)$key ? 'btn-navy' : 'btn-outline-navy' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="admin-table align-middle">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Trainer</th>
                                <th>Dept</th>
                                <th class="text-center">Modules</th>
                                <th class="text-center">Enrolled</th>
                                <th>Status</th>
                                <th class="text-end">Change status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $course)
                                <tr>
                                    <td style="font-weight:600;color:#1f2f4d;">{{ $course->title }}</td>
                                    <td class="text-muted small">{{ $course->trainer->name ?? '—' }}</td>
                                    <td>
                                        @if($course->department)
                                            <span class="badge-dept">{{ $course->department->code }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $course->modules_count }}</td>
                                    <td class="text-center">{{ $course->enrollments_count }}</td>
                                    <td>
                                        @if($course->status === 'published')
                                            <span class="badge text-bg-success">Published</span>
                                        @elseif($course->status === 'draft')
                                            <span class="badge text-bg-secondary">Draft</span>
                                        @else
                                            <span class="badge text-bg-dark">Archived</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('admin.courses.status', $course) }}"
                                              class="d-inline-flex gap-1 justify-content-end">
                                            @csrf @method('PATCH')
                                            <select name="status" class="form-select form-select-sm" style="width:auto;">
                                                <option value="draft"     @selected($course->status === 'draft')>Draft</option>
                                                <option value="published" @selected($course->status === 'published')>Published</option>
                                                <option value="archived"  @selected($course->status === 'archived')>Archived</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-navy">Apply</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-book" style="font-size:26px;color:#a5b0c6;"></i>
                                        <p class="mt-2 mb-0">No courses in this view.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($courses->hasPages())
                    <div class="mt-3">{{ $courses->links() }}</div>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection

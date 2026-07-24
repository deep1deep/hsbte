@extends('layouts.app')

@section('title', 'Students - Admin - HSBTE')

@section('content')
<section class="section-pad">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <div class="text-muted small mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                    <i class="bi bi-chevron-right" style="font-size:.7rem;"></i> Students
                </div>
                <h2 class="mb-1">Students</h2>
                <p class="text-muted mb-0">{{ $students->total() }} registered student{{ $students->total() == 1 ? '' : 's' }}</p>
            </div>
            <a href="{{ route('admin.students.export', request()->query()) }}" class="btn btn-navy btn-sm">
                <i class="bi bi-download me-1"></i> Export CSV
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        {{-- Filters --}}
        <div class="admin-card">
            <div class="admin-card-body">
                <form method="GET" action="{{ route('admin.students') }}" class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label small">Search</label>
                        <input type="text" name="q" value="{{ $search }}" class="form-control"
                               placeholder="Name or email">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Department</label>
                        <select name="department" class="form-select">
                            <option value="">All departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" @selected($deptId == $dept->id)>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-navy w-100"><i class="bi bi-search"></i></button>
                        @if($search || $deptId)
                            <a href="{{ route('admin.students') }}" class="btn btn-outline-navy" title="Clear filters">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="admin-table align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Dept</th>
                                <th>Sem</th>
                                <th class="text-center">Courses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $s)
                                <tr>
                                    <td style="font-weight:600;color:#1f2f4d;">{{ $s->name }}</td>
                                    <td class="text-muted small">{{ $s->email }}</td>
                                    <td>
                                        @if($s->department)
                                            <span class="badge-dept">{{ $s->department->code }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $s->semester ?? '—' }}</td>
                                    <td class="text-center">{{ $s->enrollments_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-search" style="font-size:26px;color:#a5b0c6;"></i>
                                        <p class="mt-2 mb-0">No students match your filters.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($students->hasPages())
                    <div class="mt-3">{{ $students->links() }}</div>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection

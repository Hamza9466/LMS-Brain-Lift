@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">Sections</h4>
        <a href="{{ route('courses.index') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> All Courses
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                    <tr>
                        <th class="px-3 py-3">Course</th>
                        <th class="px-3 py-3">Title</th>
                        <th class="px-3 py-3 text-center" width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr>
                            <td>{{ $section->course->title ?? 'N/A' }}</td>
                            <td>{{ $section->title }}</td>
                            <td class="text-center">
                                @php
                                    $owns = optional($section->course)->teacher_id === auth()->id();
                                    $isAdmin = auth()->user()->role === 'admin';
                                @endphp

                                {{-- Edit: admin OR owning teacher --}}
                                @if($isAdmin || $owns)
                                    <a href="{{ route('sections.edit', $section->id) }}" class="text-primary me-2" title="Edit">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </a>
                                @endif

                                {{-- Delete: admin ONLY --}}
                                @if($isAdmin)
                                    <form action="{{ route('sections.destroy', $section->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Delete"
                                                onclick="return confirm('Delete this section?')">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No sections found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sections instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="d-flex justify-content-center mt-3">
                {{ $sections->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

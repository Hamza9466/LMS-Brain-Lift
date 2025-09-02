@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">
            <i class="fas fa-bullhorn me-2"></i> Announcements
        </h4>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 rounded-3">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0 rounded-3">
            <i class="fas fa-exclamation-circle me-2"></i> Please fix the following:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Create form --}}
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header fw-semibold bg-light">
            <i class="fas fa-plus-circle me-2 text-primary"></i> Create Announcement
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-heading me-1"></i> Title
                        </label>
                        <input name="title" class="form-control" required maxlength="255" value="{{ old('title') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-users me-1"></i> Audience
                        </label>
                        <select name="audience" class="form-select" required>
                            <option value="all" {{ old('audience')=='all'?'selected':'' }}>All (students)</option>
                            
                            <option value="course_students" {{ old('audience')=='course_students'?'selected':'' }}>Enrolled students of a course</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-book me-1"></i> Course (only for "course_students")
                        </label>
                        <select name="course_id" class="form-select">
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}" {{ (string)old('course_id')===(string)$c->id?'selected':'' }}>{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-comment-dots me-1"></i> Message
                        </label>
                        <textarea name="body" class="form-control" rows="4" required>{{ old('body') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_published" value="1" checked>
                            <span class="form-check-label">Published</span>
                        </label>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Publish
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Announcements List --}}
    @forelse($announcements as $a)
        <div class="card shadow-sm border-0 rounded-3 mb-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">
                        <i class="fas fa-bullhorn text-primary me-1"></i> {{ $a->title }}
                    </div>
                    <div class="small text-muted">
                        <i class="fas fa-users me-1"></i> Audience: {{ $a->audience }}
                        @if($a->course) · <i class="fas fa-book me-1"></i> Course: {{ $a->course->title }} @endif
                        · <i class="fas fa-user-check me-1"></i> Recipients: {{ $a->recipients_count }}
                        · <i class="fas fa-calendar-alt me-1"></i> {{ $a->created_at->format('Y-m-d H:i') }}
                        @if(!$a->is_published)
                            <span class="badge bg-secondary ms-2"><i class="fas fa-eye-slash me-1"></i> Unpublished</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('admin.announcements.toggle', $a->id) }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas {{ $a->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            {{ $a->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $a->id) }}" 
                          onsubmit="return confirm('Delete this announcement?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-muted">
            <i class="fas fa-info-circle me-1"></i> No announcements yet.
        </div>
    @endforelse

    <div class="mt-3">{{ $announcements->links() }}</div>
</div>
@endsection

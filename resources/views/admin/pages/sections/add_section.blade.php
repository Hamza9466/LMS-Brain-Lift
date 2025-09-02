@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h4>Add Section</h4>
    <form method="POST" action="{{ route('sections.store') }}">
        @csrf

        @if($course)
            <div class="mb-3">
                <label class="form-label">Course</label>
                <input type="text" class="form-control border-1 bg-white" value="{{ $course->title }}" disabled>
                <input type="hidden" name="course_id" value="{{ $course->id }}">
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Section Title</label>
            <input type="text" name="title" class="form-control border-1 bg-white" required>
        </div>

        <button class="btn btn-primary">Create Section</button>
    </form>
</div>
@endsection

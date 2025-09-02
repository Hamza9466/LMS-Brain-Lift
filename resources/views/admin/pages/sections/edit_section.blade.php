@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h4>Edit Section</h4>
    <form method="POST" action="{{ route('sections.update', $section->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" class="form-select border-1 bg-white" required>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ $course->id == $section->course_id ? 'selected' : '' }}>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Section Title</label>
            <input type="text" name="title" class="form-control border-1 bg-white" value="{{ $section->title }}" required>
        </div>

        <button class="btn btn-success">Update Section</button>
    </form>
</div>
@endsection

@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <a href="{{ route('student.courses.show', $course->id) }}" class="small">&larr; Back to course</a>
    <h3 class="mt-2 mb-3">{{ $course->title }} — {{ $section->title }}</h3>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <div class="list-group">
        @forelse($lessonStates as $row)
            @php($lesson = $row['lesson'])
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('student.lessons.show', $lesson->id) }}">{{ $lesson->title }}</a>
                <span class="badge {{ $row['completed'] ? 'bg-success' : 'bg-light text-dark' }}">
                    {{ $row['completed'] ? 'Done' : 'Published' }}
                </span>
            </div>
        @empty
            <div class="list-group-item text-muted">No lessons in this section.</div>
        @endforelse
    </div>
</div>
@endsection

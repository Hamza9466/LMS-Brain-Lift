@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h3 class="mb-2">{{ $course->title }}</h3>
    @if(!empty($course->short_description))
      <div class="text-muted mb-4">{{ $course->short_description }}</div>
    @endif

    @foreach($sectionsData as $row)
        @php
            $section   = $row['section'];
            $unlocked  = (bool) $row['unlocked'];
            $progress  = (int)  $row['progress'];
            $badge     = $row['isCompleted'] ? 'success' : ($unlocked ? 'primary' : 'secondary');
            $status    = $row['isCompleted'] ? 'Completed' : ($unlocked ? 'Unlocked' : 'Locked');
        @endphp

        <div class="card mb-3">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-1">
                        {{ $section->title }}
                        <span class="badge bg-{{ $badge }} ms-2">{{ $status }}</span>
                    </h5>
                    <div class="small text-muted">
                        {{ $row['done'] }} / {{ $row['total'] }} lessons · {{ $progress }}% complete
                    </div>
                </div>

                {{-- Action --}}
                @if($unlocked)
                    <a href="{{ route('student.sections.show', $section->id) }}"
                       class="btn btn-outline-primary">
                        Open
                    </a>
                @else
                    <button type="button"
                            class="btn btn-outline-secondary"
                            aria-disabled="true"
                            disabled
                            title="Complete previous section and pass its MCQs to unlock">
                        Locked
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection

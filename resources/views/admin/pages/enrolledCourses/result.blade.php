@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
  <a href="{{ route('student.courses.show', $attempt->quiz->course_id) }}" class="small">&larr; Back to course</a>

  <h4 class="mb-3">Quiz Result — {{ $attempt->quiz->title }}</h4>

  <div class="alert {{ $attempt->is_passed ? 'alert-success' : 'alert-danger' }}">
    Score: <strong>{{ rtrim(rtrim((string)$attempt->percentage,'0'),'.') }}%</strong> —
    {!! $attempt->is_passed ? 'You passed! 🎉' : 'You did not pass.' !!}
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <div class="text-muted small">Attempt Status</div>
          <div class="fw-semibold">{{ ucfirst($attempt->status) }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-muted small">Started</div>
          <div class="fw-semibold">{{ optional($attempt->started_at)->format('Y-m-d H:i') }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-muted small">Submitted</div>
          <div class="fw-semibold">{{ optional($attempt->submitted_at)->format('Y-m-d H:i') }}</div>
        </div>
        <div class="col-md-3">
          <div class="text-muted small">Duration (s)</div>
          <div class="fw-semibold">{{ $attempt->duration_seconds }}</div>
        </div>
      </div>
    </div>
  </div>

  <h6 class="mb-3">Your Answers</h6>

  @forelse($attempt->answers as $ans)
    @php $q = $ans->question; @endphp

    @if($q)
      @php
        $correctIds  = collect($q->options)->where('is_correct', true)->pluck('id')->map('intval')->values();
        $selectedIds = collect($ans->selected_option_ids ?? [])->map('intval')->values();
        $isCorrect   = $selectedIds->sort()->values()->all() === $correctIds->sort()->values()->all();
      @endphp

      <div class="card mb-2">
        <div class="card-body">
          <div class="fw-semibold mb-2">{!! nl2br(e($q->text)) !!}</div>

          <ul class="mb-2">
            @foreach($q->options as $op)
              <li>
                {{ $op->text }}
                @if($selectedIds->contains((int)$op->id))
                  <span class="badge bg-info ms-1">Your choice</span>
                @endif
                @if($op->is_correct)
                  <span class="badge bg-success ms-1">Correct</span>
                @endif
              </li>
            @endforeach
          </ul>

          <div>
            @if($isCorrect)
              <span class="badge bg-success">
                Correct (+{{ rtrim(rtrim((string)$q->points,'0'),'.') }})
              </span>
            @else
              <span class="badge bg-danger">Incorrect (+0)</span>
            @endif
          </div>
        </div>
      </div>
    @else
      <div class="alert alert-warning">One of your answers references a missing question.</div>
    @endif
  @empty
    <div class="text-muted">No answers recorded for this attempt.</div>
  @endforelse
</div>
@endsection

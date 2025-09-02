@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
  <a href="{{ route('admin.quizzes.attempts.index', $attempt->quiz_id) }}" class="small">&larr; Back to attempts</a>

  <h4 class="fw-bold mt-2 mb-3">Attempt #{{ $attempt->id }} — {{ $attempt->quiz->title }}</h4>

  <div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body">
      <div class="text-muted small">User</div>
      <div>{{ $attempt->user->email ?? '—' }}</div>
    </div></div></div>

    <div class="col-md-3"><div class="card"><div class="card-body">
      <div class="text-muted small">Status</div>
      <div><span class="badge {{ $attempt->status==='submitted'?'bg-success':'bg-warning text-dark' }}">{{ ucfirst($attempt->status) }}</span></div>
    </div></div></div>

    <div class="col-md-3"><div class="card"><div class="card-body">
      <div class="text-muted small">Score</div>
      <div>{{ rtrim(rtrim($attempt->score,'0'),'.') }}</div>
    </div></div></div>

    <div class="col-md-3"><div class="card"><div class="card-body">
      <div class="text-muted small">Percentage</div>
      <div>{{ rtrim(rtrim($attempt->percentage,'0'),'.') }}%</div>
    </div></div></div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Answers</strong></div>
    <div class="card-body">
      @forelse($attempt->answers as $ans)
        <div class="mb-3">
          <div class="fw-semibold mb-1">Q{{ $loop->iteration }}. {!! nl2br(e($ans->question->text)) !!}</div>

          @php
            $selected = collect($ans->selected_option_ids ?? []);
            $correct  = $ans->question->options->where('is_correct', true)->pluck('id');
          @endphp

          @foreach($ans->question->options as $op)
            @php
              $isSel = $selected->contains($op->id);
              $isCor = $op->is_correct;
            @endphp
            <div class="small mb-1">
              <span class="badge {{ $isCor ? 'bg-success' : 'bg-secondary' }}">{{ $isCor ? 'Correct' : 'Option' }}</span>
              {!! $isSel ? '<span class="badge bg-primary ms-1">Selected</span>' : '' !!}
              <span class="ms-2">{{ $op->text }}</span>
            </div>
          @endforeach

          <div class="text-muted small mt-1">
            Awarded: {{ rtrim(rtrim($ans->points_awarded,'0'),'.') }} / {{ rtrim(rtrim($ans->question->points,'0'),'.') }}
          </div>
        </div>
        @if(!$loop->last) <hr> @endif
      @empty
        <div class="text-muted">No answers recorded.</div>
      @endforelse
    </div>
  </div>
</div>
@endsection

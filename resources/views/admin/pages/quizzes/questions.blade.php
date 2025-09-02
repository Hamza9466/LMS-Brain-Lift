{{-- resources/views/admin/pages/quizzes/questions.blade.php --}}
@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Questions — {{ $quiz->title }}</h4>
    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-light">Back</a>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif

  {{-- Add new question --}}
  <div class="card mb-4">
    <div class="card-header bg-white">Add Question</div>
    <div class="card-body">
      <form method="POST" action="{{ route('admin.quizzes.questions.store', $quiz->id) }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
              <option value="single">Single choice</option>
              <option value="multiple">Multiple choice</option>
              <option value="true_false">True / False</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Points</label>
            <input type="number" step="0.01" min="0" class="form-control" name="points" value="1" required>
          </div>
          <div class="col-md-7">
            <label class="form-label">Question</label>
            <input class="form-control" name="text" required>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Options</label>

          {{-- 4 option rows by default --}}
          @for($i=0; $i<4; $i++)
            <div class="input-group mb-2">
              <div class="input-group-text">
                {{-- Ensure key always exists with 0 --}}
                <input type="hidden" name="options[{{ $i }}][correct]" value="0">
                {{-- Checked sends 1 --}}
                <input class="form-check-input mt-0" type="checkbox" name="options[{{ $i }}][correct]" value="1">
              </div>
              <input type="text" name="options[{{ $i }}][text]" class="form-control" placeholder="Option text">
            </div>
          @endfor
          <small class="text-muted">Check the box next to each correct answer.</small>
        </div>

        <div class="text-end mt-3">
          <button class="btn btn-primary">Add Question</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Existing questions --}}
  @forelse($quiz->questions as $q)
    <div class="card mb-2">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="fw-semibold">{{ strtoupper($q->type) }} · {{ rtrim(rtrim($q->points,'0'),'.') }} pts</div>
            <div class="mb-2">{!! nl2br(e($q->text)) !!}</div>
            <ul class="mb-0">
              @foreach($q->options as $op)
                <li>
                  @if($op->is_correct) <span class="badge bg-success me-1">Correct</span> @endif
                  {{ $op->text }}
                </li>
              @endforeach
            </ul>
          </div>
          <div class="ms-3">
            <a href="{{ route('admin.questions.edit',$q->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            <form class="d-inline" method="POST" action="{{ route('admin.questions.destroy',$q->id) }}" onsubmit="return confirm('Delete question?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="text-muted">No questions yet.</div>
  @endforelse
</div>
@endsection

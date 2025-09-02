{{-- resources/views/admin/pages/quizzes/question_edit.blade.php --}}
@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Question — {{ $question->quiz->title }}</h4>
    <a href="{{ route('admin.quizzes.questions.index', $question->quiz_id) }}" class="btn btn-light">Back</a>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif

  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.questions.update', $question->id) }}">
        @csrf @method('PUT')

        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
              <option value="single"     @selected($question->type==='single')>Single choice</option>
              <option value="multiple"   @selected($question->type==='multiple')>Multiple choice</option>
              <option value="true_false" @selected($question->type==='true_false')>True / False</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Points</label>
            <input type="number" step="0.01" min="0" class="form-control" name="points" value="{{ $question->points }}" required>
          </div>
          <div class="col-md-7">
            <label class="form-label">Question</label>
            <input class="form-control" name="text" value="{{ $question->text }}" required>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Options</label>
          @foreach($question->options as $i => $op)
            <div class="input-group mb-2">
              <div class="input-group-text">
                <input type="hidden" name="options[{{ $i }}][correct]" value="0">
                <input class="form-check-input mt-0" type="checkbox" name="options[{{ $i }}][correct]" value="1" @checked($op->is_correct)>
              </div>
              <input type="text" name="options[{{ $i }}][text]" class="form-control" value="{{ $op->text }}" placeholder="Option text">
            </div>
          @endforeach
          <small class="text-muted">Check the box next to each correct answer.</small>
        </div>

        <div class="text-end mt-3">
          <button class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

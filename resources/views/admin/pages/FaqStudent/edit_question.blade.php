@extends('admin.layouts.main')

@section('content')
<h4 class="mb-3">Edit Student FAQ</h4>

<form action="{{ route('admin.faq-students.update', $faq) }}" method="POST">
  @csrf @method('PUT')

  <div class="mb-3">
    <label class="form-label">Question</label>
    <input type="text" name="question" class="form-control border-1 bg-white @error('question') is-invalid @enderror" value="{{ old('question', $faq->question) }}">
    @error('question') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

 

  <button class="btn btn-primary">Update</button>
  <a href="{{ route('admin.faq-students.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection

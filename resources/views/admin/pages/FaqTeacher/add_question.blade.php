@extends('admin.layouts.main')

@section('content')
<h4 class="mb-3">Add Teacher FAQ</h4>

<form action="{{ route('admin.faq-teachers.store') }}" method="POST" class="card card-body shadow-sm">
  @csrf

  <div class="mb-3">
    <label class="form-label">Question</label>
    <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question') }}">
    @error('question') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

 

  <div class="d-flex gap-2">
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('admin.faq-teachers.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection

@extends('admin.layouts.main')

@section('content')

        <div class="col-lg-11">

<h4 class="mb-3">Add Student FAQ</h4>

<form action="{{ route('admin.faq-students.store') }}" method="POST">
  @csrf

  <div class="mb-3">
    <label class="form-label">Question</label>
    <input type="text" name="question" class="form-control border-1 bg-white @error('question') is-invalid @enderror" value="{{ old('question') }}">
    @error('question') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

 

  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.faq-students.index') }}" class="btn btn-secondary">Cancel</a>
</form>
        </div>
@endsection

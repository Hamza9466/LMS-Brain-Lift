@extends('admin.layouts.main')

@section('content')
@php
  $isEdit = $quiz->exists;
@endphp
<div class="container py-4">
  <h4 class="mb-3">{{ $isEdit ? 'Edit Quiz' : 'Add Quiz' }}</h4>

  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ $isEdit ? route('admin.quizzes.update',$quiz->id) : route('admin.quizzes.store') }}">
    @csrf @if($isEdit) @method('PUT') @endif

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Course</label>
        <select name="course_id" id="course_id" class="form-select bg-white border-1" required>
          <option value="">Select</option>
          @foreach($courses as $c)
            <option value="{{ $c->id }}" {{ old('course_id',$quiz->course_id) == $c->id ? 'selected':'' }}>{{ $c->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Section (optional)</label>
        <select name="section_id" id="section_id" class="form-select bg-white border-1">
          <option value="">— None —</option>
        </select>
      </div>
      <div class="col-md-8">
        <label class="form-label">Title</label>
        <input class="form-control bg-white border-1" name="title" value="{{ old('title',$quiz->title) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Duration (minutes)</label>
        <input type="number" min="1" class="form-control bg-white border-1" name="duration_minutes" value="{{ old('duration_minutes',$quiz->duration_minutes) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Max Attempts</label>
        <input type="number" min="1" class="form-control bg-white border-1" name="max_attempts" value="{{ old('max_attempts',$quiz->max_attempts ?? 1) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Pass Percentage</label>
        <input type="number" step="0.01" min="0" max="100" class="form-control bg-white border-1" name="pass_percentage" value="{{ old('pass_percentage',$quiz->pass_percentage ?? 70) }}" required>
      </div>
      <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea class="form-control bg-white border-1" rows="3" name="description">{{ old('description',$quiz->description) }}</textarea>
      </div>
      <div class="col-md-12">
        <div class="form-check form-check-inline">
          <input class="form-check-input bg-white border-1" type="checkbox" name="shuffle_questions" value="1" id="sq"
            {{ old('shuffle_questions',$quiz->shuffle_questions) ? 'checked':'' }}>
          <label class="form-check-label" for="sq">Shuffle Questions</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input bg-white border-1" type="checkbox" name="shuffle_options" value="1" id="so"
            {{ old('shuffle_options',$quiz->shuffle_options) ? 'checked':'' }}>
          <label class="form-check-label" for="so">Shuffle Options</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input bg-white border-1" type="checkbox" name="is_published" value="1" id="pub"
            {{ old('is_published',$quiz->is_published) ? 'checked':'' }}>
          <label class="form-check-label" for="pub">Published</label>
        </div>
      </div>
    </div>

    <div class="text-end mt-4">
      <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }}</button>
    </div>
  </form>
</div>

{{-- sections-by-course populate --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
  const allSections = @json($sections);
  const courseSel = document.getElementById('course_id');
  const sectionSel = document.getElementById('section_id');
  function fillSections() {
    const cid = courseSel.value;
    const selected = "{{ old('section_id',$quiz->section_id) }}";
    sectionSel.innerHTML = '<option value="">— None —</option>';
    allSections.filter(s => String(s.course_id) === String(cid)).forEach(s => {
      const opt = document.createElement('option');
      opt.value = s.id; opt.textContent = s.title;
      if (String(selected) === String(s.id)) opt.selected = true;
      sectionSel.appendChild(opt);
    });
  }
  courseSel.addEventListener('change', fillSections);
  if (courseSel.value) fillSections();
});
</script>
@endsection

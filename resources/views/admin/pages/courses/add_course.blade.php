@extends('admin.layouts.main')

@section('content')
@php
    $free = old('is_free', $course->is_free ?? false);
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 fw-bold">{{ isset($course) ? 'Edit Course' : 'Add New Course' }}</h4>
                </div>

                {{-- Global alerts --}}
                @if ($errors->any())
                    <div class="alert alert-danger m-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success m-3">{{ session('success') }}</div>
                @endif

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data"
                          action="{{ isset($course) ? route('courses.update', $course->id) : route('courses.store') }}">
                        @csrf
                        @if(isset($course)) @method('PUT') @endif

                        {{-- Owner kept hidden if your controller expects it --}}
                        <input type="hidden" name="teacher_id"
                               value="{{ old('teacher_id', $course->teacher_id ?? auth()->id()) }}">

                        {{-- Title + Slug --}}
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Course Title</label>
                                <input type="text" name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $course->title ?? '') }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Slug (optional)</label>
                                <input type="text" name="slug"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       value="{{ old('slug', $course->slug ?? '') }}"
                                       placeholder="leave blank to auto-generate">
                                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Short/Long Descriptions --}}
                        <div class="mt-3">
                            <label class="form-label fw-semibold">Short Description</label>
                            <input type="text" name="short_description"
                                   class="form-control @error('short_description') is-invalid @enderror"
                                   value="{{ old('short_description', $course->short_description ?? '') }}">
                            @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Course Description (summary)</label>
                            <textarea name="description" rows="3" required
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description ?? '') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Long Description (About this course)</label>
                            <textarea name="long_description" rows="6"
                                      class="form-control @error('long_description') is-invalid @enderror">{{ old('long_description', $course->long_description ?? '') }}</textarea>
                            @error('long_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Subject / Level / Language --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Subject</label>
                                <input type="text" name="subject"
                                       class="form-control @error('subject') is-invalid @enderror"
                                       value="{{ old('subject', $course->subject ?? '') }}"
                                       placeholder="e.g., Data Modeling">
                                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Level</label>
                                <input type="text" name="level"
                                       class="form-control @error('level') is-invalid @enderror"
                                       value="{{ old('level', $course->level ?? 'Beginner') }}"
                                       placeholder="Beginner, Intermediate, Advanced">
                                @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Language</label>
                                <input type="text" name="language"
                                       class="form-control @error('language') is-invalid @enderror"
                                       value="{{ old('language', $course->language ?? 'English') }}">
                                @error('language') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="mt-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">-- Select Category --</option>
                                @foreach(\App\Models\CourseCategory::orderBy('name')->get() as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ (string)old('category_id', $course->category_id ?? '') === (string)$cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Thumbnail (Promo Video removed) --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thumbnail Image</label>
                                <input type="file" name="thumbnail"
                                       class="form-control @error('thumbnail') is-invalid @enderror">
                                @error('thumbnail') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                                @if(isset($course) && $course->thumbnail)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" width="120"
                                             class="img-thumbnail" alt="Thumbnail">
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Cached Stats --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Total Lessons</label>
                                <input type="number" name="total_lessons" min="0"
                                       class="form-control @error('total_lessons') is-invalid @enderror"
                                       value="{{ old('total_lessons', $course->total_lessons ?? '') }}">
                                @error('total_lessons') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Total Duration (minutes)</label>
                                <input type="number" name="total_duration_minutes" min="0"
                                       class="form-control @error('total_duration_minutes') is-invalid @enderror"
                                       value="{{ old('total_duration_minutes', $course->total_duration_minutes ?? '') }}">
                                @error('total_duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Lists + tags/materials --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">What you will learn (one per line)</label>
                                <textarea name="what_you_will_learn" rows="4"
                                          class="form-control @error('what_you_will_learn') is-invalid @enderror"
                                          placeholder="Point 1&#10;Point 2">{{ old('what_you_will_learn', isset($course->what_you_will_learn) ? implode("\n", $course->what_you_will_learn) : '') }}</textarea>
                                @error('what_you_will_learn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Requirements (one per line)</label>
                                <textarea name="requirements" rows="4"
                                          class="form-control @error('requirements') is-invalid @enderror"
                                          placeholder="Requirement 1&#10;Requirement 2">{{ old('requirements', isset($course->requirements) ? implode("\n", $course->requirements) : '') }}</textarea>
                                @error('requirements') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Who is this for (one per line)</label>
                                <textarea name="who_is_for" rows="4"
                                          class="form-control @error('who_is_for') is-invalid @enderror"
                                          placeholder="Audience 1&#10;Audience 2">{{ old('who_is_for', isset($course->who_is_for) ? implode("\n", $course->who_is_for) : '') }}</textarea>
                                @error('who_is_for') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tags (comma-separated)</label>
                                <input type="text" name="tags"
                                       class="form-control @error('tags') is-invalid @enderror"
                                       value="{{ old('tags', isset($course->tags) ? implode(',', $course->tags) : '') }}"
                                       placeholder="data,modeling,analysis">
                                @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Materials (comma-separated)</label>
                                <input type="text" name="materials"
                                       class="form-control @error('materials') is-invalid @enderror"
                                       value="{{ old('materials', isset($course->materials) ? implode(',', $course->materials) : '') }}"
                                       placeholder="Videos,Booklets">
                                @error('materials') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" name="is_free" id="is_free" {{ $free ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_free">Free</label>
                                </div>
                                @error('is_free') <div class="text-danger small ms-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Price</label>
                                <input type="number" step="0.01" name="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $course->price ?? '') }}" {{ $free ? 'disabled' : '' }}>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Compare at Price</label>
                                <input type="number" step="0.01" name="compare_at_price"
                                       class="form-control @error('compare_at_price') is-invalid @enderror"
                                       value="{{ old('compare_at_price', $course->compare_at_price ?? '') }}" {{ $free ? 'disabled' : '' }}>
                                @error('compare_at_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Discount %</label>
                                <input type="number" step="0.01" name="discount_percentage"
                                       class="form-control @error('discount_percentage') is-invalid @enderror"
                                       value="{{ old('discount_percentage', $course->discount_percentage ?? '') }}" {{ $free ? 'disabled' : '' }}>
                                @error('discount_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Discount Price</label>
                                <input type="number" step="0.01" name="discount_price"
                                       class="form-control @error('discount_price') is-invalid @enderror"
                                       value="{{ old('discount_price', $course->discount_price ?? '') }}" {{ $free ? 'disabled' : '' }}>
                                @error('discount_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">
                            If “Free” is checked, all price/discount fields are ignored on save.
                        </small>

                        {{-- Status / Publish date --}}
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft"     {{ old('status', $course->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $course->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Published At (optional)</label>
                                <input type="datetime-local" name="published_at"
                                       class="form-control @error('published_at') is-invalid @enderror"
                                       value="{{ old('published_at', isset($course->published_at) ? $course->published_at->format('Y-m-d\TH:i') : '') }}">
                                @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                {{ isset($course) ? 'Update Course' : 'Create Course' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

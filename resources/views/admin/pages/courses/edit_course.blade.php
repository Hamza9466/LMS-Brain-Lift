@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Edit Course</h5>
                </div>

                <div class="card-body">
                    {{-- Messages --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" enctype="multipart/form-data" action="{{ route('courses.update', $course->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Title + Slug (slug read-only) --}}
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Course Title</label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ old('title', $course->title) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control"
                                       value="{{ old('slug', $course->slug) }}" readonly>
                            </div>
                        </div>

                        {{-- Short / Description / Long Description --}}
                        <div class="mt-3">
                            <label class="form-label">Short Description</label>
                            <input type="text" name="short_description" class="form-control"
                                   value="{{ old('short_description', $course->short_description) }}">
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Course Description (summary)</label>
                            <textarea name="description" rows="3" class="form-control" required>{{ old('description', $course->description) }}</textarea>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Long Description (About this course)</label>
                            <textarea name="long_description" rows="6" class="form-control">{{ old('long_description', $course->long_description) }}</textarea>
                        </div>

                        {{-- Subject / Level / Language --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="form-label">Subject</label>
                                <input type="text" name="subject" class="form-control"
                                       value="{{ old('subject', $course->subject) }}" placeholder="e.g., Data Modeling">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Level</label>
                                <input type="text" name="level" class="form-control"
                                       value="{{ old('level', $course->level) }}" placeholder="Beginner, Intermediate, Advanced">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Language</label>
                                <input type="text" name="language" class="form-control"
                                       value="{{ old('language', $course->language ?? 'English') }}">
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
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thumbnail (Promo field removed) --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control">
                                @if($course->thumbnail)
                                    <div class="mt-2">
                                        <span class="d-block text-muted">Current:</span>
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Course Thumbnail" class="img-thumbnail" width="150">
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Cached Stats --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Total Lessons</label>
                                <input type="number" name="total_lessons" min="0" class="form-control"
                                       value="{{ old('total_lessons', $course->total_lessons) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Duration (minutes)</label>
                                <input type="number" name="total_duration_minutes" min="0" class="form-control"
                                       value="{{ old('total_duration_minutes', $course->total_duration_minutes) }}">
                            </div>
                        </div>

                        {{-- Lists & Arrays --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">What you will learn (one per line)</label>
                                <textarea name="what_you_will_learn" rows="4" class="form-control"
                                  placeholder="Point 1&#10;Point 2">{{ old('what_you_will_learn', $course->what_you_will_learn ? implode("\n", $course->what_you_will_learn) : '') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Requirements (one per line)</label>
                                <textarea name="requirements" rows="4" class="form-control"
                                  placeholder="Requirement 1&#10;Requirement 2">{{ old('requirements', $course->requirements ? implode("\n", $course->requirements) : '') }}</textarea>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Who is this for (one per line)</label>
                                <textarea name="who_is_for" rows="4" class="form-control"
                                  placeholder="Audience 1&#10;Audience 2">{{ old('who_is_for', $course->who_is_for ? implode("\n", $course->who_is_for) : '') }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tags (comma-separated)</label>
                                <input type="text" name="tags" class="form-control"
                                       value="{{ old('tags', $course->tags ? implode(',', $course->tags) : '') }}"
                                       placeholder="data,modeling,analysis">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Materials (comma-separated)</label>
                                <input type="text" name="materials" class="form-control"
                                       value="{{ old('materials', $course->materials ? implode(',', $course->materials) : '') }}"
                                       placeholder="Videos,Booklets">
                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="row g-3 mt-1">
                            <div class="col-md-2 d-flex align-items-end">
                                <input type="hidden" name="is_free" value="0">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" name="is_free" id="is_free" value="1"
                                           {{ old('is_free', $course->is_free) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_free">Free</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" name="price" class="form-control"
                                       value="{{ old('price', $course->price) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Compare at Price</label>
                                <input type="number" step="0.01" name="compare_at_price" class="form-control"
                                       value="{{ old('compare_at_price', $course->compare_at_price) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Discount %</label>
                                <input type="number" step="0.01" name="discount_percentage" class="form-control"
                                       value="{{ old('discount_percentage', $course->discount_percentage) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Discount Price</label>
                                <input type="number" step="0.01" name="discount_price" class="form-control"
                                       value="{{ old('discount_price', $course->discount_price) }}">
                            </div>
                        </div>

                        {{-- Status / Published At --}}
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="draft"     {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Published At (optional)</label>
                                <input type="datetime-local" name="published_at" class="form-control"
                                       value="{{ old('published_at', $course->published_at ? $course->published_at->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Update Course</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

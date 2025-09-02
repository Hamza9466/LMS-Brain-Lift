@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Edit Category</h4>
                <a href="{{ route('admin.course-categories.index') }}" class="btn btn-outline-secondary">
                    ← Back to Categories
                </a>
            </div>

            {{-- Alerts --}}
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

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">Update details</h5>
                </div>

                <div class="card-body">
                    {{-- enctype is required for file uploads --}}
                    <form method="POST" action="{{ route('admin.course-categories.update', $category) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug</label>
                            <input type="text" name="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $category->slug) }}"
                                   placeholder="Leave blank to auto-generate">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">If left empty, a unique slug will be generated from the name.</small>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Optional description...">{{ old('description', $category->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Image --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category Image</label>
                            <input type="file" name="image"
                                   class="form-control @error('image') is-invalid @enderror">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            @if ($category->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$category->image) }}"
                                         alt="Category Image"
                                         class="img-thumbnail" width="150">
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="text-end mt-4">
                            <a href="{{ route('admin.course-categories.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
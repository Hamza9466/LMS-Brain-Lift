@extends('admin.layouts.main')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">Course Categories</h4>
        <a href="{{ route('admin.course-categories.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> New Category
        </a>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Search --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input
                type="text"
                name="q"
                value="{{ $q ?? '' }}"
                class="form-control border-1 bg-white shadow-sm"
                placeholder="Search name or slug..."
            >
        </div>
        <div class="col-md-2">
            <button class="btn btn-warning w-100 shadow-sm">
                <i class="fas fa-search me-1"></i> Search
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                        <tr>
                            <th class="px-3 py-3">#</th>
                            <th class="py-3">Image</th>
                            <th class="py-3">Name</th>
                            <th class="py-3">Slug</th>
                            <th class="py-3">Description</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $c)
                            <tr class="border-bottom">
                                <td class="px-3">
                                    {{ ($categories->firstItem() ?? 1) + $loop->index }}
                                </td>
                                <td>
                                    @php
                                        // Build a safe public URL for the stored file
                                        $imgUrl = $c->image && Storage::disk('public')->exists($c->image)
                                            ? Storage::url($c->image)   // => /storage/course_categories/xxx.jpg
                                            : null;
                                    @endphp

                                    @if($imgUrl)
                                        <img
                                            src="{{ $imgUrl }}"
                                            alt="{{ $c->name }}"
                                            class="img-thumbnail shadow-sm"
                                            width="70"
                                            height="70"
                                            style="object-fit:cover"
                                        >
                                    @else
                                        <span class="text-muted fst-italic">No Image</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $c->name }}</td>
                                <td>{{ $c->slug }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($c->description, 80) }}</td>
                                <td class="text-center">
                                    <a
                                        href="{{ route('admin.course-categories.edit', $c) }}"
                                        class="text-primary me-2"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit fa-lg"></i>
                                    </a>
                                    <form
                                        action="{{ route('admin.course-categories.destroy', $c) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this category?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link text-danger p-0 m-0" title="Delete">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-3">
            {{ $categories->appends(['q' => $q])->links() }}
        </div>
    @endif
</div>
@endsection

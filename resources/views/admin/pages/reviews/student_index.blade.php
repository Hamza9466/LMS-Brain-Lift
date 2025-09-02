{{-- resources/views/admin/pages/reviews/student_index.blade.php --}}
@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h4 class="dashboard-title">Reviews</h4>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
      <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    @forelse($reviews as $r)
        @php
            $thumb = $r->course?->thumbnail
                ? asset('storage/'.$r->course->thumbnail)
                : asset('assets/images/courses/default-thumbnail.jpg');
        @endphp

        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body d-flex gap-3 align-items-start">
                <img src="{{ $thumb }}" alt="" width="180" height="120"
                     style="object-fit:cover;border-radius:.5rem;">

                <div class="flex-grow-1">
                    {{-- Title row like: Course: Overview of Exercise --}}
                    <h5 class="mb-2">
                        <span class="text-muted">Course:</span>
                        <span class="fw-semibold">{{ $r->course->title ?? 'Course' }}</span>
                    </h5>

                    {{-- Stars on their own line, left aligned --}}
                    <div class="mb-1" aria-label="Rating: {{ $r->rating }} out of 5">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= (int)$r->rating ? 'text-warning' : 'text-muted' }}" style="font-size:18px;">★</span>
                        @endfor
                    </div>

                    {{-- Time ago under stars --}}
                    <div class="text-muted small mb-2">
                        {{ $r->created_at->diffForHumans() }}
                    </div>

                    {{-- Comment --}}
                    @if($r->comment)
                        <div class="fs-6">{{ $r->comment }}</div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-muted text-center py-5">You haven’t written any reviews yet.</div>
    @endforelse

    {{ $reviews->links() }}
</div>
@endsection

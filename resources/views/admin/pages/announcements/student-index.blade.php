@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">
            <i class="fas fa-bullhorn me-2"></i> Announcements
        </h4>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 rounded-3">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Announcements List --}}
    @forelse($rows as $row)
        @php
            $a = $row->announcement;
            $isRead = !empty($row->read_at);
        @endphp
        <div class="card shadow-sm border-0 rounded-3 mb-3 {{ !$isRead ? 'border-primary' : '' }}">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-semibold mb-1">
                        <i class="fas fa-bullhorn text-primary me-1"></i> {{ $a->title }}
                        @if(!$isRead)
                            <span class="badge bg-primary ms-2"><i class="fas fa-star me-1"></i> New</span>
                        @endif
                    </div>
                    <div class="small text-muted mb-2">
                        <i class="fas fa-clock me-1"></i> {{ $a->created_at->diffForHumans() }}
                    </div>
                    <div class="text-body">{!! nl2br(e($a->body)) !!}</div>
                </div>
                <div>
                    @if(!$isRead)
                        <form method="POST" action="{{ route('student.announcements.read', $a->id) }}">
                            @csrf
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-check me-1"></i> Mark as Read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-muted">
            <i class="fas fa-info-circle me-1"></i> No announcements.
        </div>
    @endforelse

    <div class="mt-3">
        {{ $rows->links() }}
    </div>
</div>
@endsection

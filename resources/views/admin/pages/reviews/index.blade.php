@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">Course Reviews</h4>

        {{-- Filter --}}
        <form class="d-flex gap-2" method="GET" action="{{ route('coursereview.index') }}">
            <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="approved" {{ ($status ?? '')==='approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending"  {{ ($status ?? '')==='pending'  ? 'selected' : '' }}>Pending</option>
            </select>
            <noscript><button class="btn btn-sm btn-secondary">Filter</button></noscript>
        </form>
    </div>

    @if (session('success'))
      <div class="alert alert-success shadow-sm border-0 rounded-3">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
      </div>
    @endif
    @if (session('info'))
      <div class="alert alert-info shadow-sm border-0 rounded-3">
        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
      </div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger shadow-sm border-0 rounded-3">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                    <tr>
                        <th class="px-3 py-3">#</th>
                        <th class="py-3">Course</th>
                        <th class="py-3">User</th>
                        <th class="py-3 text-center">Rating</th>
                        <th class="py-3">Title</th>
                        <th class="py-3">Comment</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3">Created</th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $rev)
                        <tr class="border-bottom">
                            <td class="px-3">{{ $rev->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $rev->course->title ?? '—' }}</div>
                                <div class="text-muted small">{{ $rev->course->slug ?? '' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $rev->user->email ?? 'User #'.$rev->user_id }}</div>
                                <div class="text-muted small">ID: {{ $rev->user_id }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-dark border">{{ $rev->rating }}★</span>
                            </td>
                            <td>{{ $rev->title ?? '—' }}</td>
                            <td style="max-width: 360px;">
                                <div class="text-truncate" title="{{ $rev->comment }}">{{ $rev->comment }}</div>
                            </td>
                            <td class="text-center">
                                @if($rev->is_approved)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $rev->created_at->format('d M Y, h:i A') }}</div>
                                <div class="text-muted small">{{ $rev->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-center">
                                {{-- Approve / Reject toggle --}}
                                <form method="POST" action="{{ route('coursereview.update', $rev->id) }}" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="is_approved" value="{{ $rev->is_approved ? 0 : 1 }}">
                                    <button class="btn btn-sm {{ $rev->is_approved ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                            title="{{ $rev->is_approved ? 'Reject Review' : 'Approve Review' }}">
                                        <i class="fas {{ $rev->is_approved ? 'fa-times' : 'fa-check' }}"></i>
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('coursereview.destroy', $rev->id) }}" class="d-inline"
                                      onsubmit="return confirm('Delete this review?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete Review">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>No reviews found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white text-center">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection

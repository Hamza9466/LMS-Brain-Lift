@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Notifications</h3>
   <form method="POST" action="{{ route('student.notifications.readAll') }}">
  @csrf
  <button class="btn btn-outline-secondary btn-sm" onclick="return confirm('Mark all as read?')">
    Mark All Read
  </button>
</form>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

  @forelse($notifications as $n)
    <div class="card mb-2 {{ $n->read_at ? '' : 'border-primary' }}">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <div class="fw-semibold">
            {{ $n->title }}
            @if(!$n->read_at) <span class="badge bg-primary ms-2">New</span> @endif
          </div>
          <div class="small text-muted">{{ $n->created_at->diffForHumans() }}</div>
          @if($n->body)
            <div class="mt-1">{!! nl2br(e($n->body)) !!}</div>
          @endif
          @if($n->link_url)
            <div class="mt-2"><a class="btn btn-sm btn-light" href="{{ $n->link_url }}">Open</a></div>
          @endif
        </div>
        <div>
          @if(!$n->read_at)
            <form method="POST" action="{{ route('student.notifications.read', $n->id) }}">
              @csrf
              <button class="btn btn-outline-primary btn-sm">Mark Read</button>
            </form>
          @endif
        </div>
      </div>
    </div>
  @empty
    <div class="text-muted">No notifications.</div>
  @endforelse

  <div class="mt-3">{{ $notifications->links() }}</div>
</div>
@endsection

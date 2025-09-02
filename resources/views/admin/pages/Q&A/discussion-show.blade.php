@extends('admin.layouts.main')

@php
use Illuminate\Support\Str;

if (!function_exists('displayUserLabel')) {
    /**
     * Build a display name using ONLY the users table.
     * Order: first_name/last_name → name → username → email prefix → fallback
     * Admin/Teacher show their role.
     */
    function displayUserLabel($user, $fallbackId = null) {
        if (!$user) return $fallbackId ? ('User #'.$fallbackId) : 'User';

        $role = $user->role ?? null;
        if (in_array($role, ['admin','teacher'], true)) {
            return ucfirst($role);
        }

        $name = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
        if ($name === '' && !empty($user->name))     $name = $user->name;
        if ($name === '' && !empty($user->username)) $name = $user->username;
        if ($name === '' && !empty($user->email))    $name = Str::before($user->email, '@');

        return $name !== '' ? $name : ($fallbackId ? ('Student #'.$fallbackId) : 'Student');
    }
}

$canModerate = in_array(auth()->user()->role ?? 'student', ['admin','teacher'], true);
@endphp

@section('content')
<div class="container py-4">
    <a href="{{ route('student.discussion.index', $thread->lesson_id) }}" class="small">&larr; Back to Discussions</a>

    <h3 class="mt-2">{{ $course->title }}</h3>
    <h5 class="text-muted">Discussions · {{ $lesson->title }}</h5>

    @if(session('success'))
      <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger mt-3">
        <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
      </div>
    @endif

    <div class="card mt-3">
      <div class="card-body d-flex justify-content-between align-items-start">
        <div>
          <h4 class="mb-0">
            {{ $thread->title }}
            @if($thread->is_pinned) <span class="badge bg-primary ms-2">Pinned</span> @endif
          </h4>
          @if($thread->body)
            <p class="mt-2 mb-0">{{ $thread->body }}</p>
          @endif
          <div class="small text-muted mt-1">
            by {{ displayUserLabel($thread->user, $thread->user_id) }} · {{ $thread->created_at?->diffForHumans() }}
          </div>
        </div>

        <div class="text-end">
          <span class="badge bg-{{ $thread->status==='open' ? 'success' : 'secondary' }}">{{ ucfirst($thread->status) }}</span>
          <div class="mt-2">
            {{-- Quick "Reply" jump to chat box --}}
            <a class="btn btn-sm btn-outline-primary" href="#reply">Reply</a>

            {{-- Pin/Delete only for admin/teacher --}}
            @if($canModerate)
              <form method="POST" action="{{ route('admin.discussion.pin', $thread->id) }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-outline-primary">{{ $thread->is_pinned ? 'Unpin' : 'Pin' }}</button>
              </form>

              <form method="POST" action="{{ route('admin.discussion.delete', $thread->id) }}" class="d-inline"
                    onsubmit="return confirm('Delete this discussion?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>

    <h6 class="mt-4 mb-2">Replies</h6>
    @forelse($replies as $r)
      <div class="card mb-2">
        <div class="card-body">
          <div class="fw-semibold">
            {{ displayUserLabel($r->user, $r->user_id) }}
            @if($r->user && $r->user->role)
              <span class="badge bg-info text-dark">{{ ucfirst($r->user->role) }}</span>
            @endif
          </div>
          <div class="small text-muted">{{ $r->created_at?->diffForHumans() }}</div>
          <div class="mt-2">{{ $r->body }}</div>
        </div>
      </div>
    @empty
      <div class="text-muted">No replies yet.</div>
    @endforelse

    {{-- Reply/chat box --}}
    <div class="card mt-3" id="reply">
      <div class="card-body">
        <form method="POST" action="{{ route('student.discussion.reply', $thread->id) }}">
          @csrf
          <label class="form-label">Your message</label>
          <textarea name="body" class="form-control" rows="3" required></textarea>
          <button class="btn btn-primary mt-2">Post Reply</button>
        </form>
      </div>
    </div>
</div>

{{-- Auto-focus when coming from ...#reply --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (location.hash === '#reply') {
    const el = document.querySelector('#reply textarea[name="body"]');
    if (el) { el.focus(); el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
  }
});
</script>
@endsection

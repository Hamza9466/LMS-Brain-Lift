@extends('admin.layouts.main')

@php
use Illuminate\Support\Str;

/**
 * Build a user label using ONLY the users table.
 * Order: first_name/last_name → name → username → email (before @) → fallback.
 * For admin/teacher, show the role.
 */
if (!function_exists('displayUserLabel')) {
    function displayUserLabel($user, $fallbackId = null) {
        if (!$user) return $fallbackId ? ('User #'.$fallbackId) : 'User';

        $role = $user->role ?? null;
        if (in_array($role, ['admin','teacher'], true)) {
            return ucfirst($role);
        }

        $name = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
        if ($name === '' && !empty($user->name))      $name = $user->name;
        if ($name === '' && !empty($user->username))  $name = $user->username;
        if ($name === '' && !empty($user->email))     $name = Str::before($user->email, '@');

        return $name !== '' ? $name : ($fallbackId ? ('User #'.$fallbackId) : 'User');
    }
}
@endphp

@section('content')
<div class="container py-4">

  <a href="{{ route('student.qna.index', $thread->lesson_id) }}" class="small">&larr; Back to Q&amp;A</a>

  {{-- Thread --}}
  <div class="card mt-3">
    <div class="card-body d-flex justify-content-between align-items-start">
      <div>
        <h4 class="mb-0">{{ $thread->title }}</h4>
        @if($thread->body)
          <p class="mt-2 mb-0">{{ $thread->body }}</p>
        @endif
        <div class="small text-muted mt-1">
          by {{ displayUserLabel($thread->user, $thread->user_id) }} · {{ $thread->created_at?->diffForHumans() }}
        </div>
      </div>
      <span class="badge bg-{{ $thread->status==='open' ? 'success' : 'secondary' }}">{{ ucfirst($thread->status) }}</span>
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success')) <div class="alert alert-success mt-3">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger  mt-3">{{ session('error') }}</div>   @endif

  {{-- Replies --}}
  <h6 class="mt-4 mb-2">Replies</h6>
  @php $items = $replies ?? ($thread->replies ?? collect()); @endphp

  @forelse($items as $r)
    <div class="card mb-2">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div>
            <div class="fw-semibold">
              {{ displayUserLabel($r->user, $r->user_id) }}
              @if($r->is_answer) <span class="badge bg-primary ms-2">Answer</span> @endif
            </div>
            <div class="small text-muted">{{ $r->created_at?->diffForHumans() }}</div>
          </div>
        </div>

        @if($r->body)
          <div class="mt-2">{{ $r->body }}</div>
        @endif
      </div>
    </div>
  @empty
    <div class="text-muted">No replies yet.</div>
  @endforelse

  {{-- Reply form --}}
  <div class="card mt-3" id="reply">
    <div class="card-body">
      <form method="POST" action="{{ route('student.qna.reply', $thread->id) }}">
        @csrf
        <div class="mb-2">
          <label class="form-label">Your reply</label>
          <textarea name="body" class="form-control" rows="3" required></textarea>
        </div>
        <button class="btn btn-primary">Post Reply</button>
      </form>
    </div>
  </div>

</div>

{{-- Auto-focus reply when coming from ...#reply --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (location.hash === '#reply') {
    const el = document.querySelector('#reply textarea[name="body"]');
    if (el) { el.focus(); el.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
  }
});
</script>
@endsection

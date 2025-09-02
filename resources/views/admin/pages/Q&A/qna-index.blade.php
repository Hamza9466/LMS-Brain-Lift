@extends('admin.layouts.main')

@php
    use Illuminate\Support\Str;

    /**
     * Show a human label for a user using ONLY the users table.
     * Priority: first_name/last_name → name → username → email (before @) → fallback.
     * For admin/teacher we show the role.
     */
    if (!function_exists('displayUserLabel')) {
        function displayUserLabel($user, $fallbackId = null) {
            if (!$user) {
                return $fallbackId ? ('User #'.$fallbackId) : 'User';
            }

            $role = $user->role ?? null;
            if (in_array($role, ['admin','teacher'], true)) {
                return ucfirst($role);
            }

            $name = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
            if ($name === '' && !empty($user->name)) {
                $name = $user->name;
            }
            if ($name === '' && !empty($user->username)) {
                $name = $user->username;
            }
            if ($name === '' && !empty($user->email)) {
                $name = Str::before($user->email, '@');
            }

            return $name !== '' ? $name : ($fallbackId ? ('User #'.$fallbackId) : 'User');
        }
    }
@endphp

@section('content')
<div class="container py-4">

  <a href="{{ route('student.sections.show', $lesson->section_id) }}" class="small">&larr; Back to section</a>

  <h3 class="mt-2">{{ $course->title ?? 'Course' }}</h3>
  <h5 class="text-muted">Q&amp;A · {{ $lesson->title }}</h5>

  @if(session('success')) <div class="alert alert-success mt-3">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger  mt-3">{{ session('error') }}</div>   @endif
  @if($errors->any())
    <div class="alert alert-danger mt-3">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  {{-- Ask a question --}}
  <div class="card mt-3 mb-4">
    <div class="card-body">
      <form method="POST" action="{{ route('student.qna.store', $lesson->id) }}">
        @csrf
        <div class="mb-2">
          <label class="form-label">Title</label>
          <input name="title" class="form-control" maxlength="255" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Question (optional)</label>
          <textarea name="body" class="form-control" rows="3" maxlength="5000"></textarea>
        </div>
        <button class="btn btn-primary">Post Question</button>
      </form>
    </div>
  </div>

  {{-- Thread list --}}
  @forelse($threads as $t)
    <div class="card mb-2">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <a class="fw-semibold" href="{{ route('student.qna.show', $t->id) }}">
            {{ $t->title }}
          </a>
          @if($t->body)
            <div class="text-muted small mt-1">{{ \Illuminate\Support\Str::limit($t->body, 140) }}</div>
          @endif
          <div class="small text-muted mt-1">
            by {{ displayUserLabel($t->user, $t->user_id) }} · {{ $t->created_at?->diffForHumans() }}
          </div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-{{ $t->status==='open' ? 'success' : 'secondary' }}">{{ ucfirst($t->status) }}</span>
          <a class="btn btn-sm btn-outline-primary" href="{{ route('student.qna.show', $t->id) }}#reply">
            Reply
          </a>
        </div>
      </div>
    </div>
  @empty
    <div class="text-muted">No questions yet.</div>
  @endforelse

  <div class="mt-3">
    {{ $threads->withQueryString()->links() }}
  </div>

</div>
@endsection

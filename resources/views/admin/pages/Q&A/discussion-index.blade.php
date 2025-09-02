@extends('admin.layouts.main')

@php
use Illuminate\Support\Str;

/**
 * Display name/label using ONLY the users table.
 * - Admin/Teacher => show role
 * - Student => first_name/last_name → name → username → email prefix → fallback
 */
if (!function_exists('displayUserLabel')) {
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

  @if($lesson)
    <a href="{{ route('student.sections.show', $lesson->section_id) }}" class="small">&larr; Back to section</a>
  @endif

  @if($course && $lesson)
    <h3 class="mt-2">{{ $course->title }}</h3>
    <h5 class="text-muted">Discussions · {{ $lesson->title }}</h5>
  @else
    <h4 class="mt-2">All Discussions</h4>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    {{-- Replied / Unreplied filters with counters --}}
    @php $current = request('filter'); @endphp
    <div class="btn-group" role="group" aria-label="Discussion filter">
      <a href="{{ route('admin.discussion.index') }}"
         class="btn btn-sm {{ $current ? 'btn-outline-secondary' : 'btn-secondary' }}">
         All ({{ $counts['all'] ?? 0 }})
      </a>
      <a href="{{ route('admin.discussion.index', ['filter' => 'unreplied']) }}"
         class="btn btn-sm {{ $current === 'unreplied' ? 'btn-secondary' : 'btn-outline-secondary' }}">
         Unreplied ({{ $counts['unreplied'] ?? 0 }})
      </a>
      <a href="{{ route('admin.discussion.index', ['filter' => 'replied']) }}"
         class="btn btn-sm {{ $current === 'replied' ? 'btn-secondary' : 'btn-outline-secondary' }}">
         Replied ({{ $counts['replied'] ?? 0 }})
      </a>
    </div>
  </div>

  @if(session('success')) <div class="alert alert-success mt-3">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger  mt-3">{{ session('error') }}</div>   @endif
  @if($errors->any())
    <div class="alert alert-danger mt-3">
      <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
  @endif

  @isset($lesson)
  <div class="card mt-3 mb-4">
    <div class="card-body">
      <form method="POST" action="{{ route('student.discussion.store', $lesson->id) }}">
        @csrf
        <div class="mb-2">
          <label class="form-label">Title</label>
          <input name="title" class="form-control" maxlength="255" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Message</label>
          <textarea name="body" class="form-control" rows="3"></textarea>
        </div>
        <button class="btn btn-primary">Start Thread</button>
      </form>
    </div>
  </div>
  @endisset

  @isset($threads)
    @forelse($threads as $t)
      <div class="card mb-2">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">
          <div>
            <a href="{{ route('student.discussion.show', $t->id) }}" class="fw-semibold">
              @if($t->is_pinned) 📌 @endif
              {{ $t->title }}
            </a>

            @if(!empty($t->body))
              <div class="text-muted small mt-1">{{ \Illuminate\Support\Str::limit($t->body, 160) }}</div>
            @endif

            <div class="small text-muted mt-1">
              by {{ displayUserLabel($t->user, $t->user_id) }} · {{ $t->created_at?->diffForHumans() }}
            </div>
          </div>

          <div class="text-end">
            {{-- Replied / Unreplied + replies count --}}
            @if(($t->replies_count ?? 0) > 0)
              <span class="badge bg-success">Replied</span>
            @else
              <span class="badge bg-danger">Unreplied</span>
            @endif
            <span class="ms-2 text-muted small">{{ $t->replies_count }} replies</span>

            <div class="mt-2">
              {{-- Open chat (reply box) --}}
              <a class="btn btn-sm btn-outline-primary me-1"
                 href="{{ route('student.discussion.show', $t->id) }}#reply">
                Reply
              </a>

              {{-- Pin / Unpin + Delete (only admin/teacher) --}}
              @if($canModerate)
                <form method="POST" action="{{ route('admin.discussion.pin', $t->id) }}" class="d-inline">
                  @csrf
                  <button class="btn btn-sm btn-outline-primary">{{ $t->is_pinned ? 'Unpin' : 'Pin' }}</button>
                </form>

                <form method="POST" action="{{ route('admin.discussion.delete', $t->id) }}" class="d-inline"
                      onsubmit="return confirm('Delete this discussion?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="text-muted">No discussions yet.</div>
    @endforelse

    <div class="mt-3">
      {{ $threads->withQueryString()->links() }}
    </div>
  @endisset
</div>
@endsection

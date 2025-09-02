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
@endphp

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0">Q&amp;A Threads</h4>

    @php $current = request('filter'); @endphp
    <div class="btn-group" role="group" aria-label="Q&A filter">
      <a href="{{ route('admin.qna.index') }}" class="btn btn-sm {{ $current ? 'btn-outline-secondary' : 'btn-secondary' }}">
        All ({{ $counts['all'] ?? 0 }})
      </a>
      <a href="{{ route('admin.qna.index', ['filter' => 'unreplied']) }}" class="btn btn-sm {{ $current==='unreplied' ? 'btn-secondary' : 'btn-outline-secondary' }}">
        Unreplied ({{ $counts['unreplied'] ?? 0 }})
      </a>
      <a href="{{ route('admin.qna.index', ['filter' => 'replied']) }}" class="btn btn-sm {{ $current==='replied' ? 'btn-secondary' : 'btn-outline-secondary' }}">
        Replied ({{ $counts['replied'] ?? 0 }})
      </a>
    </div>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  @forelse($threads as $t)
    <div class="card mb-2">
      <div class="card-body">
        <div class="d-flex justify-content-between flex-wrap gap-2">
          <div>
            <div class="fw-semibold">
              @if($t->is_pinned) 📌 @endif
              <a href="{{ route('student.qna.show', $t->id) }}">{{ $t->title }}</a>
            </div>

            @if(!empty($t->body))
              <div class="text-muted small mt-1">{{ \Illuminate\Support\Str::limit($t->body, 160) }}</div>
            @endif

            <div class="small text-muted mt-1">
              Course: {{ $t->lesson?->section?->course?->title ?? $t->course?->title ?? '—' }}
              · Lesson: {{ $t->lesson?->title ?? '—' }}
              · by {{ displayUserLabel($t->user, $t->user_id) }}
              · {{ optional($t->created_at)->diffForHumans() }}
            </div>
          </div>

          <div class="text-end">
            <span class="badge bg-{{ $t->status==='open' ? 'success':'secondary' }}">{{ ucfirst($t->status) }}</span>
            @if(($t->replies_count ?? 0) > 0)
              <span class="badge bg-success ms-1">Replied</span>
            @else
              <span class="badge bg-danger ms-1">Unreplied</span>
            @endif
            <span class="ms-2 text-muted small">{{ $t->replies_count }} replies</span>

            <div class="mt-2">
              <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('student.qna.show', $t->id) }}#reply">
                Reply
              </a>

              <form method="POST" action="{{ route('admin.qna.toggle', $t->id) }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-outline-secondary">
                  {{ $t->status==='open' ? 'Close' : 'Reopen' }}
                </button>
              </form>

              <form method="POST" action="{{ route('admin.qna.delete', $t->id) }}" class="d-inline"
                    onsubmit="return confirm('Delete thread?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="text-muted">No Q&amp;A threads yet.</div>
  @endforelse

  <div class="mt-3">
    {{ $threads->withQueryString()->links() }}
  </div>

</div>
@endsection

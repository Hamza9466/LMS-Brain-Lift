@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">Quiz Attempts — {{ $quiz->title }}</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 rounded-3">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                    <tr>
                        <th class="px-3 py-3">#</th>
                        <th class="py-3">User</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Score</th>
                        <th class="py-3">Percent</th>
                        <th class="py-3">Passed</th>
                        <th class="py-3">Started</th>
                        <th class="py-3">Submitted</th>
                        <th class="text-center py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $a)
                        <tr class="border-bottom">
                            <td class="px-3">{{ $a->id }}</td>
                            <td>{{ $a->user->email ?? 'User #'.$a->user_id }}</td>
                            <td>
                                <span class="badge bg-{{ $a->status === 'submitted' ? 'success' : 'warning' }}">
                                    {{ ucfirst($a->status) }}
                                </span>
                            </td>
                            <td>{{ rtrim(rtrim((string)($a->score ?? 0),'0'),'.') }}</td>
                            <td>{{ rtrim(rtrim((string)($a->percentage ?? 0),'0'),'.') }}%</td>
                            <td class="text-center">
                                {!! $a->is_passed 
                                    ? '<i class="fas fa-check text-success"></i>' 
                                    : '<i class="fas fa-times text-danger"></i>' !!}
                            </td>
                            <td>{{ $a->started_at ?? '—' }}</td>
                            <td>{{ $a->submitted_at ?? '—' }}</td>
                            <td class="text-center">
                                <form method="POST"
                                      action="{{ route('admin.quizzes.attempts.resetUser', [$quiz->id, $a->user_id]) }}"
                                      onsubmit="return confirm('Clear ALL attempts for this student?');"
                                      class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-warning" title="Reset Attempts">
                                        <i class="fas fa-undo-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i> No attempts yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white text-center">
            {{ $attempts->links() }}
        </div>
    </div>
</div>
@endsection

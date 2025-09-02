@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Attempts — {{ $quiz->title }}</h4>
    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-light">Back</a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th><th>User</th><th>Score</th><th>%</th><th>Status</th><th>Started</th><th>Submitted</th><th></th>
          </tr>
        </thead>
        <tbody>
        @forelse($attempts as $a)
          <tr>
            <td>{{ $a->id }}</td>
            <td>{{ $a->user->email ?? 'User #'.$a->user_id }}</td>
            <td>{{ $a->score }}</td>
            <td>{{ $a->percentage }}%</td>
            <td>{!! $a->is_passed ? '<span class="badge bg-success">Pass</span>' : '<span class="badge bg-danger">Fail</span>' !!}</td>
            <td>{{ $a->started_at?->format('d M Y H:i') }}</td>
            <td>{{ $a->submitted_at?->format('d M Y H:i') }}</td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.attempts.show',$a->id) }}">View</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">No attempts yet.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white">{{ $attempts->links() }}</div>
  </div>
</div>
@endsection

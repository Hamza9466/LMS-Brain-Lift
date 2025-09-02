@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">
            <i class="fas fa-question-circle me-2"></i> Quizzes
        </h4>
        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary" title="Add Quiz">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 rounded-3">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color:#fff;">
                    <tr>
                        <th class="px-3 py-3">#</th>
                        <th class="py-3">Title</th>
                        <th class="py-3">Course / Section</th>
                        <th class="text-center py-3">Questions</th>
                        <th class="text-center py-3">Attempts</th>
                        <th class="text-center py-3">Pass %</th>
                        <th class="text-center py-3">Status</th>
                        <th class="text-end py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $q)
                        <tr class="border-bottom">
                            <td class="px-3">{{ $q->id }}</td>
                            <td class="fw-semibold">{{ $q->title }}</td>
                            <td>
                                <div>{{ $q->course->title ?? '—' }}</div>
                                <div class="text-muted small">
                                    <i class="fas fa-layer-group me-1"></i>{{ $q->section->title ?? 'No section' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <i class="fas fa-list-ol me-1 text-secondary"></i>{{ $q->questions_count }}
                            </td>
                            <td class="text-center">
                                <i class="fas fa-users me-1 text-secondary"></i>{{ $q->attempts_count }}
                            </td>
                            <td class="text-center">{{ $q->pass_percentage }}%</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.quizzes.toggle',$q->id) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm {{ $q->is_published ? 'btn-success' : 'btn-outline-secondary' }}" title="{{ $q->is_published ? 'Published' : 'Draft' }}">
                                        <i class="fas {{ $q->is_published ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-info me-1" href="{{ route('admin.quizzes.questions.index',$q->id) }}" title="Questions">
                                    <i class="fas fa-list"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-secondary me-1" href="{{ route('admin.quizzes.attempts.index',$q->id) }}" title="Attempts">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('admin.quizzes.edit',$q->id) }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form class="d-inline" method="POST" action="{{ route('admin.quizzes.destroy',$q->id) }}" onsubmit="return confirm('Delete quiz?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-1"></i> No quizzes yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $quizzes->links() }}
        </div>
    </div>
</div>
@endsection

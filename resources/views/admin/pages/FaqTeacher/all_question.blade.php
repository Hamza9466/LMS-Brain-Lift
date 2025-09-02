@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">Teacher FAQs</h4>
        <a href="{{ route('admin.faq-teachers.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Add Question
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                    <tr class="text-center">
                        <th class="px-3 py-3" width="5%">#</th>
                        <th class="py-3 text-start">Question</th>
                        <th class="py-3" width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $faq->question }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.faq-teachers.edit', $faq) }}" class="text-warning me-2" title="Edit">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>
                                <form action="{{ route('admin.faq-teachers.destroy', $faq) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Delete"
                                            onclick="return confirm('Delete this FAQ?')">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No FAQs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($faqs instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="d-flex justify-content-center mt-3">
                {{ $faqs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

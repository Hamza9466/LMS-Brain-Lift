@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">📩 Contact Messages</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                    <tr class="text-center">
                        <th class="px-3 py-3" width="5%">#</th>
                        <th class="py-3" width="20%">Name</th>
                        <th class="py-3" width="20%">Email</th>
                        <th class="py-3">Message</th>
                        <th class="py-3" width="15%">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $c)
                        <tr>
                            <td class="text-center">{{ $c->id }}</td>
                            <td>{{ $c->name }}</td>
                            <td>
                                <a href="mailto:{{ $c->email }}" class="text-decoration-none">
                                    {{ $c->email }}
                                </a>
                            </td>
                            <td style="white-space: pre-wrap; max-width: 400px;">
                                {{ Str::limit($c->message, 120) }}
                            </td>
                            <td class="text-center">
                                {{ $c->created_at->format('d M Y, H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i> No messages yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($contacts instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="d-flex justify-content-center mt-3">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

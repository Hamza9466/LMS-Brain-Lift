@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 fw-bold">User Details</h4>

    @php
        use Illuminate\Support\Str;

        // Safe display name from users table only
        $displayName = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
        if ($displayName === '' && !empty($user->name)) {
            $displayName = $user->name;
        }
        if ($displayName === '' && !empty($user->email)) {
            $displayName = Str::before($user->email, '@');
        }
        if ($displayName === '') {
            $displayName = 'N/A';
        }

        // Badge class for role
        $roleBadge = match($user->role) {
            'admin'   => 'bg-danger',
            'student' => 'bg-secondary',
            default   => 'bg-light text-dark'
        };
    @endphp

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Name:</strong></p>
                    <p class="text-muted mb-3">{{ $displayName }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1"><strong>Email:</strong></p>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1"><strong>Role:</strong></p>
                    <p class="mb-3">
                        <span class="badge {{ $roleBadge }}">{{ ucfirst($user->role ?? 'N/A') }}</span>
                    </p>
                </div>

                @if(!empty($user->email_verified_at))
                <div class="col-md-6">
                    <p class="mb-1"><strong>Email Verified:</strong></p>
                    <p class="text-muted mb-3">{{ \Carbon\Carbon::parse($user->email_verified_at)->diffForHumans() }}</p>
                </div>
                @endif

                <div class="col-md-6">
                    <p class="mb-1"><strong>Created:</strong></p>
                    <p class="text-muted mb-3">{{ optional($user->created_at)->diffForHumans() }}</p>
                </div>

                <div class="col-md-6">
                    <p class="mb-1"><strong>Last Updated:</strong></p>
                    <p class="text-muted mb-3">{{ optional($user->updated_at)->diffForHumans() }}</p>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('admin.teachers.edit', $user->id) }}" class="btn btn-primary">Edit User</a>
                <form method="POST" action="{{ route('admin.teachers.destroy', $user->id) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this user?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

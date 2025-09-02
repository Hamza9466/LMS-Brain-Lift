@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 fw-bold text-primary">All Users</h4>

    {{-- Filter by role --}}
    <form method="GET" action="{{ route('admin.teachers.index') }}" class="mb-3">
        <div class="input-group" style="max-width: 300px;">
            <select name="role" class="form-select border-1 bg-white" onchange="this.form.submit()">
                <option value="">All Users</option>
                <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admins</option>
                <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Students</option>
            </select>
        </div>
    </form>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                        <tr>
                            <th class="px-3 py-3">#</th>
                            <th class="py-3">Name</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Created</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                // Prefer first/last name; fallback to 'name', then email username, else 'N/A'
                                $displayName = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
                                if ($displayName === '' && !empty($user->name)) {
                                    $displayName = $user->name;
                                }
                                if ($displayName === '' && !empty($user->email)) {
                                    $displayName = \Illuminate\Support\Str::before($user->email, '@');
                                }
                                if ($displayName === '') { $displayName = 'N/A'; }
                            @endphp
                            <tr class="border-bottom">
                                <td class="px-3">
                                    {{ ($users->firstItem() ?? 1) + $loop->index }}
                                </td>
                                <td>{{ $displayName }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge
                                        @if($user->role === 'admin') bg-danger
                                        @elseif($user->role === 'student') bg-secondary
                                        @else bg-light text-dark
                                        @endif">
                                        {{ ucfirst($user->role ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>{{ optional($user->created_at)->diffForHumans() }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.teachers.show', $user->id) }}"
                                       class="text-info me-2" title="View">
                                        <i class="fas fa-eye fa-lg"></i>
                                    </a>
                                    <a href="{{ route('admin.teachers.edit', $user->id) }}"
                                       class="text-warning me-2" title="Edit">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.teachers.destroy', $user->id) }}"
                                          class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-link text-danger p-0 m-0" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $users->appends(['role' => request('role')])->links() }}
    </div>
</div>
@endsection

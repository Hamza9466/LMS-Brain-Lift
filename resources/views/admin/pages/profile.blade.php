{{-- resources/views/admin/profile/show.blade.php --}}
@extends('admin.layouts.main')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    /** @var \App\Models\User|null $user */
    $user = $user ?? auth()->user();
    if (!$user) { abort(401); }

    // Role: prefer controller-provided, else from users table; clamp to admin|student
    $role = $roleFromController ?? strtolower((string)($user->role ?? 'student'));
    if (!in_array($role, ['admin','student'], true)) { $role = 'student'; }

    // Display name from users table (first/last -> name -> email prefix -> 'User')
    $displayName = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
    if ($displayName === '' && !empty($user->name)) {
        $displayName = $user->name;
    }
    if ($displayName === '' && !empty($user->email)) {
        $displayName = Str::before($user->email, '@');
    }
    if ($displayName === '') { $displayName = 'User'; }

    // Optional profile image from public disk or a full URL
    $img = null;
    if (!empty($user->profile_photo_path) && Storage::disk('public')->exists($user->profile_photo_path)) {
        $img = Storage::url($user->profile_photo_path);
    } elseif (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
        $img = Storage::url($user->profile_image);
    } elseif (!empty($user->avatar) && preg_match('#^https?://#i', $user->avatar)) {
        $img = $user->avatar;
    }
    // Fallback placeholder
   

    // Helpers
    $safe   = fn($v, $fallback = '—') => (isset($v) && $v !== '') ? $v : $fallback;
    $pretty = fn($dt) => $dt ? $dt->format('D d M Y, h:i a') : '—';
@endphp

<div class="dashboard-content">
    <div class="container">
        <h4 class="dashboard-title mb-3">My Profile</h4>

        <!-- Top Card -->
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                   
                    <div>
                        <span class="badge {{ $role === 'admin' ? 'bg-danger' : 'bg-secondary' }} fw-semibold">
                            {{ strtoupper($role) }}
                        </span>
                        <div class="fs-5 fw-bold mt-1">{{ $displayName }}</div>
                        <div class="text-muted">{{ $safe($user->email) }}</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small text-muted">Registration Date</div>
                        <div class="fw-semibold">{{ $pretty($user->created_at) }}</div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small text-muted">Last Updated</div>
                        <div class="fw-semibold">{{ $pretty($user->updated_at) }}</div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small text-muted">Email</div>
                        <div class="fw-semibold">{{ $safe($user->email) }}</div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small text-muted">Email Verified</div>
                        <div class="fw-semibold">
                            {{ $user->email_verified_at ? $pretty($user->email_verified_at) : 'Not verified' }}
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small text-muted">Role</div>
                        <div class="fw-semibold">{{ ucfirst($role) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core User Info (users table only) -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="small text-muted">First Name</div>
                        <div class="fw-semibold">{{ $safe($user->first_name) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Last Name</div>
                        <div class="fw-semibold">{{ $safe($user->last_name) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Display Name</div>
                        <div class="fw-semibold">{{ $displayName }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Optional: Edit button --}}
        {{-- <div class="mt-3">
            <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        </div> --}}
    </div>
</div>
@endsection

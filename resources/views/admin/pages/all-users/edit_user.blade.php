@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h2>{{ $edit ? 'Edit User' : 'Add User' }}</h2>

    {{-- Flash + validation --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Role selector (create only) --}}
    @if (!$edit)
        <form method="GET" action="{{ route('admin.teachers.create') }}" class="mb-4">
            <div class="input-group" style="max-width: 300px;">
                <select name="role" class="form-select bg-white border-1" onchange="this.form.submit()">
                    <option value="">Select Role</option>
                    <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
        </form>
    @endif

    {{-- Show form when role chosen (create) or editing --}}
    @if(request('role') || $edit)
        <form action="{{ $edit ? route('admin.teachers.update', $user->id) : route('admin.teachers.store') }}"
              method="POST">
            @csrf
            @if ($edit) @method('PUT') @endif

            {{-- Role --}}
            @if($edit)
                <div class="mb-3" style="max-width:300px;">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select bg-white border-1" required>
                        <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>
            @else
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif

            <div class="row">
                {{-- First Name --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text"
                           name="first_name"
                           class="form-control bg-white border-1 @error('first_name') is-invalid @enderror"
                           value="{{ old('first_name', $edit ? $user->first_name : '') }}"
                           required>
                    @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Last Name --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text"
                           name="last_name"
                           class="form-control bg-white border-1 @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name', $edit ? $user->last_name : '') }}"
                           required>
                    @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control bg-white border-1 @error('email') is-invalid @enderror"
                           value="{{ old('email', $edit ? $user->email : '') }}"
                           required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Password (create) --}}
                @unless($edit)
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password"
                               name="password"
                               class="form-control bg-white border-1 @error('password') is-invalid @enderror"
                               required>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3 ">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control bg-white border-1" required>
                    </div>
                @endunless

                {{-- Password (edit: optional) --}}
                @if($edit)
                    <div class="col-md-6 mb-3 bg-white border-1">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Min 6 characters.</small>
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-success">{{ $edit ? 'Update' : 'Save' }}</button>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    @endif
</div>
@endsection

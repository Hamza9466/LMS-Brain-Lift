<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * List users with optional role filter (admin|student)
     */
    public function index(Request $request)
    {
        $role = $request->get('role');

        $query = User::query();

        if (!empty($role)) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(10);

        return view('admin.pages.all-users.all_users', compact('users', 'role'));
    }

    /**
     * Show single user (users table only)
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.all-users.view_user', compact('user'));
    }

    /**
     * Edit form (users table only)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.all-users.edit_user', [
            'edit' => true,
            'user' => $user,
        ]);
    }

    /**
     * Update user (first/last/email/password/role)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => ['nullable','string','max:50'],
            'last_name'  => ['nullable','string','max:50'],
            'email'      => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'role'       => ['required', Rule::in(['admin','student'])],
            'password'   => ['nullable','min:6'],
        ]);

        $data = [
            'first_name' => $request->first_name ?? $user->first_name,
            'last_name'  => $request->last_name  ?? $user->last_name,
            'email'      => $request->email,
            'role'       => $request->role,
        ];

        if ($request->filled('password')) {
            // if you have casts ['password' => 'hashed'], just assign raw
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.teachers.index')->with('success', 'User updated successfully.');
    }

    /**
     * Create form (users table only)
     */
    public function create(Request $request)
    {
        $role = $request->query('role'); // optional preselect
        return view('admin.pages.all-users.add_user', [
            'edit' => false,
            'role' => $role,
            'user' => null
        ]);
    }

    /**
     * Store user (users table only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'role'       => ['required', Rule::in(['admin','student'])],
            'email'      => ['required','email','unique:users,email'],
            'password'   => ['required','min:6'],
            'first_name' => ['required','string','max:50'],
            'last_name'  => ['required','string','max:50'],
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'role'       => $request->role,
            // with casts ['password' => 'hashed'] you can assign plain:
            'password'   => $request->password,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'User created successfully.');
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'User deleted successfully.');
    }
}
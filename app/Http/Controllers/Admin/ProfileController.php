<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // If not already, protect this controller with auth middleware in routes or constructor.
    // public function __construct() { $this->middleware('auth'); }

    /**
     * Optional alias that just reuses show()
     */
    public function profile(Request $request)
    {
        return $this->show($request);
    }

    /**
     * Show the logged-in user's profile (users table only).
     */
    public function show(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            abort(401); // or redirect()->route('login')
        }

        // Only two roles supported: admin | student (default to student if unknown)
        $role = strtolower((string) ($user->role ?? 'student'));
        if (!in_array($role, ['admin', 'student'], true)) {
            $role = 'student';
        }

        // Pass the role explicitly so Blade doesn’t try to derive it
        $roleFromController = $role;

        return view('admin.pages.profile', compact('user', 'roleFromController'));
    }
}
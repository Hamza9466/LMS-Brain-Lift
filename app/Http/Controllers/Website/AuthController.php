<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register → redirect to Sign In (do NOT auto-login)
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => $request->password, // cast 'hashed' in User model
            'role'       => 'student',
        ]);

        // go to login page after successful registration
        return redirect()->route('login')->with('success', 'Account created! Please sign in.');
    }

    // Login → redirect to Dashboard
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = (bool) $request->remember;

        if (Auth::attempt($request->only('email','password'), $remember)) {
            $request->session()->regenerate();

            // send to /admin/dashboard (named 'dashboard')
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login successful!');
        }

        return back()->with('error', 'Invalid credentials!');
    }

    public function showRegister() { return view('website.pages.sign-up'); }
    public function showLogin()    { return view('website.pages.sign-in'); }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$params)
    {
        // Parse params: roles (comma/pipe separated) + optional guard:xyz
        $roles = [];
        $guard = null;

        foreach ($params as $p) {
            if (str_starts_with($p, 'guard:')) {
                $guard = substr($p, 6);
            } else {
                // allow both role:admin,student and role:admin|student
                $roles = array_merge($roles, preg_split('/[|,]/', $p, -1, PREG_SPLIT_NO_EMPTY));
            }
        }

        $auth = $guard ? Auth::guard($guard) : Auth::guard();

        // Not logged in
        if (! $auth->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login'); // make sure your route is named 'login'
        }

        // Role check (case-insensitive)
        $userRole = strtolower((string) $auth->user()->role);
        $roles = array_map('strtolower', $roles);

        if (! in_array($userRole, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
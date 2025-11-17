<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Use custom role field instead of Spatie's hasRole()
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // User doesn't have required role - redirect appropriately
        $currentRoute = $request->route()->getName();
        
        if ($user->role === 'admin' && $currentRoute !== 'admin.dashboard') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'student' && $currentRoute !== 'student.dashboard') {
            return redirect()->route('student.dashboard');
        }

        // If we're already on the correct dashboard but still failing role check,
        // show an error instead of redirecting
        abort(403, 'Unauthorized access.');
    }
}
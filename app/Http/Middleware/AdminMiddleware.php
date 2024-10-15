<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {
    public function handle($request, Closure $next) {

        // Check if the authenticated user is an admin
        if (Auth::user()->role === 'admin') {
            return $next($request); // User is an admin, allow access
        }

        // If not an admin, redirect or abort
        return redirect()->route('home')->with('error', 'Unauthorized access.');
        // Alternatively: return abort(403, 'Unauthorized access.');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAny {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {

        // Check if the user is authenticated
        if (Auth::guard('client')->check() or Auth::guard('coach')->check() or Auth::guard('admin')->check()) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}

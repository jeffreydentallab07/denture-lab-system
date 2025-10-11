<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('clinic')->check()) {
            return redirect()->route('login')->with('error', 'Please login as a clinic.');
        }

        return $next($request);
    }
}

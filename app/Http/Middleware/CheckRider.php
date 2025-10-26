<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRider
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('rider')) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

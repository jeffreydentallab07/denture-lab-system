<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTechnician
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('technician')) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

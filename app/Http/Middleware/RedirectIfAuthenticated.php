<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->redirectToDashboard();
            }
        }

        return $next($request);
    }

    /**
     * Redirect to appropriate dashboard based on user role
     */
    private function redirectToDashboard()
    {
        $user = Auth::user();

        // Check using Spatie's role methods
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('technician')) {
            return redirect()->route('technician.dashboard');
        }

        if ($user->hasRole('rider')) {
            return redirect()->route('rider.dashboard');
        }

        if ($user->hasRole('clinic')) {
            return redirect()->route('clinic.dashboard');
        }

        // Fallback for users without specific roles
        return redirect('/home');
    }
}

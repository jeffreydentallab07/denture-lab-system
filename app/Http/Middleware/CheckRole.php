<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has any of the allowed roles
        if (!in_array($user->role, $roles)) {
            // Redirect to user's own dashboard
            return $this->redirectToUserDashboard($user->role);
        }

        return $next($request);
    }

    /**
     * Redirect to appropriate dashboard based on user role
     */
    private function redirectToUserDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to access that page.');
            case 'staff':
                return redirect()->route('staff.dashboard')->with('error', 'You do not have permission to access that page.');
            case 'dentist':
                return redirect()->route('dentist.dashboard')->with('error', 'You do not have permission to access that page.');
            default:
                return redirect('/')->with('error', 'You do not have permission to access that page.');
        }
    }
}

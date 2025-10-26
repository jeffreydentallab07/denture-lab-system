<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        // If already authenticated, redirect to dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect to appropriate dashboard
            return $this->redirectToDashboard();
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Redirect to appropriate dashboard based on user role (using Spatie)
     */
    protected function redirectToDashboard()
    {
        $user = Auth::user();

        // Check roles using Spatie's hasRole method
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

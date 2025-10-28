<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Clinic;

class DualAuthenticationController extends Controller
{
    /**
     * Handle login for both Users and Clinics
     */
    public function login(Request $request)
    {
        $request->validate([
            'email_or_username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email_or_username', 'password');

        // Try to authenticate as User first
        $user = $this->authenticateUser($credentials);

        if ($user) {
            // Login as User
            Auth::guard('web')->login($user, $request->filled('remember'));

            // Redirect based on user role
            return $this->redirectBasedOnRole($user->role);
        }

        // If not found in users, try to authenticate as Clinic
        $clinic = $this->authenticateClinic($credentials);

        if ($clinic) {
            // Login as Clinic (using clinic guard)
            Auth::guard('clinic')->login($clinic, $request->filled('remember'));

            // Redirect to clinic dashboard
            return redirect()->intended('/clinic/dashboard');
        }

        // If neither found, return error
        return back()->withErrors([
            'email_or_username' => 'The provided credentials do not match our records.',
        ])->onlyInput('email_or_username');
    }

    /**
     * Authenticate User from users table
     */
    private function authenticateUser($credentials)
    {
        // Try to find user by email
        $user = User::where('email', $credentials['email_or_username'])->first();

        // If user found and password matches
        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Authenticate Clinic from clinics table
     */
    private function authenticateClinic($credentials)
    {
        // Try to find clinic by email OR username
        $clinic = Clinic::where('email', $credentials['email_or_username'])
            ->orWhere('username', $credentials['email_or_username'])
            ->first();

        // If clinic found and password matches
        if ($clinic && Hash::check($credentials['password'], $clinic->password)) {
            return $clinic;
        }

        return null;
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->intended('/admin/dashboard');
            case 'technician':
                return redirect()->intended('/technician/dashboard');
            case 'rider':
                return redirect()->intended('/rider/dashboard');
            case 'staff':
                return redirect()->intended('/staff/dashboard');
            default:
                return redirect()->intended('/dashboard');
        }
    }

    /**
     * Logout for both guards
     */
    public function logout(Request $request)
    {
        // Check which guard is authenticated and logout
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('clinic')->check()) {
            Auth::guard('clinic')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

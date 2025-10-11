<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Clinic;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Try logging in as a regular user (admin, technician, rider)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect based on role
            return $this->redirectBasedOnRole($user->role);
        }

        // If not found in users, try clinics table
        $clinic = Clinic::where('email', $request->email)->first();

        if ($clinic && Hash::check($request->password, $clinic->password)) {
            // Log in using clinic guard
            Auth::guard('clinic')->login($clinic);
            $request->session()->regenerate();

            return redirect()->route('clinic.dashboard')
                ->with('success', 'Welcome back, ' . $clinic->clinic_name);
        }

        // If both failed, return error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'technician':
                return redirect()->route('technician.dashboard');
            case 'rider':
                return redirect()->route('rider.dashboard');
            default:
                return redirect()->route('home');
        }
    }

    public function logout(Request $request)
    {
        // Check which guard is authenticated
        if (Auth::guard('clinic')->check()) {
            Auth::guard('clinic')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}

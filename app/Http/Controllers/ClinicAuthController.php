<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClinicAuthController extends Controller
{
    // Show signup form
    public function showSignup()
    {
        return view('clinic.auth.signup');
    }

    // Handle signup
    public function signup(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:clinics,username',
            'clinic_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clinics,email',
            'password' => 'required|string|min:6|confirmed',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('clinic_photos', 'public');
        }

        Clinic::create($validated);

        return redirect()->route('login')
            ->with('success', 'Clinic registered successfully! Please login.');
    }
}

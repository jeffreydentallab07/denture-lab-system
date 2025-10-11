<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClinicSettingsController extends Controller
{
    // Show clinic settings form
    public function edit()
    {
        $clinic = Auth::guard('clinic')->user();
        return view('clinic.settings', compact('clinic')); // Blade: clinic/settings.blade.php
    }

    // Update clinic settings
    public function update(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $validated = $request->validate([
            'clinic_name'    => 'required|string|max:255',
            'email'          => 'required|email|unique:clinics,email,' . $clinic->clinic_id . ',clinic_id',
            'contact_number' => 'nullable|string|max:20',
            'password'       => 'nullable|string|min:8|confirmed',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($clinic->profile_photo && Storage::disk('public')->exists('uploads/clinic_photos/' . $clinic->profile_photo)) {
                Storage::disk('public')->delete('uploads/clinic_photos/' . $clinic->profile_photo);
            }
            $path = $request->file('photo')->store('uploads/clinic_photos', 'public');
            $clinic->profile_photo = basename($path);
        }

        $clinic->clinic_name    = $validated['clinic_name'];
        $clinic->email          = $validated['email'];
        $clinic->contact_number = $validated['contact_number'] ?? $clinic->contact_number;

        if (!empty($validated['password'])) {
            $clinic->password = Hash::make($validated['password']);
        }

        $clinic->save();

        return redirect()->route('clinic.dashboard')->with('success', 'Clinic settings updated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use App\Helpers\NotificationHelper;
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
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clinics,email',
            'password' => 'required|string|min:6|confirmed',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Set approval status to pending
        $validated['approval_status'] = 'pending';

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('clinic_photos', 'public');
        }

        $clinic = Clinic::create($validated);

        // Notify all admins about new clinic registration
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationHelper::notifyUser(
                $admin->id,
                'clinic_registration',
                'New Clinic Registration',
                "New clinic '{$clinic->clinic_name}' has registered and is waiting for approval.",
                route('admin.clinics.pending'),
                $clinic->clinic_id
            );
        }

        return redirect(route('login') . '#login')
            ->with('success', 'Registration successful! Your account is pending approval. You will be notified once approved.');
    }
}

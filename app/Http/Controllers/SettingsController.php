<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Notification;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get notifications for the layout
        $notifications = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->count();

        // Determine which layout to use based on role
        $layout = match ($user->role) {
            'admin' => 'layouts.app',
            'technician' => 'layouts.technician',
            'rider' => 'layouts.rider',
            'clinic' => 'layouts.clinic',
            default => 'layouts.app'
        };

        return view('settings.index', compact('user', 'layout', 'notifications', 'notificationCount'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && file_exists(storage_path('app/public/' . $user->photo))) {
                unlink(storage_path('app/public/' . $user->photo));
            }

            // Store new photo
            $validated['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}

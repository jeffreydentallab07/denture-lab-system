<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseOrder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Dentist;

class ClinicController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $clinic = Auth::guard('clinic')->user();

        // Get statistics
        $totalCaseOrders = CaseOrder::where('clinic_id', $clinic->clinic_id)->count();
        $completedCases = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->where('status', 'completed')
            ->count();
        $pendingCases = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->whereIn('status', ['initial', 'in-progress'])
            ->count();

        // Recent appointments
        $recentAppointments = Appointment::whereHas('caseOrder', function ($query) use ($clinic) {
            $query->where('clinic_id', $clinic->clinic_id);
        })
            ->with(['caseOrder.patient', 'technician'])
            ->latest('schedule_datetime')
            ->take(5)
            ->get();

        // Total patients and dentists
        $totalPatients = Patient::where('clinic_id', $clinic->clinic_id)->count();
        $totalDentists = Dentist::where('clinic_id', $clinic->clinic_id)->count();

        return view('clinic.dashboard', compact(
            'totalCaseOrders',
            'completedCases',
            'pendingCases',
            'recentAppointments',
            'totalPatients',
            'totalDentists'
        ));
    }

    // Settings
    public function settings()
    {
        $clinic = Auth::guard('clinic')->user();
        return view('clinic.clinic_settings.index', compact('clinic'));
    }

    // Update Settings
    public function updateSettings(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clinics,email,' . $clinic->clinic_id . ',clinic_id',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle password update
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($clinic->profile_photo) {
                Storage::disk('public')->delete($clinic->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('clinic_photos', 'public');
        }

        $clinic->update($validated);

        return redirect()->route('clinic.settings')
            ->with('success', 'Settings updated successfully.');
    }
}

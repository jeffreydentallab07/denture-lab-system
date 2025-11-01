<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicsController extends Controller
{
    /**
     * Show pending clinic registrations
     */
    public function pending()
    {
        $pendingClinics = Clinic::where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalPending = Clinic::where('approval_status', 'pending')->count();
        $recentRegistrations = Clinic::where('approval_status', 'pending')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return view('admin.clinics.pending', compact('pendingClinics', 'totalPending', 'recentRegistrations'));
    }

    /**
     * Show all clinics (approved, pending, rejected)
     */
    public function index()
    {
        $clinics = Clinic::withCount(['caseOrders', 'patients', 'dentists'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalClinics = Clinic::count();
        $activeClinics = Clinic::where('approval_status', 'approved')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        return view('admin.clinics.index', compact('clinics', 'totalClinics', 'activeClinics'));
    }

    /**
     * Show single clinic details
     */
    public function show($id)
    {
        $clinic = Clinic::with('approvedBy')->findOrFail($id);
        return view('admin.clinics.show', compact('clinic'));
    }

    /**
     * Approve clinic
     */
    public function approve($id)
    {
        $clinic = Clinic::findOrFail($id);

        if ($clinic->approval_status === 'approved') {
            return back()->with('error', 'Clinic is already approved.');
        }

        $clinic->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejection_reason' => null,
        ]);

        // Notify clinic about approval
        NotificationHelper::notifyClinic(
            $clinic->clinic_id,
            'account_approved',
            'Account Approved',
            'Congratulations! Your clinic account has been approved. You can now log in and start using our services.',
            route('clinic.dashboard'),
            null
        );

        return back()->with('success', "Clinic '{$clinic->clinic_name}' has been approved successfully!");
    }

    /**
     * Reject clinic
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $clinic = Clinic::findOrFail($id);

        if ($clinic->approval_status === 'rejected') {
            return back()->with('error', 'Clinic is already rejected.');
        }

        $clinic->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_at' => null,
            'approved_by' => Auth::id(),
        ]);

        // Notify clinic about rejection
        NotificationHelper::notifyClinic(
            $clinic->clinic_id,
            'account_rejected',
            'Account Rejected',
            'Your clinic registration has been rejected. Reason: ' . $validated['rejection_reason'],
            null,
            null
        );

        return back()->with('success', "Clinic '{$clinic->clinic_name}' has been rejected.");
    }

    /**
     * Delete clinic
     */
    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinicName = $clinic->clinic_name;
        $clinic->delete();

        return redirect()->route('admin.clinics.index')
            ->with('success', "Clinic '{$clinicName}' has been deleted successfully.");
    }
}

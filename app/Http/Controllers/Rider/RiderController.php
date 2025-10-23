<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Pickup;
use App\Models\Notification;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function dashboard()
    {
        $rider = Auth::user();

        // Get all pickups for this rider
        $pickups = Pickup::with(['caseOrder.clinic', 'caseOrder.patient'])
            ->where('rider_id', $rider->id)
            ->latest('pickup_date')
            ->get();

        // Statistics
        $totalPickups = $pickups->count();
        $pendingPickups = $pickups->where('status', 'pending')->count();
        $pickedUpCount = $pickups->where('status', 'picked-up')->count();

        // Today's pickups
        $todayPickups = $pickups->filter(function ($pickup) {
            return $pickup->pickup_date->isToday();
        });

        // Notifications
        $notifications = Notification::where('user_id', $rider->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $rider->id)
            ->where('read', false)
            ->count();

        return view('rider.dashboard', compact(
            'pickups',
            'totalPickups',
            'pendingPickups',
            'pickedUpCount',
            'todayPickups',
            'notifications',
            'notificationCount'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $pickup = Pickup::where('rider_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,picked-up',
        ]);

        // Update pickup status
        $pickup->update([
            'status' => $validated['status'],
            'picked_up_at' => $validated['status'] === 'picked-up' ? now() : $pickup->picked_up_at,
        ]);

        // When picked up, update case order status to 'for appointment'
        if ($validated['status'] === 'picked-up') {
            $pickup->caseOrder->update(['status' => 'for appointment']);

            // Notify all admins that case is ready for appointment
            NotificationHelper::notifyAdmins(
                'case_ready_for_appointment',
                'Case Ready for Appointment',
                "Case order CASE-" . str_pad($pickup->case_order_id, 5, '0', STR_PAD_LEFT) . " from " . $pickup->caseOrder->clinic->clinic_name . " has been picked up and is ready for appointment scheduling.",
                route('admin.case-orders.show', $pickup->case_order_id),
                $pickup->case_order_id
            );

            // Notify clinic that case has been picked up
            NotificationHelper::notifyClinic(
                $pickup->caseOrder->clinic_id,
                'case_picked_up',
                'Case Picked Up',
                "Your case order CASE-" . str_pad($pickup->case_order_id, 5, '0', STR_PAD_LEFT) . " has been successfully picked up by our rider and is on its way to our lab.",
                route('clinic.notifications.index'),
                $pickup->case_order_id
            );
        }

        return redirect()->route('rider.dashboard')
            ->with('success', 'Pickup status updated successfully. Case order is now ready for appointment.');
    }
}

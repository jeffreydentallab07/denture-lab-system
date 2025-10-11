<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;

class DeliveriesController extends Controller
{
    public function index()
    {
        $rider = Auth::user();

        // Get all deliveries for this rider
        $deliveries = Delivery::with(['appointment.caseOrder.clinic', 'appointment.caseOrder.patient'])
            ->where('rider_id', $rider->id)
            ->orderBy('delivery_date', 'desc')
            ->paginate(15);

        // Stats
        $totalDeliveries = Delivery::where('rider_id', $rider->id)->count();
        $pendingDeliveries = Delivery::where('rider_id', $rider->id)->where('delivery_status', 'ready to deliver')->count();
        $inTransit = Delivery::where('rider_id', $rider->id)->where('delivery_status', 'in transit')->count();
        $completedDeliveries = Delivery::where('rider_id', $rider->id)->where('delivery_status', 'delivered')->count();

        // Today's deliveries
        $todayDeliveries = Delivery::with(['appointment.caseOrder.clinic'])
            ->where('rider_id', $rider->id)
            ->whereDate('delivery_date', today())
            ->orderBy('delivery_date', 'asc')
            ->get();

        // Notifications
        $notifications = Notification::where('user_id', $rider->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $rider->id)
            ->where('read', false)
            ->count();

        return view('rider.deliveries.index', compact(
            'deliveries',
            'totalDeliveries',
            'pendingDeliveries',
            'inTransit',
            'completedDeliveries',
            'todayDeliveries',
            'notifications',
            'notificationCount'
        ));
    }

    public function show($id)
    {
        $rider = Auth::user();

        $delivery = Delivery::with(['appointment.caseOrder.clinic', 'appointment.caseOrder.patient'])
            ->where('rider_id', $rider->id)
            ->findOrFail($id);

        // Notifications
        $notifications = Notification::where('user_id', $rider->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $rider->id)
            ->where('read', false)
            ->count();

        return view('rider.deliveries.show', compact('delivery', 'notifications', 'notificationCount'));
    }

    public function updateStatus(Request $request, $id)
    {
        $rider = Auth::user();
        $delivery = Delivery::where('rider_id', $rider->id)->findOrFail($id);

        $validated = $request->validate([
            'delivery_status' => 'required|in:ready to deliver,in transit,delivered',
        ]);

        $oldStatus = $delivery->delivery_status;

        // Update delivered_at timestamp when status changes to delivered
        if ($validated['delivery_status'] === 'delivered' && $oldStatus !== 'delivered') {
            $validated['delivered_at'] = now();
        }

        $delivery->update($validated);

        // Notify clinic when status changes
        if ($oldStatus !== $validated['delivery_status']) {
            $statusMessage = '';
            switch ($validated['delivery_status']) {
                case 'in transit':
                    $statusMessage = 'Your order is now in transit and will arrive soon.';
                    break;
                case 'delivered':
                    $statusMessage = 'Your order has been successfully delivered. Thank you for your business!';
                    break;
            }

            if ($statusMessage) {
                NotificationHelper::notifyClinic(
                    $delivery->appointment->caseOrder->clinic_id,
                    'delivery_status_update',
                    'Delivery Status Update',
                    "Case order CASE-" . str_pad($delivery->appointment->case_order_id, 5, '0', STR_PAD_LEFT) . ": " . $statusMessage,
                    route('clinic.appointments.show', $delivery->appointment_id),
                    $delivery->delivery_id
                );
            }
        }

        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }
}

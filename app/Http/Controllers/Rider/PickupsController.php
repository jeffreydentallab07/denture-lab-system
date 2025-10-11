<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Pickup;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class PickupsController extends Controller
{
    public function index()
    {
        $rider = Auth::user();

        // Get all pickups for this rider
        $pickups = Pickup::with(['caseOrder.clinic', 'caseOrder.patient'])
            ->where('rider_id', $rider->id)
            ->orderBy('pickup_date', 'desc')
            ->paginate(15);

        // Stats
        $totalPickups = Pickup::where('rider_id', $rider->id)->count();
        $pendingPickups = Pickup::where('rider_id', $rider->id)->where('status', 'pending')->count();
        $pickedUpCount = Pickup::where('rider_id', $rider->id)->where('status', 'picked-up')->count();

        // Today's pickups
        $todayPickups = Pickup::with(['caseOrder.clinic', 'caseOrder.patient'])
            ->where('rider_id', $rider->id)
            ->whereDate('pickup_date', today())
            ->orderBy('pickup_date', 'asc')
            ->get();

        // Upcoming pickups (next 7 days)
        $upcomingPickups = Pickup::with(['caseOrder.clinic', 'caseOrder.patient'])
            ->where('rider_id', $rider->id)
            ->where('status', 'pending')
            ->whereBetween('pickup_date', [today()->addDay(), today()->addDays(7)])
            ->orderBy('pickup_date', 'asc')
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

        return view('rider.pickups.index', compact(
            'pickups',
            'totalPickups',
            'pendingPickups',
            'pickedUpCount',
            'todayPickups',
            'upcomingPickups',
            'notifications',
            'notificationCount'
        ));
    }

    public function show($id)
    {
        $rider = Auth::user();

        $pickup = Pickup::with(['caseOrder.clinic', 'caseOrder.patient'])
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

        return view('rider.pickups.show', compact('pickup', 'notifications', 'notificationCount'));
    }
}

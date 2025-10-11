<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\ClinicNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $clinic = Auth::guard('clinic')->user();

        $allNotifications = ClinicNotification::where('clinic_id', $clinic->clinic_id)
            ->latest()
            ->paginate(20);

        // For layout
        $notifications = ClinicNotification::where('clinic_id', $clinic->clinic_id)
            ->where('read', 0)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = ClinicNotification::where('clinic_id', $clinic->clinic_id)
            ->where('read', 0)
            ->count();

        return view('clinic.notifications.index', compact('allNotifications', 'notifications', 'notificationCount'));
    }

    public function markAsRead($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $notification = ClinicNotification::where('clinic_id', $clinic->clinic_id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $clinic = Auth::guard('clinic')->user();

        ClinicNotification::where('clinic_id', $clinic->clinic_id)
            ->where('read', 0)
            ->update(['read' => 1]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}

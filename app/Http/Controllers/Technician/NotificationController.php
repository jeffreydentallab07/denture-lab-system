<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $technician = Auth::user();

        $allNotifications = Notification::where('user_id', $technician->id)
            ->latest()
            ->paginate(20);

        // For layout
        $notifications = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->count();

        return view('technician.notifications.index', compact('allNotifications', 'notifications', 'notificationCount'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\CaseOrder;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guard = 'admin';

        if (!$user) {
            if (Auth::guard('clinic')->check()) {
                $user = Auth::guard('clinic')->user();
                $guard = 'clinic';
            } elseif (Auth::guard('technician')->check()) {
                $user = Auth::guard('technician')->user();
                $guard = 'technician';
            } elseif (Auth::guard('rider')->check()) {
                $user = Auth::guard('rider')->user();
                $guard = 'rider';
            } else {
                abort(403, 'Unauthorized');
            }
        }

        // --- Notifications ---
        // New case orders
        $newCaseOrders = CaseOrder::where('status', 'new')->get();

        // Finished appointments
        $finishedAppointments = Appointment::where('work_status', 'finished')->get();

        // Merge notifications
        $notifications = $newCaseOrders->map(function ($order) {
            return [
                'type' => 'New Case Order',
                'message' => "Case #{$order->id} created",
                'link' => route('case-orders.show', $order->id)
            ];
        })->merge(
            $finishedAppointments->map(function ($appointment) {
                return [
                    'type' => 'Appointment Finished',
                    'message' => "Appointment #{$appointment->appointment_id} is finished",
                    'link' => route('appointments.show', $appointment->appointment_id)
                ];
            })
        );

        $notificationCount = $notifications->count();

        return view('dashboard.index', [
            'user' => $user,
            'role' => ucfirst($guard),
            'notifications' => $notifications,
            'notificationCount' => $notificationCount
        ]);
    }
}

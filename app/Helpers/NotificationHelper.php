<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\ClinicNotification;
use App\Models\User;
use App\Models\Clinic;

class NotificationHelper
{
    /**
     * Create notification for all admins
     */
    public static function notifyAdmins($type, $title, $message, $link = null, $referenceId = null)
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'reference_id' => $referenceId,
            ]);
        }
    }

    /**
     * Create notification for specific user
     */
    public static function notifyUser($userId, $type, $title, $message, $link = null, $referenceId = null)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'reference_id' => $referenceId,
        ]);
    }

    /**
     * Create notification for specific clinic
     */
    public static function notifyClinic($clinicId, $type, $title, $message, $link = null, $referenceId = null)
    {
        ClinicNotification::create([
            'clinic_id' => $clinicId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'reference_id' => $referenceId,
            'read' => 0,
        ]);
    }
}

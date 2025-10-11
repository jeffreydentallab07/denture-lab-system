<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\ClinicNotification;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share notifications with admin/user views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $notifications = Notification::where('user_id', Auth::id())
                    ->where('read', false)
                    ->latest()
                    ->limit(5)
                    ->get();

                $notificationCount = $notifications->count();

                $view->with('notifications', $notifications);
                $view->with('notificationCount', $notificationCount);
            }
        });

        // Share notifications with clinic views
        View::composer('*', function ($view) {
            if (Auth::guard('clinic')->check()) {
                $clinicNotifications = ClinicNotification::where('clinic_id', Auth::guard('clinic')->user()->clinic_id)
                    ->where('read', false)
                    ->latest()
                    ->limit(5)
                    ->get();

                $notificationCount = $clinicNotifications->count();

                $view->with('clinicNotifications', $clinicNotifications);
                $view->with('notificationCount', $notificationCount);
            }
        });
    }
}

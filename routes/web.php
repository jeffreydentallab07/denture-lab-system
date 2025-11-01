<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// =====================================================
// CONTROLLERS
// =====================================================

use App\Http\Controllers\NotificationController;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClinicAuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\DualAuthenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Models\User;


// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CaseOrdersController as AdminCaseOrdersController;
use App\Http\Controllers\Admin\AppointmentsController as AdminAppointmentsController;
use App\Http\Controllers\Admin\MaterialsController as AdminMaterialsController;
use App\Http\Controllers\Admin\ClinicsController as AdminClinicsController;
use App\Http\Controllers\Admin\DentistsController as AdminDentistsController;
use App\Http\Controllers\Admin\PatientsController as AdminPatientsController;
use App\Http\Controllers\Admin\TechniciansController as AdminTechniciansController;
use App\Http\Controllers\Admin\RidersController as AdminRidersController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\BillingsController as AdminBillingsController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;

// Clinic Controllers
use App\Http\Controllers\Clinic\ClinicController;
use App\Http\Controllers\Clinic\CaseOrdersController as ClinicCaseOrdersController;
use App\Http\Controllers\Clinic\DentistsController as ClinicDentistsController;
use App\Http\Controllers\Clinic\PatientsController as ClinicPatientsController;
use App\Http\Controllers\Clinic\AppointmentsController as ClinicAppointmentsController;
use App\Http\Controllers\Clinic\BillingController as ClinicBillingController;
use App\Http\Controllers\Clinic\CaseOrderReviewController;
use App\Http\Controllers\Clinic\NotificationController as ClinicNotificationController;

// Technician Controllers
use App\Http\Controllers\Technician\TechnicianController;
use App\Http\Controllers\Technician\NotificationController as TechnicianNotificationController;

// Rider Controllers
use App\Http\Controllers\Rider\RiderController;
use App\Http\Controllers\Rider\PickupsController;
use App\Http\Controllers\Rider\DeliveriesController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/home', fn() => view('landing'))->name('home');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [DualAuthenticationController::class, 'login']);
});

Route::post('/logout', [DualAuthenticationController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::get('password/reset', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('password.request')->middleware('guest');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email')->middleware('guest');
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset')->middleware('guest');
Route::post('password/reset', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update')->middleware('guest');
/*
|--------------------------------------------------------------------------
| CLINIC SIGNUP (Public)
|--------------------------------------------------------------------------
*/

Route::post('/clinic/signup', [ClinicAuthController::class, 'signup'])->name('clinic.register');

/*
|--------------------------------------------------------------------------
| UNIVERSAL AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Laboratory Management)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Case Orders
    Route::get('/case-orders', [AdminCaseOrdersController::class, 'index'])->name('case-orders.index');
    Route::get('/case-orders/{id}', [AdminCaseOrdersController::class, 'show'])->name('case-orders.show');
    Route::delete('/case-orders/{id}', [AdminCaseOrdersController::class, 'destroy'])->name('case-orders.destroy');

    // Pickup Management
    Route::get('/case-orders/{id}/create-pickup', [AdminCaseOrdersController::class, 'createPickup'])->name('case-orders.create-pickup');
    Route::post('/case-orders/{id}/store-pickup', [AdminCaseOrdersController::class, 'storePickup'])->name('case-orders.store-pickup');

    // Appointment Management
    Route::get('/case-orders/{id}/create-appointment', [AdminCaseOrdersController::class, 'createAppointment'])->name('case-orders.create-appointment');
    Route::post('/case-orders/{id}/store-appointment', [AdminCaseOrdersController::class, 'storeAppointment'])->name('case-orders.store-appointment');

    // Delivery Management
    Route::get('/case-orders/{id}/create-delivery', [AdminCaseOrdersController::class, 'createDelivery'])->name('case-orders.create-delivery');
    Route::post('/case-orders/{id}/store-delivery', [AdminCaseOrdersController::class, 'storeDelivery'])->name('case-orders.store-delivery');

    // Appointments Management
    Route::resource('appointments', AdminAppointmentsController::class);
    Route::put('/appointments/{appointment}/assign-technician', [AdminAppointmentsController::class, 'assignTechnician'])->name('appointments.assignTechnician');
    Route::put('/appointments/{appointment}/finish', [AdminAppointmentsController::class, 'markAsFinished'])->name('appointments.finish');
    Route::put('/appointments/{id}/reschedule', [AdminAppointmentsController::class, 'reschedule'])->name('appointments.reschedule');
    Route::put('/appointments/{id}/cancel', [AdminAppointmentsController::class, 'cancel'])->name('appointments.cancel');

    // Materials Management
    Route::resource('materials', AdminMaterialsController::class);

    // Clinics Management
    Route::get('clinics/pending', [AdminClinicsController::class, 'pending'])->name('clinics.pending');
    Route::get('clinics', [AdminClinicsController::class, 'index'])->name('clinics.index');
    Route::get('clinics/{clinic}', [AdminClinicsController::class, 'show'])->name('clinics.show');
    Route::post('clinics/{clinic}/approve', [AdminClinicsController::class, 'approve'])->name('clinics.approve');
    Route::post('clinics/{clinic}/reject', [AdminClinicsController::class, 'reject'])->name('clinics.reject');
    Route::delete('clinics/{clinic}', [AdminClinicsController::class, 'destroy'])->name('clinics.destroy');
    // Dentists Management
    Route::resource('dentists', AdminDentistsController::class);

    // Patients Management
    Route::resource('patients', AdminPatientsController::class);

    // Technicians Management
    Route::resource('technicians', AdminTechniciansController::class);

    // Riders Management
    Route::resource('riders', AdminRidersController::class);

    // Deliveries Management
    Route::resource('delivery', DeliveryController::class);

    // Billing Management
    Route::resource('billing', AdminBillingsController::class);
    Route::get('billing/{billing}/invoice', [AdminBillingsController::class, 'invoice'])
        ->name('billing.invoice');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportsController::class, 'index'])->name('index');
        Route::get('/export-pdf', [AdminReportsController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/print', [AdminReportsController::class, 'print'])->name('print');

        // Case Orders Detail
        Route::get('/case-orders/detail', [AdminReportsController::class, 'caseOrdersDetail'])->name('caseOrdersDetail');
        Route::get('/case-orders-detail/print', [AdminReportsController::class, 'printCaseOrdersDetail'])->name('printCaseOrdersDetail');
        Route::get('/case-orders/detail/pdf', [AdminReportsController::class, 'caseOrdersDetailPdf'])->name('caseOrdersDetailPdf');

        // Revenue Detail
        Route::get('/revenue/detail', [AdminReportsController::class, 'revenueDetail'])->name('revenueDetail');
        Route::get('/revenue-detail/print', [AdminReportsController::class, 'printRevenueDetail'])->name('printRevenueDetail');
        Route::get('/revenue/detail/pdf', [AdminReportsController::class, 'revenueDetailPdf'])->name('revenueDetailPdf');

        // Materials Detail
        Route::get('/materials/detail', [AdminReportsController::class, 'materialsDetail'])->name('materialsDetail');
        Route::get('/materials-detail/print', [AdminReportsController::class, 'printMaterialsDetail'])->name('printMaterialsDetail');
        Route::get('/materials/detail/pdf', [AdminReportsController::class, 'materialsDetailPdf'])->name('materialsDetailPdf');
    });
});

/*
|--------------------------------------------------------------------------
| CLINIC ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:clinic'])->prefix('clinic')->name('clinic.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [ClinicController::class, 'dashboard'])->name('dashboard');

    // Settings
    Route::get('/settings', [ClinicController::class, 'settings'])->name('settings');
    Route::put('/settings', [ClinicController::class, 'updateSettings'])->name('settings.update');

    // Dentists Management
    Route::resource('dentists', ClinicDentistsController::class);

    // Patients Management
    Route::resource('patients', ClinicPatientsController::class);

    // Billing Management
    Route::get('/billing', [ClinicBillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/{billing}', [ClinicBillingController::class, 'show'])->name('billing.show');

    // Case Orders Management
    Route::resource('case-orders', ClinicCaseOrdersController::class);

    // ✅ Case Order Review Routes
    Route::get('/case-orders/{id}/review', [CaseOrderReviewController::class, 'show'])
        ->name('case-orders.review');
    Route::post('/case-orders/{id}/approve', [CaseOrderReviewController::class, 'approve'])
        ->name('case-orders.approve');
    Route::post('/case-orders/{id}/request-adjustment', [CaseOrderReviewController::class, 'requestAdjustment'])
        ->name('case-orders.request-adjustment');

    // Appointments (View only)
    Route::resource('appointments', ClinicAppointmentsController::class);

    // Notifications
    Route::get('/notifications', [ClinicNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [ClinicNotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [ClinicNotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

/*
|--------------------------------------------------------------------------
| TECHNICIAN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.technician'])->prefix('technician')->name('technician.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [TechnicianController::class, 'dashboard'])->name('dashboard');

    // Appointments
    Route::get('/appointments', [TechnicianController::class, 'appointmentsIndex'])->name('appointments.index');
    Route::get('/appointments/{id}', [TechnicianController::class, 'showAppointment'])->name('appointments.show');
    Route::post('/appointments/{id}/update', [TechnicianController::class, 'updateAppointment'])->name('appointment.update');
    Route::post('/appointments/{id}/add-material', [TechnicianController::class, 'addMaterial'])->name('appointments.addMaterial');
    Route::delete('/appointments/{appointmentId}/materials/{usageId}', [TechnicianController::class, 'removeMaterial'])->name('appointments.removeMaterial');

    // Materials
    Route::get('/materials', [TechnicianController::class, 'materialsIndex'])->name('materials.index');

    // Work History
    Route::get('/work-history', [TechnicianController::class, 'workHistory'])->name('work-history');

    // Notifications
    Route::get('/notifications', [TechnicianNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [TechnicianNotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [TechnicianNotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

/*
|--------------------------------------------------------------------------
| RIDER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.rider'])->prefix('rider')->name('rider.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [RiderController::class, 'dashboard'])->name('dashboard');

    // Pickups
    Route::get('/pickups', [PickupsController::class, 'index'])->name('pickups.index');
    Route::get('/pickups/{id}', [PickupsController::class, 'show'])->name('pickups.show');
    Route::put('/pickups/{id}/update-status', [RiderController::class, 'updateStatus'])->name('pickups.updateStatus');

    // Deliveries
    Route::get('/deliveries', [DeliveriesController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/{id}', [DeliveriesController::class, 'show'])->name('deliveries.show');
    Route::put('/deliveries/{id}/update-status', [DeliveriesController::class, 'updateStatus'])->name('deliveries.updateStatus');
});

/*
|--------------------------------------------------------------------------
| ROOT REDIRECT
|--------------------------------------------------------------------------
*/


Route::get('/', function () {
    // Check clinic guard FIRST (to prioritize clinic if both somehow authenticated)
    if (Auth::guard('clinic')->check()) {
        return redirect()->route('clinic.dashboard');
    }

    if (Auth::guard('web')->check()) {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('technician')) {
            return redirect()->route('technician.dashboard');
        }

        if ($user->hasRole('rider')) {
            return redirect()->route('rider.dashboard');
        }

        return redirect()->route('home');
    }

    // No one authenticated
    return redirect()->route('login');
});

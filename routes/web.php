<?php

use Illuminate\Support\Facades\Route;

// =====================================================
// CONTROLLERS
// =====================================================

use App\Http\Controllers\NotificationController;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClinicAuthController;
use App\Http\Controllers\SettingsController;

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
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| CLINIC SIGNUP (Public)
|--------------------------------------------------------------------------
*/

Route::post('/clinic/signup', [ClinicAuthController::class, 'signup'])->name('clinic.signup.post');

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

    // Case Orders Management
    Route::resource('case-orders', AdminCaseOrdersController::class);
    Route::put('/case-orders/{caseOrder}/approve', [AdminCaseOrdersController::class, 'approve'])->name('case-orders.approve');
    Route::put('/case-orders/{id}/approve-and-assign', [AdminCaseOrdersController::class, 'approveAndAssign'])->name('case-orders.approve-and-assign');

    // Appointments Management
    Route::resource('appointments', AdminAppointmentsController::class);
    Route::put('/appointments/{appointment}/assign-technician', [AdminAppointmentsController::class, 'assignTechnician'])->name('appointments.assignTechnician');
    Route::put('/appointments/{appointment}/finish', [AdminAppointmentsController::class, 'markAsFinished'])->name('appointments.finish');
    Route::put('/appointments/{id}/reschedule', [AdminAppointmentsController::class, 'reschedule'])->name('appointments.reschedule');
    Route::put('/appointments/{id}/cancel', [AdminAppointmentsController::class, 'cancel'])->name('appointments.cancel');

    // Materials Management
    Route::resource('materials', AdminMaterialsController::class);

    // Clinics Management
    Route::resource('clinics', AdminClinicsController::class);

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
    Route::resource('new-case-orders', ClinicCaseOrdersController::class);

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
    if (auth()->check()) {
        $user = auth()->user();

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

    // Check clinic guard separately
    if (auth('clinic')->check()) {
        return redirect()->route('clinic.dashboard');
    }

    return redirect()->route('login');
});

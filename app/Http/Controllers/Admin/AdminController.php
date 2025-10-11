<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseOrder;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Material;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalClinics = Clinic::count();
        $totalCaseOrders = CaseOrder::count();
        $totalAppointments = Appointment::count();
        $totalTechnicians = User::where('role', 'technician')->count();
        $totalRiders = User::where('role', 'rider')->count();
        $totalMaterials = Material::count();

        // Recent appointments
        $recentAppointments = Appointment::with(['caseOrder.clinic', 'technician'])
            ->latest('schedule_datetime')
            ->take(10)
            ->get();

        // Pending case orders
        $pendingCaseOrders = CaseOrder::with(['clinic', 'patient'])
            ->where('status', 'initial')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalClinics',
            'totalCaseOrders',
            'totalAppointments',
            'totalTechnicians',
            'totalRiders',
            'totalMaterials',
            'recentAppointments',
            'pendingCaseOrders'
        ));
    }
}

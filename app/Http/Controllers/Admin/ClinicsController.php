<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;

class ClinicsController extends Controller
{
    public function index()
    {
        $clinics = Clinic::withCount(['caseOrders', 'patients', 'dentists'])
            ->latest()
            ->paginate(15);

        $totalClinics = Clinic::count();
        $activeClinics = Clinic::whereHas('caseOrders', function ($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        })->count();

        return view('admin.clinics.index', compact('clinics', 'totalClinics', 'activeClinics'));
    }

    public function show($id)
    {
        $clinic = Clinic::with(['caseOrders.patient', 'caseOrders.dentist', 'patients', 'dentists'])
            ->withCount(['caseOrders', 'patients', 'dentists'])
            ->findOrFail($id);

        // Get recent case orders
        $recentCaseOrders = $clinic->caseOrders()
            ->with(['patient', 'dentist'])
            ->latest()
            ->take(10)
            ->get();

        // Get case order statistics
        $totalCaseOrders = $clinic->caseOrders->count();
        $completedCaseOrders = $clinic->caseOrders->where('status', 'completed')->count();
        $pendingCaseOrders = $clinic->caseOrders->whereIn('status', ['initial', 'in-progress'])->count();

        return view('admin.clinics.show', compact(
            'clinic',
            'recentCaseOrders',
            'totalCaseOrders',
            'completedCaseOrders',
            'pendingCaseOrders'
        ));
    }
}

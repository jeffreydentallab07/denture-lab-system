<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {
        $clinic = Auth::guard('clinic')->user();

        $billings = Billing::with(['appointment.caseOrder'])
            ->whereHas('appointment.caseOrder', function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->clinic_id);
            })
            ->latest()
            ->paginate(15);

        return view('clinic.billing.index', compact('billings'));
    }

    public function show($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $billing = Billing::with(['appointment.caseOrder.patient', 'appointment.technician', 'appointment.materialUsages.material'])
            ->whereHas('appointment.caseOrder', function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->clinic_id);
            })
            ->findOrFail($id);

        return view('clinic.billing.show', compact('billing'));
    }
}

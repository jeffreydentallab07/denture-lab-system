<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use App\Models\CaseOrder;
use App\Models\Dentist;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseOrdersController extends Controller
{
    public function index()
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrders = CaseOrder::with(['dentist', 'patient'])
            ->where('clinic_id', $clinic->clinic_id)
            ->latest()
            ->paginate(15);

        $patients = Patient::where('clinic_id', $clinic->clinic_id)->get();
        $dentists = Dentist::where('clinic_id', $clinic->clinic_id)->get();
        $caseTypes = ['Crown', 'Bridge', 'Denture', 'Implant', 'Veneer'];
        $caseStatuses = ['initial', 'in-progress', 'completed', 'cancelled'];

        return view('clinic.new-case-orders.index', compact('caseOrders', 'clinic', 'patients', 'dentists', 'caseTypes', 'caseStatuses'));
    }

    public function store(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'case_type' => 'required|string|max:255',
            'status' => 'required|string|in:initial,in-progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Get patient's dentist
        $patient = Patient::findOrFail($validated['patient_id']);

        $caseOrder = CaseOrder::create([
            'clinic_id' => $clinic->clinic_id,
            'dentist_id' => $patient->dentist_id,
            'patient_id' => $validated['patient_id'],
            'case_type' => $validated['case_type'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        NotificationHelper::notifyAdmins(
            'case_order_pending',
            'New Case Order',
            "New case order from {$clinic->clinic_name} - {$caseOrder->case_type}",
            route('admin.case-orders.show', $caseOrder->co_id),
            $caseOrder->co_id
        );


        return redirect()->route('clinic.new-case-orders.index')
            ->with('success', 'Case order created successfully.');
    }

    public function update(Request $request, $id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'case_type' => 'required|string|max:255',
            'status' => 'required|string|in:initial,in-progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $caseOrder->update($validated);

        return redirect()->route('clinic.new-case-orders.index')
            ->with('success', 'Case order updated successfully.');
    }

    public function destroy($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        $caseOrder->delete();

        return redirect()->route('clinic.new-case-orders.index')
            ->with('success', 'Case order deleted successfully.');
    }
}

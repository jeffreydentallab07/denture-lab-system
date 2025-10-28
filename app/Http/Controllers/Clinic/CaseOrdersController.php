<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\CaseOrder;
use App\Models\Dentist;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;

class CaseOrdersController extends Controller
{
    /**
     * Display a listing of case orders
     */
    public function index(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $query = CaseOrder::with(['patient', 'dentist', 'latestAppointment.technician'])
            ->where('clinic_id', $clinic->clinic_id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('co_id', 'like', "%{$search}%")
                    ->orWhere('case_type', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $caseOrders = $query->latest()->paginate(15);

        return view('clinic.case-orders.index', compact('caseOrders'));
    }

    /**
     * Show the form for creating a new case order
     */
    public function create()
    {
        $clinic = Auth::guard('clinic')->user();

        $patients = Patient::where('clinic_id', $clinic->clinic_id)->get();
        $dentists = Dentist::where('clinic_id', $clinic->clinic_id)->get();

        return view('clinic.case-orders.create', compact('patients', 'dentists'));
    }

    /**
     * Store a newly created case order
     */
    public function store(Request $request)
    {
        $clinic = Auth::guard('clinic')->user();

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'dentist_id' => 'required|exists:dentists,dentist_id',
            'case_type' => 'required|in:denture,crown,bridge,implant,orthodontics',
            'notes' => 'nullable|string|max:1000'
        ]);

        $validated['clinic_id'] = $clinic->clinic_id;
        $validated['status'] = CaseOrder::STATUS_PENDING;

        $caseOrder = CaseOrder::create($validated);

        // Notify all admins
        NotificationHelper::notifyAdmins(
            'new_case_order',
            'New Case Order Created',
            "New case order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " from {$clinic->clinic_name}",
            route('admin.case-orders.show', $caseOrder->co_id),
            $caseOrder->co_id
        );

        return redirect()
            ->route('clinic.case-orders.show', $caseOrder->co_id)
            ->with('success', 'Case order created successfully!');
    }

    /**
     * Display the specified case order
     */
    public function show($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::with([
            'patient',
            'dentist',
            'appointments.technician',
            'appointments.delivery',
            'appointments.materialUsages.material',
            'appointments.billing',
            'pickup.rider',
            'latestDelivery'
        ])
            ->where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        return view('clinic.case-orders.show', compact('caseOrder'));
    }

    /**
     * Show the form for editing the case order
     */
    public function edit($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        // Only allow editing if pending or adjustment requested
        if (!in_array($caseOrder->status, [CaseOrder::STATUS_PENDING, CaseOrder::STATUS_ADJUSTMENT_REQUESTED])) {
            return redirect()
                ->route('clinic.case-orders.show', $id)
                ->with('error', 'This case order cannot be edited at this time.');
        }

        $patients = Patient::where('clinic_id', $clinic->clinic_id)->get();
        $dentists = Dentist::where('clinic_id', $clinic->clinic_id)->get();

        return view('clinic.case-orders.edit', compact('caseOrder', 'patients', 'dentists'));
    }

    /**
     * Update the specified case order
     */
    public function update(Request $request, $id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        // Only allow editing if pending or adjustment requested
        if (!in_array($caseOrder->status, [CaseOrder::STATUS_PENDING, CaseOrder::STATUS_ADJUSTMENT_REQUESTED])) {
            return redirect()
                ->route('clinic.case-orders.show', $id)
                ->with('error', 'This case order cannot be edited at this time.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'dentist_id' => 'required|exists:dentists,dentist_id',
            'case_type' => 'required|in:denture,crown,bridge,implant,orthodontics',
            'notes' => 'nullable|string|max:1000'
        ]);

        $caseOrder->update($validated);

        return redirect()
            ->route('clinic.case-orders.show', $id)
            ->with('success', 'Case order updated successfully!');
    }

    /**
     * Remove the specified case order
     */
    public function destroy($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        // Only allow deletion if pending
        if ($caseOrder->status !== CaseOrder::STATUS_PENDING) {
            return redirect()
                ->route('clinic.case-orders.index')
                ->with('error', 'Only pending case orders can be deleted.');
        }

        $caseOrder->delete();

        return redirect()
            ->route('clinic.case-orders.index')
            ->with('success', 'Case order deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\CaseOrder;
use App\Models\Pickup;
use App\Models\Clinic;
use App\Models\Dentist;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;

class CaseOrdersController extends Controller
{
    public function index()
    {
        $caseOrders = CaseOrder::with(['clinic', 'patient', 'dentist'])
            ->latest()
            ->paginate(15);

        return view('admin.case-orders.index', compact('caseOrders'));
    }


    public function create()
    {
        $clinics = Clinic::all();
        $dentists = Dentist::all();
        $patients = Patient::all();
        $caseTypes = ['Crown', 'Bridge', 'Denture', 'Implant', 'Veneer'];
        $caseStatuses = ['initial', 'in-progress', 'completed', 'cancelled'];

        return view('admin.case-orders.create', compact('clinics', 'dentists', 'patients', 'caseTypes', 'caseStatuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,clinic_id',
            'dentist_id' => 'nullable|exists:dentists,dentist_id',
            'patient_id' => 'nullable|exists:patients,patient_id',
            'case_type' => 'required|string|max:255',
            'status' => 'required|string|in:initial,in-progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        CaseOrder::create($validated);

        return redirect()->route('admin.case-orders.index')
            ->with('success', 'Case order created successfully.');
    }

    public function show($id)
    {
        $caseOrder = CaseOrder::with(['clinic', 'dentist', 'patient', 'appointments.technician', 'pickup.rider'])
            ->findOrFail($id);

        $riders = User::where('role', 'rider')->get();

        return view('admin.case-orders.show', compact('caseOrder', 'riders'));
    }

    public function edit(CaseOrder $caseOrder)
    {
        $clinics = Clinic::all();
        $dentists = Dentist::all();
        $patients = Patient::all();
        $caseTypes = ['Crown', 'Bridge', 'Denture', 'Implant', 'Veneer'];
        $caseStatuses = ['initial', 'in-progress', 'completed', 'cancelled'];

        return view('admin.case-orders.edit', compact('caseOrder', 'clinics', 'dentists', 'patients', 'caseTypes', 'caseStatuses'));
    }

    public function update(Request $request, CaseOrder $caseOrder)
    {
        $validated = $request->validate([
            'clinic_id' => 'required|exists:clinics,clinic_id',
            'dentist_id' => 'nullable|exists:dentists,dentist_id',
            'patient_id' => 'nullable|exists:patients,patient_id',
            'case_type' => 'required|string|max:255',
            'status' => 'required|string|in:initial,in-progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $caseOrder->update($validated);

        return redirect()->route('admin.case-orders.index')
            ->with('success', 'Case order updated successfully.');
    }

    public function destroy(CaseOrder $caseOrder)
    {
        $caseOrder->delete();
        return redirect()->route('admin.case-orders.index')
            ->with('success', 'Case order deleted successfully.');
    }

    public function approveAndAssign(Request $request, $id)
    {
        $validated = $request->validate([
            'rider_id' => 'required|exists:users,id',
            'pickup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $caseOrder = CaseOrder::findOrFail($id);

        // Update case order status
        $caseOrder->update(['status' => 'for pickup']);

        // Create pickup record
        Pickup::create([
            'case_order_id' => $caseOrder->co_id,
            'rider_id' => $validated['rider_id'],
            'pickup_date' => $validated['pickup_date'] ?? now()->addDay(),
            'pickup_address' => $caseOrder->clinic->address,
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        // Notify the rider
        $rider = User::find($validated['rider_id']);
        NotificationHelper::notifyUser(
            $rider->id,
            'pickup_assigned',
            'New Pickup Assignment',
            "You have been assigned to pick up case order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " from " . $caseOrder->clinic->clinic_name,
            route('rider.dashboard'),
            $caseOrder->co_id
        );

        return redirect()->route('admin.case-orders.show', $caseOrder->co_id)
            ->with('success', 'Case order approved and rider assigned successfully.');
    }
}

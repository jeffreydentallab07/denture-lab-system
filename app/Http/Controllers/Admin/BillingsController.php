<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Appointment;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class BillingsController extends Controller
{
    public function index()
    {
        $billings = Billing::with(['appointment.caseOrder.clinic', 'appointment.caseOrder.patient'])
            ->latest()
            ->paginate(15);

        return view('admin.billing.index', compact('billings'));
    }

    public function create(Request $request)
    {
        // Get appointment if provided
        $appointmentId = $request->query('appointment');
        $appointment = null;

        if ($appointmentId) {
            $appointment = Appointment::with(['caseOrder.clinic', 'caseOrder.patient', 'materialUsages.material'])
                ->where('work_status', 'completed')
                ->whereDoesntHave('billing') // Only appointments without billing
                ->findOrFail($appointmentId);
        }

        // Get all completed appointments without billing
        $completedAppointments = Appointment::with(['caseOrder.clinic', 'caseOrder.patient'])
            ->where('work_status', 'completed')
            ->whereDoesntHave('billing')
            ->latest()
            ->get();

        return view('admin.billing.create', compact('appointment', 'completedAppointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'total_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['payment_status'] = $validated['payment_status'] ?? 'unpaid';

        $billing = Billing::create($validated);

        $appointment = Appointment::with(['caseOrder'])->findOrFail($validated['appointment_id']);

        // Notify clinic about billing - FIX: Use billing->id instead of billing->id
        NotificationHelper::notifyClinic(
            $appointment->caseOrder->clinic_id,
            'billing_created',
            'Billing Created',
            "Billing has been created for your case order CASE-" . str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) . ". Total amount: ₱" . number_format($validated['total_amount'], 2),
            route('clinic.billing.show', $billing->id), // Changed from billing->id to billing->id
            $billing->id
        );

        return redirect()->route('admin.billing.index')
            ->with('success', 'Billing created successfully and clinic has been notified.');
    }
    public function show($id)
    {
        $billing = Billing::with(['appointment.caseOrder.clinic', 'appointment.caseOrder.patient', 'appointment.technician', 'appointment.materialUsages.material'])
            ->findOrFail($id);

        return view('admin.billing.show', compact('billing'));
    }

    public function edit($id)
    {
        $billing = Billing::with(['appointment'])->findOrFail($id);

        return view('admin.billing.edit', compact('billing'));
    }

    public function update(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $billing->payment_status;
        $billing->update($validated);

        // Notify clinic if payment status changed - FIX: Use billing->id
        if ($oldStatus !== $validated['payment_status']) {
            NotificationHelper::notifyClinic(
                $billing->appointment->caseOrder->clinic_id,
                'billing_updated',
                'Billing Status Updated',
                "Billing for case CASE-" . str_pad($billing->appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " status changed to '" . ucfirst($validated['payment_status']) . "'",
                route('clinic.billing.show', $billing->id), // Changed
                $billing->id
            );
        }

        return redirect()->route('admin.billing.show', $billing->id)
            ->with('success', 'Billing updated successfully.');
    }
    public function destroy($id)
    {
        $billing = Billing::findOrFail($id);
        $billing->delete();

        return redirect()->route('admin.billing.index')
            ->with('success', 'Billing deleted successfully.');
    }
}

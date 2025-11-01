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
                ->whereDoesntHave('billing')
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
            'additional_details' => 'nullable|string|max:500',
            'additional_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Get appointment with material cost
        $appointment = Appointment::with(['caseOrder'])->findOrFail($validated['appointment_id']);

        // Calculate total amount = material cost + additional amount
        $totalAmount = $appointment->total_material_cost + $validated['additional_amount'];

        // Create billing
        $billing = Billing::create([
            'appointment_id' => $validated['appointment_id'],
            'additional_details' => $validated['additional_details'],
            'additional_amount' => $validated['additional_amount'],
            'total_amount' => $totalAmount,
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
        ]);

        // Notify clinic about billing
        NotificationHelper::notifyClinic(
            $appointment->caseOrder->clinic_id,
            'billing_created',
            'Billing Created',
            "Billing has been created for your case order CASE-" . str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) . ". Total amount: ₱" . number_format($totalAmount, 2),
            route('clinic.billing.show', $billing->id),
            $billing->id
        );

        return redirect()->route('admin.billing.index')
            ->with('success', 'Billing created successfully and clinic has been notified.');
    }

    public function show($id)
    {
        $billing = Billing::with([
            'appointment.caseOrder.clinic',
            'appointment.caseOrder.patient',
            'appointment.technician',
            'appointment.materialUsages.material'
        ])->findOrFail($id);

        return view('admin.billing.show', compact('billing'));
    }

    public function edit($id)
    {
        $billing = Billing::with(['appointment.caseOrder.clinic', 'appointment.caseOrder.patient'])
            ->findOrFail($id);

        return view('admin.billing.edit', compact('billing'));
    }

    public function update(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        $validated = $request->validate([
            'additional_details' => 'nullable|string|max:500',
            'additional_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Recalculate total amount
        $totalAmount = $billing->appointment->total_material_cost + $validated['additional_amount'];

        $oldStatus = $billing->payment_status;

        // Update billing
        $billing->update([
            'additional_details' => $validated['additional_details'],
            'additional_amount' => $validated['additional_amount'],
            'total_amount' => $totalAmount,
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
        ]);

        // Notify clinic if payment status changed
        if ($oldStatus !== $validated['payment_status']) {
            NotificationHelper::notifyClinic(
                $billing->appointment->caseOrder->clinic_id,
                'billing_updated',
                'Billing Status Updated',
                "Billing for case CASE-" . str_pad($billing->appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " status changed to '" . ucfirst($validated['payment_status']) . "'",
                route('clinic.billing.show', $billing->id),
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

    public function invoice($id)
    {
        $billing = Billing::with([
            'appointment.caseOrder.clinic',
            'appointment.caseOrder.patient',
            'appointment.technician',
            'appointment.materialUsages.material'
        ])->findOrFail($id);

        return view('admin.billing.invoice', compact('billing'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\CaseOrder;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['caseOrder.clinic', 'caseOrder.patient', 'technician'])
            ->latest('estimated_date')
            ->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        // Get case order if provided
        $caseOrderId = $request->query('case_order');
        $caseOrder = $caseOrderId ? CaseOrder::with(['clinic', 'patient'])->findOrFail($caseOrderId) : null;

        // Get all case orders that are ready for appointment
        $caseOrders = CaseOrder::with(['clinic', 'patient'])
            ->where('status', 'for appointment')
            ->get();

        // Get all technicians
        $technicians = User::where('role', 'technician')->get();

        return view('admin.appointments.create', compact('caseOrder', 'caseOrders', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_order_id' => 'required|exists:case_orders,co_id',
            'technician_id' => 'required|exists:users,id',
            'estimated_date' => 'required|date|after_or_equal:today',
            'purpose' => 'nullable|string',
        ]);

        // Set default status to 'in-progress'
        $validated['work_status'] = 'in-progress';

        $appointment = Appointment::create($validated);

        // Update case order status to 'in progress'
        $caseOrder = CaseOrder::findOrFail($validated['case_order_id']);
        $caseOrder->update(['status' => 'in progress']);

        // Notify the technician
        $technician = User::find($validated['technician_id']);
        NotificationHelper::notifyUser(
            $technician->id,
            'appointment_assigned',
            'New Work Assignment',
            "You have been assigned to work on case order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " from " . $caseOrder->clinic->clinic_name . ". Estimated completion: " . \Carbon\Carbon::parse($validated['estimated_date'])->format('M d, Y'),
            route('technician.dashboard'),
            $appointment->appointment_id
        );

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully and technician has been notified.');
    }

    public function show($id)
    {
        $appointment = Appointment::with(['caseOrder.clinic', 'caseOrder.patient', 'technician', 'materialUsages.material'])
            ->findOrFail($id);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        $appointment = Appointment::with(['caseOrder'])->findOrFail($id);
        $caseOrders = CaseOrder::with(['clinic', 'patient'])->get();
        $technicians = User::where('role', 'technician')->get();

        return view('admin.appointments.edit', compact('appointment', 'caseOrders', 'technicians'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'case_order_id' => 'required|exists:case_orders,co_id',
            'technician_id' => 'required|exists:users,id',
            'estimated_date' => 'required|date|after_or_equal:today',
            'purpose' => 'nullable|string',
            'work_status' => 'required|in:pending,in-progress,completed,cancelled',
        ]);

        $appointment->update($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    public function reschedule(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'estimated_date' => 'required|date|after_or_equal:today',
            'reschedule_reason' => 'nullable|string',
        ]);

        $oldDate = $appointment->estimated_date->format('M d, Y');

        $appointment->update([
            'estimated_date' => $validated['estimated_date'],
        ]);

        // Notify technician about reschedule
        NotificationHelper::notifyUser(
            $appointment->technician_id,
            'appointment_rescheduled',
            'Appointment Rescheduled',
            "Your appointment APT-" . str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT) . " has been rescheduled from {$oldDate} to " . \Carbon\Carbon::parse($validated['estimated_date'])->format('M d, Y') . ". Reason: " . ($validated['reschedule_reason'] ?? 'Not specified'),
            route('technician.appointments.show', $appointment->appointment_id),
            $appointment->appointment_id
        );

        return redirect()->route('admin.appointments.show', $appointment->appointment_id)
            ->with('success', 'Appointment rescheduled successfully and technician has been notified.');
    }

    public function cancel(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
        ]);

        // Update appointment status to cancelled
        $appointment->update([
            'work_status' => 'cancelled',
        ]);

        // Update case order status back to 'for appointment'
        $appointment->caseOrder->update(['status' => 'for appointment']);

        // Notify technician about cancellation
        NotificationHelper::notifyUser(
            $appointment->technician_id,
            'appointment_cancelled',
            'Appointment Cancelled',
            "Appointment APT-" . str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT) . " for case CASE-" . str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " has been cancelled. Reason: " . $validated['cancellation_reason'],
            route('technician.dashboard'),
            $appointment->appointment_id
        );

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment cancelled successfully. Case order is now available for new appointment.');
    }
}

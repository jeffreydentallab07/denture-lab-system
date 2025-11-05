<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\CaseOrder;
use App\Models\Pickup;
use App\Models\Delivery;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CaseOrdersController extends Controller
{
    public function index()
    {
        $caseOrders = CaseOrder::with(['clinic', 'patient', 'dentist'])
            ->latest()
            ->paginate(15);

        return view('admin.case-orders.index', compact('caseOrders'));
    }

    public function show($id)
    {
        $caseOrder = CaseOrder::with([
            'clinic',
            'dentist',
            'patient',
            'appointments.technician',
            'appointments.delivery.rider',
            'pickups.rider'
        ])->findOrFail($id);

        $riders = User::where('role', 'rider')->get();
        $technicians = User::where('role', 'technician')->get();

        return view('admin.case-orders.show', compact('caseOrder', 'riders', 'technicians'));
    }

    // ========== PICKUP MANAGEMENT ==========

    public function createPickup($id)
    {
        $caseOrder = CaseOrder::with('clinic')->findOrFail($id);

        if (!in_array($caseOrder->status, ['pending', 'adjustment requested'])) {
            return redirect()
                ->back()
                ->with('error', 'Cannot create pickup for this case order at this time.');
        }

        $riders = User::where('role', 'rider')->get();

        return view('admin.case-orders.create-pickup', compact('caseOrder', 'riders'));
    }

    public function storePickup(Request $request, $id)
    {
        $caseOrder = CaseOrder::findOrFail($id);

        $validated = $request->validate([
            'rider_id' => 'required|exists:users,id',
            'pickup_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500'
        ]);

        $pickup = Pickup::create([
            'case_order_id' => $caseOrder->co_id,
            'rider_id' => $validated['rider_id'],
            'pickup_date' => $validated['pickup_date'],
            'pickup_address' => $caseOrder->clinic->address,
            'status' => 'pending',
            'notes' => $validated['notes']
        ]);

        $caseOrder->update([
            'status' => $caseOrder->status === 'pending' ? 'in progress' : 'revision in progress'
        ]);

        NotificationHelper::notifyUser(
            $validated['rider_id'],
            'pickup_assigned',
            'New Pickup Assignment',
            "You have been assigned to pick up Case Order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " from " . $caseOrder->clinic->clinic_name,
            route('rider.pickups.show', $pickup->pickup_id),
            $pickup->pickup_id
        );

        NotificationHelper::notifyClinic(
            $caseOrder->clinic_id,
            'pickup_scheduled',
            'Pickup Scheduled',
            "A pickup has been scheduled for Case Order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " on " . \Carbon\Carbon::parse($validated['pickup_date'])->format('M j, Y'),
            route('clinic.case-orders.show', $caseOrder->co_id),
            $pickup->pickup_id
        );

        return redirect()
            ->route('admin.case-orders.show', $id)
            ->with('success', 'Pickup has been created and rider assigned successfully!');
    }

    // ========== APPOINTMENT MANAGEMENT ==========

    public function createAppointment($id)
    {
        $caseOrder = CaseOrder::with('latestPickup')->findOrFail($id);

        if (!$caseOrder->latestPickup || $caseOrder->latestPickup->status !== 'picked-up') {
            return redirect()
                ->back()
                ->with('error', 'Cannot create appointment until pickup is completed.');
        }

        $technicians = User::where('role', 'technician')->get();

        return view('admin.case-orders.create-appointment', compact('caseOrder', 'technicians'));
    }

    public function storeAppointment(Request $request, $id)
    {
        $caseOrder = CaseOrder::findOrFail($id);
        $caseOrder->update(['status' => 'in progress']);

        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
            'schedule_datetime' => 'required|date|after:now',
            'purpose' => 'nullable|string|max:500'
        ]);

        $appointment = Appointment::create([
            'case_order_id' => $caseOrder->co_id,
            'technician_id' => $validated['technician_id'],
            'schedule_datetime' => $validated['schedule_datetime'],
            'purpose' => $validated['purpose'] ?? ($caseOrder->submission_count > 0 ? 'Revision/Adjustment Work' : 'Initial Work'),
            'work_status' => 'pending'
        ]);

        // ✅ Send email to clinic
        Mail::to($caseOrder->clinic->email)->send(
            new \App\Mail\AppointmentAssignedMail($appointment)
        );

        // Send in-app notifications
        NotificationHelper::notifyUser(
            $validated['technician_id'],
            'appointment_assigned',
            'New Appointment Assigned',
            "You have been assigned to work on Case Order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT),
            route('technician.appointments.show', $appointment->appointment_id),
            $appointment->appointment_id
        );

        NotificationHelper::notifyClinic(
            $caseOrder->clinic_id,
            'appointment_scheduled',
            'Work Scheduled',
            "Work has been scheduled for Case Order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " on " . \Carbon\Carbon::parse($validated['schedule_datetime'])->format('M j, Y g:i A'),
            route('clinic.case-orders.show', $caseOrder->co_id),
            $appointment->appointment_id
        );

        return redirect()
            ->route('admin.case-orders.show', $id)
            ->with('success', 'Appointment has been created and clinic has been notified via email!');
    }

    // ========== DELIVERY MANAGEMENT ==========

    public function createDelivery($id)
    {
        $caseOrder = CaseOrder::with('latestAppointment')->findOrFail($id);

        if (!$caseOrder->latestAppointment || $caseOrder->latestAppointment->work_status !== 'completed') {
            return redirect()
                ->back()
                ->with('error', 'Cannot create delivery until appointment work is completed.');
        }

        if ($caseOrder->latestAppointment->delivery) {
            return redirect()
                ->back()
                ->with('error', 'Delivery has already been created for this appointment.');
        }

        $riders = User::where('role', 'rider')->get();

        return view('admin.case-orders.create-delivery', compact('caseOrder', 'riders'));
    }

    public function storeDelivery(Request $request, $id)
    {
        $caseOrder = CaseOrder::with('latestAppointment')->findOrFail($id);

        $validated = $request->validate([
            'rider_id' => 'required|exists:users,id',
            'delivery_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500'
        ]);

        $delivery = Delivery::create([
            'appointment_id' => $caseOrder->latestAppointment->appointment_id,
            'rider_id' => $validated['rider_id'],
            'delivery_status' => 'ready to deliver',
            'delivery_date' => $validated['delivery_date'],
            'notes' => $validated['notes']
        ]);

        NotificationHelper::notifyUser(
            $validated['rider_id'],
            'delivery_assigned',
            'New Delivery Assignment',
            "You have been assigned to deliver Case Order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " to " . $caseOrder->clinic->clinic_name,
            route('rider.deliveries.show', $delivery->delivery_id),
            $delivery->delivery_id
        );

        NotificationHelper::notifyClinic(
            $caseOrder->clinic_id,
            'delivery_scheduled',
            'Delivery Scheduled',
            "Your order will be delivered on " . \Carbon\Carbon::parse($validated['delivery_date'])->format('M j, Y') . " for Case Order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT),
            route('clinic.case-orders.show', $caseOrder->co_id),
            $delivery->delivery_id
        );

        // Redirect to billing creation with appointment parameter
        return redirect()
            ->route('admin.billing.create', ['appointment' => $caseOrder->latestAppointment->appointment_id])
            ->with('success', 'Delivery created successfully! Now create the billing for this appointment.');
    }

    public function destroy($id)
    {
        $caseOrder = CaseOrder::findOrFail($id);

        if ($caseOrder->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'Only pending case orders can be deleted.');
        }

        $caseOrder->delete();

        return redirect()->route('admin.case-orders.index')
            ->with('success', 'Case order deleted successfully.');
    }
}

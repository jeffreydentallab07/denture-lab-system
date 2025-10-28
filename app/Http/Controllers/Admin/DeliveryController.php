<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Appointment;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['appointment.caseOrder.clinic', 'rider'])
            ->latest()
            ->paginate(15);

        return view('admin.delivery.index', compact('deliveries'));
    }

    public function create(Request $request)
    {
        // Get appointment if provided
        $appointmentId = $request->query('appointment');
        $appointment = null;

        if ($appointmentId) {
            $appointment = Appointment::with(['caseOrder.clinic', 'billing'])
                ->where('work_status', 'completed')
                ->whereHas('billing') // Must have billing
                ->whereDoesntHave('delivery') // No delivery yet
                ->findOrFail($appointmentId);
        }

        // Get all completed appointments with billing but no delivery
        $readyAppointments = Appointment::with(['caseOrder.clinic', 'billing'])
            ->where('work_status', 'completed')
            ->whereHas('billing')
            ->whereDoesntHave('delivery')
            ->latest()
            ->get();

        // Get all riders
        $riders = User::where('role', 'rider')->get();

        return view('admin.delivery.create', compact('appointment', 'readyAppointments', 'riders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'rider_id' => 'required|exists:users,id',
            'delivery_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $validated['delivery_status'] = 'ready to deliver';
        $validated['delivery_date'] = $validated['delivery_date'] ?? now()->addDay();

        $delivery = Delivery::create($validated);

        $appointment = Appointment::with(['caseOrder'])->findOrFail($validated['appointment_id']);

        // Notify rider about delivery assignment
        NotificationHelper::notifyUser(
            $validated['rider_id'],
            'delivery_assigned',
            'New Delivery Assignment',
            "You have been assigned to deliver completed work for case CASE-" . str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " to " . $appointment->caseOrder->clinic->clinic_name . ". Scheduled: " . \Carbon\Carbon::parse($validated['delivery_date'])->format('M d, Y'),
            route('rider.deliveries.show', $delivery->delivery_id),
            $delivery->delivery_id
        );

        // Notify clinic about upcoming delivery
        NotificationHelper::notifyClinic(
            $appointment->caseOrder->clinic_id,
            'delivery_scheduled',
            'Delivery Scheduled',
            "Your case order CASE-" . str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " will be delivered on " . \Carbon\Carbon::parse($validated['delivery_date'])->format('M d, Y') . ". Our rider will contact you soon.",
            route('clinic.appointments.show', $appointment->appointment_id),
            $delivery->delivery_id
        );

        return redirect()->route('admin.delivery.index')
            ->with('success', 'Delivery created successfully. Rider and clinic have been notified.');
    }

    public function show($id)
    {
        $delivery = Delivery::with(['appointment.caseOrder.clinic', 'appointment.caseOrder.patient', 'rider', 'appointment.billing'])
            ->findOrFail($id);

        return view('admin.delivery.show', compact('delivery'));
    }

    public function edit($id)
    {
        $delivery = Delivery::with(['appointment'])->findOrFail($id);
        $riders = User::where('role', 'rider')->get();

        return view('admin.delivery.edit', compact('delivery', 'riders'));
    }

    public function update(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);

        $validated = $request->validate([
            'rider_id' => 'required|exists:users,id',
            'delivery_status' => 'required|in:ready to deliver,in transit,delivered',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $delivery->delivery_status;
        $oldRider = $delivery->rider_id;
        $delivery->update($validated);

        // Notify if rider changed
        if ($oldRider !== $validated['rider_id']) {
            NotificationHelper::notifyUser(
                $validated['rider_id'],
                'delivery_reassigned',
                'Delivery Assignment',
                "You have been assigned to deliver case CASE-" . str_pad($delivery->appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " to " . $delivery->appointment->caseOrder->clinic->clinic_name,
                route('rider.deliveries.show', $delivery->delivery_id),
                $delivery->delivery_id
            );
        }
        // Notify if status changed
        if ($oldStatus !== $validated['delivery_status']) {
            if ($validated['delivery_status'] === 'delivered') {
                // Update delivered_at timestamp
                $delivery->update(['delivered_at' => now()]);

                // Notify clinic about delivery completion
                NotificationHelper::notifyClinic(
                    $delivery->appointment->caseOrder->clinic_id,
                    'delivery_completed',
                    'Delivery Completed',
                    "Your case order CASE-" . str_pad($delivery->appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " has been successfully delivered. Thank you for your business!",
                    route('clinic.appointments.show', $delivery->appointment_id),
                    $delivery->delivery_id
                );
            } elseif ($validated['delivery_status'] === 'in transit') {
                // Notify clinic that delivery is on the way
                NotificationHelper::notifyClinic(
                    $delivery->appointment->caseOrder->clinic_id,
                    'delivery_in_transit',
                    'Delivery In Transit',
                    "Your case order CASE-" . str_pad($delivery->appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " is now on the way. Expected delivery: " . \Carbon\Carbon::parse($delivery->delivery_date)->format('M d, Y'),
                    route('clinic.appointments.show', $delivery->appointment_id),
                    $delivery->delivery_id
                );
            }
        }

        return redirect()->route('admin.delivery.show', $delivery->delivery_id)
            ->with('success', 'Delivery updated successfully.');
    }

    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->delete();

        return redirect()->route('admin.delivery.index')
            ->with('success', 'Delivery deleted successfully.');
    }
}

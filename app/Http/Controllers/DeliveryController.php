<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['appointment.technician', 'rider'])->latest()->get();
        $riders = User::where('role', 'rider')->get();

        return view('admin.deliveries.index', compact('deliveries', 'riders'));
    }

    public function assignRider(Request $request, $delivery_id)
    {
        $delivery = Delivery::findOrFail($delivery_id);

        if (!is_null($delivery->rider_id) && $delivery->delivery_status !== 'ready to deliver') {
            return redirect()->back()->with('error', 'This delivery is already assigned to a rider.');
        }

        $delivery->rider_id = $request->rider_id;
        $delivery->delivery_status = 'in transit';
        $delivery->save();

        return redirect()->back()->with('success', 'Rider assigned successfully.');
    }

    public function createFromAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->delivery) {
            return redirect()->back()->with('error', 'Delivery already exists for this appointment.');
        }

        try {
            $delivery = Delivery::create([
                'appointment_id' => $appointment->appointment_id,
                'rider_id' => null,
                'delivery_status' => 'ready to deliver',
                'delivery_date' => now(), // Only include if column exists
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle missing delivery_date column
            if (str_contains($e->getMessage(), 'Unknown column \'delivery_date\'')) {
                $delivery = Delivery::create([
                    'appointment_id' => $appointment->appointment_id,
                    'rider_id' => null,
                    'delivery_status' => 'ready to deliver',
                ]);
            } else {
                throw $e;
            }
        }

        return redirect()->route('deliveries.index')->with('success', 'Delivery created successfully.');
    }

    public function show(Delivery $delivery)
    {
        return view('admin.deliveries.show', compact('delivery'));
    }

    public function markDelivered(Delivery $delivery)
    {
        // Ensure only the assigned rider can update
        if ($delivery->rider_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to update this delivery.');
        }

        if (in_array($delivery->delivery_status, ['in transit', 'ready to deliver'])) {
            $delivery->delivery_status = 'delivered';
            $delivery->save();

            return redirect()->route('rider.dashboard')
                ->with('success', 'Delivery marked as delivered.');
        }

        return redirect()->back()->with('error', 'Delivery cannot be marked as delivered from its current state (' . $delivery->delivery_status . ').');
    }

    public function updateStatus(Request $request, Delivery $delivery)
    {
        // Ensure only the assigned rider can update
        if ($delivery->rider_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to update this delivery.');
        }

        $request->validate([
            'delivery_status' => 'required|in:ready to deliver,in transit,delivered,cancelled',
        ]);

        // Prevent changing status if already delivered or cancelled
        if (in_array($delivery->delivery_status, ['delivered', 'cancelled'])) {
            return redirect()->back()->with('error', 'Cannot change status of a ' . $delivery->delivery_status . ' delivery.');
        }

        // Handle specific status transitions
        if ($request->delivery_status === 'in transit' && $delivery->delivery_status === 'ready to deliver') {
            $delivery->delivery_status = 'in transit';
            $delivery->save();
            return redirect()->route('rider.dashboard')->with('success', 'Delivery status updated to In Transit.');
        } elseif ($request->delivery_status === 'delivered' && $delivery->delivery_status === 'in transit') {
            return $this->markDelivered($delivery);
        } elseif ($request->delivery_status === 'delivered' && $delivery->delivery_status === 'ready to deliver') {
            Log::warning('Delivery marked delivered from Ready to Deliver state', ['delivery_id' => $delivery->delivery_id]);
            return $this->markDelivered($delivery);
        }

        $delivery->delivery_status = $request->delivery_status;
        $delivery->save();

        return redirect()->route('rider.dashboard')->with('success', 'Delivery status updated.');
    }
}

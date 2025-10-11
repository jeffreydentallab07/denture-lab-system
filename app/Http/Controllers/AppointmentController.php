<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\CaseOrder;
use App\Models\User;
use App\Models\Delivery;
use App\Models\Billing;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['technician', 'material', 'delivery', 'billing', 'caseOrder'])->get();

        $technicians = User::where('role', 'technician')->get()->map(function ($tech) {
            $tech->is_busy = Appointment::where('technician_id', $tech->id)
                ->whereIn('work_status', ['pending', 'in progress'])
                ->exists();
            return $tech;
        });

        return view('admin.appointments.index', compact('appointments', 'technicians'));
    }

    public function approve($co_id)
    {
        $caseOrder = CaseOrder::with(['patient', 'dentist'])->find($co_id);
        if (!$caseOrder) {
            return redirect()->back()->with('error', 'Case order not found.');
        }

        if (Appointment::where('co_id', $co_id)->exists()) {
            return redirect()->back()->with('error', 'Appointment already exists for this case order.');
        }

        Appointment::create([
            'co_id' => $caseOrder->co_id,
            'technician_id' => null,
            'schedule_datetime' => now(),
            'material_id' => null,
            'priority_level' => 'Normal',
            'purpose' => 'initial',
            'work_status' => 'pending',
        ]);

        $caseOrder->status = 'approved';
        $caseOrder->save();

        return redirect()->route('admin.appointments.index')->with('success', 'Case order approved and moved to appointments.');
    }

    public function assignTechnician(Request $request, Appointment $appointment)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id',
        ]);

        // Check if the assigned technician is already busy with other "pending" or "in progress" appointments
        if (Appointment::where('technician_id', $request->technician_id)
            ->whereIn('work_status', ['in progress', 'pending'])
            ->where('appointment_id', '!=', $appointment->appointment_id)
            ->exists()
        ) {
            return redirect()->back()->with('error', 'Technician is currently busy with another appointment.');
        }

        $appointment->technician_id = $request->technician_id;
        $appointment->work_status = 'in progress'; // Automatically set to in progress when assigned
        $appointment->save();

        return redirect()->route('admin.appointments.index')->with('success', 'Technician assigned successfully!');
    }

    public function markAsFinished($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->work_status = 'finished';
        $appointment->save();

        // Check if delivery already exists before creating one
        if (!$appointment->delivery) {
            try {
                Delivery::create([
                    'appointment_id' => $appointment->appointment_id,
                    'rider_id' => null,
                    'delivery_status' => 'ready to deliver', // Use consistent string value
                    'delivery_date' => now(), // Include delivery_date if column exists
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // Handle case where delivery_date column doesn't exist
                if (str_contains($e->getMessage(), 'Unknown column \'delivery_date\'')) {
                    Delivery::create([
                        'appointment_id' => $appointment->appointment_id,
                        'rider_id' => null,
                        'delivery_status' => 'ready to deliver',
                        // Exclude delivery_date if column doesn't exist
                    ]);
                } else {
                    throw $e; // Re-throw other database errors
                }
            }
        }

        return redirect()->back()->with('success', 'Appointment marked as finished.');
    }

    public function createBilling($id)
    {
        $appointment = Appointment::with('material')->findOrFail($id);

        if (Billing::where('appointment_id', $appointment->appointment_id)->exists()) {
            return redirect()->back()->with('error', 'Billing already exists for this appointment.');
        }

        // Ensure material and its price are available
        if (!$appointment->material) {
            return redirect()->back()->with('error', 'Cannot create billing: Material not selected for this appointment.');
        }

        $totalAmount = $appointment->material->price ?? 0;

        Billing::create([
            'appointment_id' => $appointment->appointment_id,
            'total_amount' => $totalAmount,
        ]);

        return redirect()->route('billing.index')->with('success', 'Billing created successfully!');
    }
}

<?php

namespace App\Http\Controllers\Technician;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Material;
use App\Models\MaterialUsage;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function dashboard()
    {
        $technician = Auth::user();

        // Get appointments for this technician
        $appointments = Appointment::with(['caseOrder.clinic', 'caseOrder.patient', 'materialUsages.material'])
            ->where('technician_id', $technician->id)
            ->whereIn('work_status', ['pending', 'in-progress']) // Only show active appointments
            ->latest('schedule_datetime')
            ->get();

        // Get all available materials
        $materials = Material::where('quantity', '>', 0)->get();

        // Statistics
        $totalAssigned = Appointment::where('technician_id', $technician->id)->count();
        $completedWork = Appointment::where('technician_id', $technician->id)
            ->where('work_status', 'completed')
            ->count();
        $pendingWork = Appointment::where('technician_id', $technician->id)
            ->whereIn('work_status', ['pending', 'in-progress'])
            ->count();

        // Today's appointments
        $todayAppointments = Appointment::with(['caseOrder.clinic'])
            ->where('technician_id', $technician->id)
            ->whereDate('schedule_datetime', today())
            ->get();

        // Notifications
        $notifications = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->count();

        return view('technician.dashboard', compact(
            'appointments',
            'materials',
            'totalAssigned',
            'completedWork',
            'pendingWork',
            'todayAppointments',
            'notifications',
            'notificationCount'
        ));
    }

    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::where('technician_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'work_status' => 'nullable|in:pending,in-progress,completed',
            'material_id' => 'nullable|exists:materials,material_id',
        ]);

        $oldStatus = $appointment->work_status;

        // Update work status if provided
        if (isset($validated['work_status'])) {
            $appointment->update(['work_status' => $validated['work_status']]);

            $statusChanged = $oldStatus !== $validated['work_status'];

            // Notify admin about status change
            if ($statusChanged) {
                NotificationHelper::notifyAdmins(
                    'appointment_status_updated',
                    'Appointment Status Updated',
                    "Technician " . Auth::user()->name . " updated appointment APT-" . str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT) . " status from '" . ucfirst(str_replace('-', ' ', $oldStatus)) . "' to '" . ucfirst(str_replace('-', ' ', $validated['work_status'])) . "'",
                    route('admin.appointments.show', $appointment->appointment_id),
                    $appointment->appointment_id
                );
            }

            // If completed, update case order status and notify
            if ($validated['work_status'] === 'completed') {
                $appointment->caseOrder->update(['status' => 'completed']);

                // Notify admin about completion
                NotificationHelper::notifyAdmins(
                    'appointment_completed',
                    'Appointment Completed',
                    "Appointment APT-" . str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT) . " for case CASE-" . str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " has been completed by " . Auth::user()->name,
                    route('admin.appointments.show', $appointment->appointment_id),
                    $appointment->appointment_id
                );

                // Notify clinic about completion
                NotificationHelper::notifyClinic(
                    $appointment->caseOrder->clinic_id,
                    'appointment_completed',
                    'Your Case Order is Complete',
                    "Case order CASE-" . str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " has been completed. The work is ready for pickup or delivery.",
                    route('clinic.appointments.index'),
                    $appointment->appointment_id
                );
            }

            // If work started (changed to in-progress)
            if ($validated['work_status'] === 'in-progress' && $oldStatus === 'pending') {
                // Notify admin that work has started
                NotificationHelper::notifyAdmins(
                    'appointment_in_progress',
                    'Work Started',
                    "Technician " . Auth::user()->name . " has started work on appointment APT-" . str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT),
                    route('admin.appointments.show', $appointment->appointment_id),
                    $appointment->appointment_id
                );

                // Notify clinic that work has started
                NotificationHelper::notifyClinic(
                    $appointment->caseOrder->clinic_id,
                    'appointment_in_progress',
                    'Work in Progress',
                    "Your case order CASE-" . str_pad($appointment->case_order_id, 5, '0', STR_PAD_LEFT) . " is now being worked on by our technician.",
                    route('clinic.appointments.index'),
                    $appointment->appointment_id
                );
            }
        }

        return redirect()->route('technician.dashboard')
            ->with('success', 'Appointment updated successfully.');
    }

    public function showAppointment($id)
    {
        $technician = Auth::user();

        $appointment = Appointment::with(['caseOrder.clinic', 'caseOrder.patient', 'materialUsages.material'])
            ->where('technician_id', $technician->id)
            ->findOrFail($id);

        $materials = Material::where('quantity', '>', 0)->get();

        // Notifications
        $notifications = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->count();

        return view('technician.appointment-details', compact('appointment', 'materials', 'notifications', 'notificationCount'));
    }

    public function addMaterial(Request $request, $id)
    {
        $appointment = Appointment::where('technician_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'material_id' => 'required|exists:materials,material_id',
            'quantity_used' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Check if material has enough quantity
        $material = Material::findOrFail($validated['material_id']);
        if ($material->quantity < $validated['quantity_used']) {
            return back()->with('error', 'Insufficient material quantity available.');
        }

        // Create material usage
        MaterialUsage::create([
            'appointment_id' => $appointment->appointment_id,
            'material_id' => $validated['material_id'],
            'quantity_used' => $validated['quantity_used'],
            'notes' => $validated['notes'],
        ]);

        // Deduct from material inventory
        $material->decrement('quantity', $validated['quantity_used']);

        // Update material status based on new quantity
        if ($material->quantity == 0) {
            $material->update(['status' => 'out of stock']);

            // Notify admins about out of stock
            NotificationHelper::notifyAdmins(
                'material_out_of_stock',
                'Material Out of Stock',
                "Material '{$material->material_name}' is now out of stock. Please restock immediately.",
                route('admin.materials.edit', $material->material_id),
                $material->material_id
            );
        } elseif ($material->quantity <= 10) {
            $material->update(['status' => 'low stock']);

            // Notify admins about low stock
            NotificationHelper::notifyAdmins(
                'material_low_stock',
                'Material Low Stock',
                "Material '{$material->material_name}' is running low (Only {$material->quantity} {$material->unit} remaining).",
                route('admin.materials.edit', $material->material_id),
                $material->material_id
            );
        }

        return back()->with('success', 'Material added successfully.');
    }

    public function removeMaterial($appointmentId, $usageId)
    {
        $usage = MaterialUsage::where('appointment_id', $appointmentId)
            ->whereHas('appointment', function ($query) {
                $query->where('technician_id', Auth::id());
            })
            ->findOrFail($usageId);

        // Return material to inventory
        $material = $usage->material;
        $material->increment('quantity', $usage->quantity_used);

        // Update material status
        if ($material->quantity > 10) {
            $material->update(['status' => 'available']);
        } elseif ($material->quantity > 0) {
            $material->update(['status' => 'low stock']);
        }

        $usage->delete();

        return back()->with('success', 'Material removed successfully.');
    }

    public function appointmentsIndex()
    {
        $technician = Auth::user();

        $appointments = Appointment::with(['caseOrder.clinic', 'materialUsages'])
            ->where('technician_id', $technician->id)
            ->latest('schedule_datetime')
            ->get();

        // Notifications
        $notifications = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->count();

        return view('technician.appointments.index', compact('appointments', 'notifications', 'notificationCount'));
    }

    public function materialsIndex()
    {
        $technician = Auth::user();

        $materials = Material::where('quantity', '>', 0)->get();

        // Notifications
        $notifications = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->count();

        return view('technician.materials.index', compact('materials', 'notifications', 'notificationCount'));
    }

    public function workHistory()
    {
        $technician = Auth::user();

        $completedAppointments = Appointment::with(['caseOrder.clinic', 'materialUsages'])
            ->where('technician_id', $technician->id)
            ->where('work_status', 'completed')
            ->latest('updated_at')
            ->paginate(20);

        // Notifications
        $notifications = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->latest()
            ->take(5)
            ->get();

        $notificationCount = Notification::where('user_id', $technician->id)
            ->where('read', false)
            ->count();

        return view('technician.work-history', compact('completedAppointments', 'notifications', 'notificationCount'));
    }
}

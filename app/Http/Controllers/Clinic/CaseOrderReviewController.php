<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\CaseOrder;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;

class CaseOrderReviewController extends Controller
{
    /**
     * Show review page for a case order
     */
    public function show($id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::with([
            'patient',
            'dentist',
            'appointments.technician',
            'appointments.delivery',
            'latestDelivery'
        ])
            ->where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        // Check if can be reviewed
        if (!$caseOrder->canBeReviewedByClinic()) {
            return redirect()
                ->route('clinic.case-orders.show', $id)
                ->with('error', 'This case order cannot be reviewed at this time.');
        }

        return view('clinic.case-orders.review', compact('caseOrder'));
    }

    /**
     * Approve case order (mark as completed)
     */
    public function approve(Request $request, $id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        if (!$caseOrder->canBeReviewedByClinic()) {
            return redirect()
                ->back()
                ->with('error', 'This case order cannot be approved at this time.');
        }

        $validated = $request->validate([
            'completion_notes' => 'nullable|string|max:1000'
        ]);

        // Update case order to completed
        $caseOrder->update([
            'status' => CaseOrder::STATUS_COMPLETED,
            'notes' => $caseOrder->notes . "\n\n[APPROVED] " . ($validated['completion_notes'] ?? 'Approved by clinic.')
        ]);

        // Notify technician
        if ($caseOrder->latestAppointment && $caseOrder->latestAppointment->technician_id) {
            NotificationHelper::notifyUser(
                $caseOrder->latestAppointment->technician_id,
                'case_order_approved',
                'Case Order Approved! 🎉',
                "Case order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " has been approved by the clinic.",
                route('technician.appointments.show', $caseOrder->latestAppointment->appointment_id),
                $caseOrder->co_id
            );
        }

        return redirect()
            ->route('clinic.case-orders.show', $id)
            ->with('success', 'Case order has been marked as completed!');
    }

    /**
     * Request adjustment for case order
     */
    public function requestAdjustment(Request $request, $id)
    {
        $clinic = Auth::guard('clinic')->user();

        $caseOrder = CaseOrder::where('clinic_id', $clinic->clinic_id)
            ->findOrFail($id);

        if (!$caseOrder->canBeReviewedByClinic()) {
            return redirect()
                ->back()
                ->with('error', 'This case order cannot be adjusted at this time.');
        }

        $validated = $request->validate([
            'adjustment_notes' => 'required|string|max:1000'
        ]);

        // Update case order status
        $caseOrder->update([
            'status' => CaseOrder::STATUS_ADJUSTMENT_REQUESTED,
            'notes' => $caseOrder->notes . "\n\n[ADJUSTMENT REQUESTED] " . $validated['adjustment_notes']
        ]);

        // Notify admin
        NotificationHelper::notifyAdmins(
            'adjustment_requested',
            'Adjustment Requested',
            "Case order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " needs adjustments.",
            route('admin.case-orders.show', $caseOrder->co_id),
            $caseOrder->co_id
        );

        // Notify technician if exists
        if ($caseOrder->latestAppointment && $caseOrder->latestAppointment->technician_id) {
            NotificationHelper::notifyUser(
                $caseOrder->latestAppointment->technician_id,
                'adjustment_requested',
                'Adjustment Requested',
                "Case order CASE-" . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) . " requires adjustments from the clinic.",
                route('technician.appointments.show', $caseOrder->latestAppointment->appointment_id),
                $caseOrder->co_id
            );
        }

        return redirect()
            ->route('clinic.case-orders.show', $id)
            ->with('success', 'Adjustment request has been submitted. Admin will schedule a new pickup.');
    }
}

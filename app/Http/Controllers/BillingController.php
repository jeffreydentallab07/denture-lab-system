<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Appointment;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    public function index()
    {
        try {
            // Ensure 'appointment.material' is correctly loaded
            $billings = Billing::with([
                'appointment.material', // This is the crucial relationship for your request
                'appointment.caseOrder',
                'appointment.technician',
                'appointment.delivery.rider'
            ])->get();

            return view('admin.billing.index', compact('billings'));
        } catch (\Exception $e) {
            Log::error('Error loading billings: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to load billing records.');
        }
    }

    public function create()
    {
        // Only show finished appointments that don't have billing yet
        $appointments = Appointment::with('material', 'caseOrder.clinic')
            ->where('work_status', 'finished')
            ->whereDoesntHave('billing')
            ->get();

        return view('billing.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'total_amount' => 'nullable|numeric|min:0', // total_amount can be null, calculated from material
        ]);

        try {
            // Check if billing already exists
            if (Billing::where('appointment_id', $request->appointment_id)->exists()) {
                return redirect()->back()->withInput()->with('error', 'Billing already exists for this appointment.');
            }

            // Get the appointment to calculate total amount if not provided
            $appointment = Appointment::with('material')->findOrFail($request->appointment_id);

            // Ensure appointment is finished
            if ($appointment->work_status !== 'finished') {
                return redirect()->back()->withInput()->with('error', 'Billing can only be created for finished appointments.');
            }

            // If total_amount is not provided in the request, use material price
            $totalAmount = $request->total_amount ?? ($appointment->material->price ?? 0);

            Billing::create([
                'appointment_id' => $request->appointment_id,
                'total_amount' => $totalAmount,
                'created_at' => now(),
            ]);

            return redirect()->route('billing.index')->with('success', 'Billing created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating billing: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->with('error', 'Failed to create billing: ' . $e->getMessage());
        }
    }

    public function edit(Billing $billing)
    {
        $appointments = Appointment::with('material', 'caseOrder.clinic')
            ->where('work_status', 'finished')
            ->get();

        return view('billing.edit', compact('billing', 'appointments'));
    }

    public function update(Request $request, Billing $billing)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'total_amount' => 'required|numeric|min:0',
        ]);

        try {
            if (
                $request->appointment_id !== $billing->appointment_id &&
                Billing::where('appointment_id', $request->appointment_id)->exists()
            ) {
                return redirect()->back()->withInput()->with('error', 'Billing already exists for the selected appointment.');
            }

            $billing->update([
                'appointment_id' => $request->appointment_id,
                'total_amount' => $request->total_amount,
            ]);

            return redirect()->route('billing.index')->with('success', 'Billing updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating billing: ' . $e->getMessage(), [
                'id' => $billing->id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->with('error', 'Failed to update billing: ' . $e->getMessage());
        }
    }

    public function destroy(Billing $billing)
    {
        try {
            $billing->delete();
            return redirect()->route('billing.index')->with('success', 'Billing deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting billing: ' . $e->getMessage(), [
                'id' => $billing->id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to delete billing: ' . $e->getMessage());
        }
    }

    public function print(Billing $billing)
    {
        $billing->load([
            'appointment.material',
            'appointment.caseOrder.clinic',
            'appointment.caseOrder.dentist',
            'appointment.delivery.rider'
        ]);

        return view('billing.print', compact('billing'));
    }

    // For modal (HTML)
    public function receiptModal($billingId)
    {
        $billing = Billing::with([
            'appointment.material',
            'appointment.caseOrder.clinic',
            'appointment.caseOrder.dentist',
            'appointment.technician',
            'appointment.delivery.rider'
        ])->find($billingId);

        return view('admin.billing.receipt', compact('billing'));
    }

    // For PDF
    public function exportPdf($billingId)
    {
        $billing = Billing::with(['appointment.material', 'appointment.caseOrder.clinic', 'appointment.technician', 'appointment.delivery.rider'])->find($billingId);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.billing.receipt_pdf', compact('billing'));
        return $pdf->download('receipt_' . $billingId . '.pdf');
    }
    public function generateReport()
    {
        $billings = Billing::with(['appointment', 'caseOrder'])->orderBy('created_at', 'desc')->get();

        $pdf = PDF::loadView('admin.billing.report', compact('billings'));
        return $pdf->download('billing_report.pdf');
    }
}

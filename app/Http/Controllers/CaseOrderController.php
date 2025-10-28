<?php

namespace App\Http\Controllers;

use App\Models\CaseOrder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseOrderController extends Controller
{
    // Admin: View all case orders
    public function index()
    {
        // Check if clinic user
        if (Auth::guard('clinic')->check()) {
            return $this->clinicIndex();
        }

        // Admin view
        $caseOrders = CaseOrder::with(['patient'])
            ->orderBy('created_at', 'desc')
            ->get();

        $newCasesCount = CaseOrder::where('status', 'initial')->count();

        return view('admin.case-orders.index', compact('caseOrders', 'newCasesCount'));
    }

    // Clinic: View their own case orders
    protected function clinicIndex()
    {
        $clinicId = Auth::guard('clinic')->user()->clinic_id;

        $caseOrders = CaseOrder::where('clinic_id', $clinicId)
            ->with(['patient'])
            ->orderBy('created_at', 'desc')
            ->get();

        $patients = Patient::all();
        $caseTypes = ['Denture plastic', 'Jacket crowns/Porcelain', 'Retainers', 'Valplast Flexible'];
        $statuses = ['initial', 'in progress', 'completed', 'on hold', 'cancelled'];

        return view('clinic.case-orders.index', compact('caseOrders', 'patients', 'caseTypes', 'statuses'));
    }

    // Create new case order (Clinic)
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'case_type' => 'required|string',
            'status' => 'required|string',
        ]);

        $clinicId = Auth::guard('clinic')->check()
            ? Auth::guard('clinic')->user()->clinic_id
            : $request->clinic_id;

        CaseOrder::create([
            'patient_id' => $request->patient_id,
            'clinic_id' => $clinicId,
            'case_type' => $request->case_type,
            'status' => $request->status,
            'notes' => $request->notes,
            'dentist_name' => $request->dentist_name,
            'clinic_name' => $request->clinic_name,
        ]);

        return redirect()->back()->with('success', 'Case order added successfully.');
    }

    // Update case order
    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'case_type' => 'required|string',
            'status' => 'required|string',
        ]);

        $caseOrder = CaseOrder::findOrFail($id);

        $caseOrder->update([
            'patient_id' => $request->patient_id,
            'case_type' => $request->case_type,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Case order updated successfully.');
    }

    // Delete case order
    public function destroy($id)
    {
        $caseOrder = CaseOrder::findOrFail($id);
        $caseOrder->delete();

        return redirect()->back()->with('success', 'Case order deleted successfully.');
    }

    // Admin: Approve case order and create appointment
    public function approve($id)
    {
        $caseOrder = CaseOrder::findOrFail($id);

        // Check if appointment already exists
        if (Appointment::where('patient_id', $caseOrder->patient_id)
            ->where('clinic_id', $caseOrder->clinic_id)
            ->where('status', 'pending')
            ->exists()
        ) {
            return redirect()->back()->with('error', 'Appointment already exists for this case order.');
        }

        // Create appointment with ONLY fields that exist in your migration
        Appointment::create([
            'clinic_id' => $caseOrder->clinic_id,
            'patient_id' => $caseOrder->patient_id,
            'dentist_id' => $caseOrder->dentist_id,
            'technician_id' => null,
            'appointment_date' => now(),
            'schedule_datetime' => now(),
            'status' => 'pending',
            'work_status' => 'pending',
            'service_type' => $caseOrder->case_type,
            'chief_complaint' => $caseOrder->notes,
        ]);

        $caseOrder->status = 'approved';
        $caseOrder->save();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Case order approved and moved to appointments.');
    }
}

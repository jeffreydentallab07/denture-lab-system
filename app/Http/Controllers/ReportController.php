<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseOrder;
use App\Models\Appointment;
use App\Models\Delivery;
use App\Models\Billing;
use App\Models\Technician;
use App\Models\Rider;
use App\Models\Clinic;
use App\Models\Dentist;
use App\Models\User;


class ReportController extends Controller
{
    // 📊 Main Reports Dashboard
    public function index()
    {
        return view('admin.reports.index');
    }

    public function caseOrders(Request $request)
    {
        $query = CaseOrder::with(['clinic', 'dentist', 'patient', 'delivery', 'appointment']);

        // Filter by Clinic
        if ($request->clinic_id) {
            $query->where('clinic_id', $request->clinic_id);
        }

        // Filter by Status
        if ($request->status) {
            $status = $request->status;

            $query->where(function ($q) use ($status) {
                // Match Case Order status
                $q->where('status', $status);

                // Match Delivery status
                $q->orWhereHas('delivery', function ($d) use ($status) {
                    $d->where('delivery_status', $status);
                });

                // Match Appointment work_status
                $q->orWhereHas('appointment', function ($a) use ($status) {
                    $a->where('work_status', $status);
                });
            });
        }

        // Filter by Date Range (created_at)
        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59'
            ]);
        } elseif ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        } elseif ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Pagination
        $caseOrders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Clinics for filter dropdown
        $clinics = Clinic::all();

        // Collect all unique statuses for the filter dropdown
        $statuses = collect([
            ...CaseOrder::pluck('status')->toArray(),
            ...\App\Models\Appointment::pluck('work_status')->toArray(),
            ...\App\Models\Delivery::pluck('delivery_status')->toArray()
        ])->unique()->filter()->values();

        // AJAX request returns only the table
        if ($request->ajax()) {
            return view('admin.reports.caseorders', compact('caseOrders', 'clinics', 'statuses'))->render();
        }

        return view('admin.reports.caseorders', compact('caseOrders', 'clinics', 'statuses'));
    }


    public function appointments(Request $request)
    {
        $appointments = Appointment::with(['caseOrder.clinic', 'caseOrder.dentist', 'caseOrder.patient', 'technician'])
            ->when($request->clinic_id, fn($q) => $q->whereHas('caseOrder', fn($q2) => $q2->where('clinic_id', $request->clinic_id)))
            ->when($request->dentist_id, fn($q) => $q->whereHas('caseOrder', fn($q2) => $q2->where('dentist_id', $request->dentist_id)))
            ->when($request->technician_id, fn($q) => $q->where('technician_id', $request->technician_id))
            ->when($request->work_status, fn($q) => $q->where('work_status', $request->work_status))
            ->whenwhen($request->from, fn($q) => $q->whereDate('estimated_date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('estimated_date', '<=', $request->to))
            ->orderBy('estimated_date', 'desc')
            ->paginate(20);

        $clinics = Clinic::all();

        // Dentist options depend on selected clinic
        $dentists = Dentist::when(
            $request->clinic_id,
            fn($q) =>
            $q->whereHas('appointments.caseOrder', fn($q2) => $q2->where('clinic_id', $request->clinic_id))
        )->get();

        $technicians = User::where('role', 'technician')->get();
        $statuses = ['pending', 'in progress', 'finished'];

        if ($request->ajax()) {
            return view('admin.reports.appointments', compact('appointments', 'clinics', 'dentists', 'technicians', 'statuses'))->render();
        }

        return view('admin.reports.appointments', compact('appointments', 'clinics', 'dentists', 'technicians', 'statuses'));
    }


    public function deliveries(Request $request)
    {
        $query = Delivery::with(['appointment.caseOrder.clinic']);

        // Filter by Clinic
        if ($request->clinic_id) {
            $query->whereHas('appointment.caseOrder', fn($q) => $q->where('clinic_id', $request->clinic_id));
        }

        // Filter by Status
        if ($request->status) {
            $query->where('delivery_status', $request->status);
        }

        // Filter by date range using created_at
        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
        } elseif ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        } elseif ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $deliveries = $query->orderBy('created_at', 'desc')->paginate(20);

        $clinics = Clinic::all();
        $statuses = Delivery::pluck('delivery_status')->unique()->values();

        if ($request->ajax()) {
            return view('admin.reports.deliveries', compact('deliveries', 'clinics', 'statuses'))->render();
        }

        return view('admin.reports.deliveries', compact('deliveries', 'clinics', 'statuses'));
    }

    public function billings(Request $request)
    {
        $query = Billing::with(['caseOrder.clinic']);

        if ($request->filled('clinic_id')) {
            $query->whereHas('caseOrder', fn($q) => $q->where('clinic_id', $request->clinic_id));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $billings = $query->orderBy('created_at', 'desc')->paginate(20);

        $clinics = Clinic::all();

        if ($request->ajax()) {
            return view('admin.reports.billings', compact('billings', 'clinics'))->render();
        }

        return view('admin.reports.billings', compact('billings', 'clinics'));
    }

    public function technicians()
    {
        // Get all users with role 'technician' and count their appointments
        $technicians = User::where('role', 'technician')
            ->withCount('appointments')
            ->paginate(20);

        return view('admin.reports.technicians', compact('technicians'));
    }


    public function riders(Request $request)
    {
        $query = Rider::withCount(['deliveries' => function ($q) use ($request) {
            if ($request->clinic_id) {
                $q->whereHas('caseOrder', fn($q2) => $q2->where('clinic_id', $request->clinic_id));
            }
        }]);

        // Order by rider name
        $riders = $query->orderBy('name')->paginate(20);

        // Clinics for filter dropdown
        $clinics = Clinic::all();

        // AJAX request returns full table
        if ($request->ajax()) {
            return view('admin.reports.riders', compact('riders', 'clinics'))->render();
        }

        return view('admin.reports.riders', compact('riders', 'clinics'));
    }

    public function clinics(Request $request)
    {
        // Use withCount now that relationships exist
        $query = Clinic::withCount(['caseOrders', 'appointments']);

        // Optional filter by clinic name
        if ($request->filled('clinic_name')) {
            $query->where('clinic_name', 'like', '%' . $request->clinic_name . '%');
        }

        $clinics = $query->orderBy('clinic_name')->paginate(20);

        return view('admin.reports.clinics', compact('clinics'));
    }


    // ⚙️ Generate Report (custom logic kung may export forms ka)
    public function generate(Request $request)
    {
        // Implement custom generate logic here
        return back()->with('success', 'Report generated successfully.');
    }

    // 📄 Export to PDF
    public function exportPdf(Request $request)
    {
        // Implement PDF export logic here
        return back()->with('success', 'PDF exported successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseOrder;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\Delivery;
use App\Models\Material;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = [];

        switch ($reportType) {
            case 'case-orders':
                $data = $this->getCaseOrdersReport($dateFrom, $dateTo);
                break;
            case 'revenue':
                $data = $this->getRevenueReport($dateFrom, $dateTo);
                break;
            case 'materials':
                $data = $this->getMaterialsReport($dateFrom, $dateTo);
                break;
            case 'clinic-performance':
                $data = $this->getClinicPerformanceReport($dateFrom, $dateTo);
                break;
            case 'technician-performance':
                $data = $this->getTechnicianPerformanceReport($dateFrom, $dateTo);
                break;
            case 'delivery-performance':
                $data = $this->getDeliveryPerformanceReport($dateFrom, $dateTo);
                break;
            default:
                $data = $this->getOverviewReport();
                break;
        }

        return view('admin.reports.index', compact('reportType', 'dateFrom', 'dateTo', 'data'));
    }

    private function getOverviewReport()
    {
        return [
            'total_case_orders' => CaseOrder::count(),
            'completed_cases' => CaseOrder::where('status', 'completed')->count(),
            'pending_cases' => CaseOrder::whereIn('status', ['initial', 'for pickup', 'for appointment', 'in-progress'])->count(),
            'total_appointments' => Appointment::count(),
            'completed_appointments' => Appointment::where('work_status', 'completed')->count(),
            'total_revenue' => Billing::where('payment_status', 'paid')->sum('total_amount'),
            'pending_revenue' => Billing::where('payment_status', 'unpaid')->sum('total_amount'),
            'total_deliveries' => Delivery::count(),
            'completed_deliveries' => Delivery::where('delivery_status', 'delivered')->count(),
            'total_clinics' => Clinic::count(),
            'total_technicians' => User::where('role', 'technician')->count(),
            'total_riders' => User::where('role', 'rider')->count(),
            'low_stock_materials' => Material::where('status', 'low stock')->count(),
            'out_of_stock_materials' => Material::where('status', 'out of stock')->count(),
        ];
    }

    private function getCaseOrdersReport($dateFrom, $dateTo)
    {
        $caseOrders = CaseOrder::with(['clinic', 'patient'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        $statusBreakdown = CaseOrder::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $caseTypeBreakdown = CaseOrder::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('case_type', DB::raw('count(*) as count'))
            ->groupBy('case_type')
            ->orderBy('count', 'desc')
            ->get();

        return [
            'case_orders' => $caseOrders,
            'total_cases' => $caseOrders->count(),
            'status_breakdown' => $statusBreakdown,
            'case_type_breakdown' => $caseTypeBreakdown,
        ];
    }

    private function getRevenueReport($dateFrom, $dateTo)
    {
        $billings = Billing::with(['appointment.caseOrder.clinic'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $billings->where('payment_status', 'paid')->sum('total_amount');
        $pendingRevenue = $billings->where('payment_status', 'unpaid')->sum('total_amount');
        $partialRevenue = $billings->where('payment_status', 'partial')->sum('total_amount');

        $revenueByClinic = $billings->where('payment_status', 'paid')
            ->groupBy(function ($item) {
                return $item->appointment->caseOrder->clinic->clinic_name;
            })
            ->map(function ($group) {
                return $group->sum('total_amount');
            })
            ->sortDesc()
            ->take(10);

        $revenueByMonth = Billing::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return [
            'billings' => $billings,
            'total_revenue' => $totalRevenue,
            'pending_revenue' => $pendingRevenue,
            'partial_revenue' => $partialRevenue,
            'revenue_by_clinic' => $revenueByClinic,
            'revenue_by_month' => $revenueByMonth,
        ];
    }

    private function getMaterialsReport($dateFrom, $dateTo)
    {
        $materials = Material::all();

        $materialUsages = DB::table('material_usages')
            ->join('materials', 'material_usages.material_id', '=', 'materials.material_id')
            ->join('appointments', 'material_usages.appointment_id', '=', 'appointments.appointment_id')
            ->whereBetween('material_usages.created_at', [$dateFrom, $dateTo])
            ->select(
                'materials.material_name',
                'materials.unit',
                DB::raw('SUM(material_usages.quantity_used) as total_used'),
                DB::raw('SUM(material_usages.quantity_used * materials.price) as total_cost')
            )
            ->groupBy('materials.material_id', 'materials.material_name', 'materials.unit')
            ->orderBy('total_used', 'desc')
            ->get();

        $lowStockMaterials = Material::where('status', 'low stock')
            ->orWhere('status', 'out of stock')
            ->get();

        $totalMaterialCost = $materialUsages->sum('total_cost');

        return [
            'materials' => $materials,
            'material_usages' => $materialUsages,
            'low_stock_materials' => $lowStockMaterials,
            'total_material_cost' => $totalMaterialCost,
        ];
    }

    private function getClinicPerformanceReport($dateFrom, $dateTo)
    {
        $clinicStats = Clinic::select('clinics.*')
            ->withCount(['caseOrders as total_orders' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->withCount(['caseOrders as completed_orders' => function ($query) use ($dateFrom, $dateTo) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->with(['caseOrders' => function ($query) use ($dateFrom, $dateTo) {
                $query->with('appointments.billing')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->having('total_orders', '>', 0)
            ->orderBy('total_orders', 'desc')
            ->get();

        // Calculate revenue for each clinic
        $clinicStats = $clinicStats->map(function ($clinic) {
            $revenue = 0;
            foreach ($clinic->caseOrders as $caseOrder) {
                foreach ($caseOrder->appointments as $appointment) {
                    if ($appointment->billing && $appointment->billing->payment_status === 'paid') {
                        $revenue += $appointment->billing->total_amount;
                    }
                }
            }
            $clinic->total_revenue = $revenue;
            return $clinic;
        });

        return [
            'clinic_stats' => $clinicStats,
        ];
    }

    private function getTechnicianPerformanceReport($dateFrom, $dateTo)
    {
        $technicianStats = User::where('role', 'technician')
            ->withCount(['appointments as total_appointments' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->withCount(['appointments as completed_appointments' => function ($query) use ($dateFrom, $dateTo) {
                $query->where('work_status', 'completed')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->with(['appointments' => function ($query) use ($dateFrom, $dateTo) {
                $query->with('materialUsages.material')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->having('total_appointments', '>', 0)
            ->orderBy('completed_appointments', 'desc')
            ->get();

        // Calculate materials used for each technician
        $technicianStats = $technicianStats->map(function ($technician) {
            $materialsUsed = 0;
            $totalCost = 0;
            foreach ($technician->appointments as $appointment) {
                $materialsUsed += $appointment->materialUsages->count();
                $totalCost += $appointment->total_material_cost;
            }
            $technician->materials_used = $materialsUsed;
            $technician->total_material_cost = $totalCost;
            return $technician;
        });

        return [
            'technician_stats' => $technicianStats,
        ];
    }

    private function getDeliveryPerformanceReport($dateFrom, $dateTo)
    {
        $riderStats = User::where('role', 'rider')
            ->withCount(['deliveries as total_deliveries' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->withCount(['deliveries as completed_deliveries' => function ($query) use ($dateFrom, $dateTo) {
                $query->where('delivery_status', 'delivered')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->withCount(['pickups as total_pickups' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->withCount(['pickups as completed_pickups' => function ($query) use ($dateFrom, $dateTo) {
                $query->where('status', 'picked-up')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->get()
            ->filter(function ($rider) {
                return $rider->total_deliveries > 0 || $rider->total_pickups > 0;
            });

        $deliveryStatusBreakdown = Delivery::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('delivery_status', DB::raw('count(*) as count'))
            ->groupBy('delivery_status')
            ->get()
            ->pluck('count', 'delivery_status')
            ->toArray();

        return [
            'rider_stats' => $riderStats,
            'delivery_status_breakdown' => $deliveryStatusBreakdown,
        ];
    }

    public function exportPdf(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = [];

        switch ($reportType) {
            case 'case-orders':
                $data = $this->getCaseOrdersReport($dateFrom, $dateTo);
                break;
            case 'revenue':
                $data = $this->getRevenueReport($dateFrom, $dateTo);
                break;
            case 'materials':
                $data = $this->getMaterialsReport($dateFrom, $dateTo);
                break;
            case 'clinic-performance':
                $data = $this->getClinicPerformanceReport($dateFrom, $dateTo);
                break;
            case 'technician-performance':
                $data = $this->getTechnicianPerformanceReport($dateFrom, $dateTo);
                break;
            case 'delivery-performance':
                $data = $this->getDeliveryPerformanceReport($dateFrom, $dateTo);
                break;
            default:
                $data = $this->getOverviewReport();
                break;
        }

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'reportType' => $reportType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'data' => $data,
            'generatedAt' => now()->format('M d, Y h:i A')
        ]);

        return $pdf->download('report-' . $reportType . '-' . date('Y-m-d') . '.pdf');
    }


    /**
     * Show print preview of report (opens in new tab)
     */
    public function print(Request $request)
    {
        $reportType = $request->get('type', 'overview');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = [];

        switch ($reportType) {
            case 'case-orders':
                $data = $this->getCaseOrdersReport($dateFrom, $dateTo);
                break;
            case 'revenue':
                $data = $this->getRevenueReport($dateFrom, $dateTo);
                break;
            case 'materials':
                $data = $this->getMaterialsReport($dateFrom, $dateTo);
                break;
            case 'clinic-performance':
                $data = $this->getClinicPerformanceReport($dateFrom, $dateTo);
                break;
            case 'technician-performance':
                $data = $this->getTechnicianPerformanceReport($dateFrom, $dateTo);
                break;
            case 'delivery-performance':
                $data = $this->getDeliveryPerformanceReport($dateFrom, $dateTo);
                break;
            default:
                $data = $this->getOverviewReport();
                break;
        }

        return view('admin.reports.print', [
            'reportType' => $reportType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'data' => $data
        ]);
    }


    // Case Orders Detail
    public function caseOrdersDetail(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getCaseOrdersReport($dateFrom, $dateTo);

        return view('admin.reports.details.case-orders', compact('dateFrom', 'dateTo', 'data'));
    }

    public function printCaseOrdersDetail(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getCaseOrdersReport($dateFrom, $dateTo);

        return view('admin.reports.details.print-case-orders', compact('dateFrom', 'dateTo', 'data'));
    }

    public function caseOrdersDetailPdf(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getCaseOrdersReport($dateFrom, $dateTo);

        $pdf = Pdf::loadView('admin.reports.pdf.case-orders-detail', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'data' => $data,
            'generatedAt' => now()->format('M d, Y h:i A')
        ])->setPaper('a4', 'landscape');

        return $pdf->download('case-orders-report-' . date('Y-m-d') . '.pdf');
    }

    // Revenue Detail
    public function revenueDetail(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getRevenueReport($dateFrom, $dateTo);

        return view('admin.reports.details.revenue', compact('dateFrom', 'dateTo', 'data'));
    }


    public function printRevenueDetail(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getRevenueReport($dateFrom, $dateTo);

        return view('admin.reports.details.print-revenue', compact('dateFrom', 'dateTo', 'data'));
    }

    public function revenueDetailPdf(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getRevenueReport($dateFrom, $dateTo);

        $pdf = Pdf::loadView('admin.reports.pdf.revenue-detail', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'data' => $data,
            'generatedAt' => now()->format('M d, Y h:i A')
        ])->setPaper('a4', 'landscape');

        return $pdf->download('revenue-report-' . date('Y-m-d') . '.pdf');
    }

    // Materials Detail
    public function materialsDetail(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getMaterialsReport($dateFrom, $dateTo);

        return view('admin.reports.details.materials', compact('dateFrom', 'dateTo', 'data'));
    }

    public function printMaterialsDetail(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getMaterialsReport($dateFrom, $dateTo);

        return view('admin.reports.details.print-materials', compact('dateFrom', 'dateTo', 'data'));
    }

    public function materialsDetailPdf(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $data = $this->getMaterialsReport($dateFrom, $dateTo);

        $pdf = Pdf::loadView('admin.reports.pdf.materials-detail', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'data' => $data,
            'generatedAt' => now()->format('M d, Y h:i A')
        ])->setPaper('a4', 'portrait');

        return $pdf->download('materials-report-' . date('Y-m-d') . '.pdf');
    }
}

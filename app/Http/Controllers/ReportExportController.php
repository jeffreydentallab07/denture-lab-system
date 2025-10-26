<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Delivery;
use App\Models\Billing;
use App\Models\CaseOrder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportExportController extends Controller
{
    /**
     * Show the reports page (form + export buttons)
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Generate reports (just preview data between dates)
     */
    public function generate(Request $request)
    {
        $type = $request->input('type');
        $from = $request->input('from');
        $to   = $request->input('to');

        $reports = $this->getReports($type, $from, $to);

        return view('reports.index', compact('reports', 'type', 'from', 'to'));
    }

    /**
     * Export Excel file
     */
    public function exportExcel(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');
        $type = $request->query('type');

        $reports = $this->getReports($type, $from, $to);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers + fields based on report type
        switch ($type) {
            case 'appointments':
                $headers = ['ID', 'Patient Name', 'Schedule Date', 'Status'];
                $fields  = ['id', 'patient_name', 'schedule_datetime', 'status'];
                break;
            case 'deliveries':
                $headers = ['ID', 'Case Order ID', 'Delivered By', 'Created At'];
                $fields  = ['id', 'case_order_id', 'delivered_by', 'created_at'];
                break;
            case 'billing':
                $headers = ['ID', 'Case Order ID', 'Amount', 'Created At'];
                $fields  = ['id', 'case_order_id', 'amount', 'created_at'];
                break;
            case 'caseorder':
                $headers = ['ID', 'Clinic Name', 'Job Type', 'Created At'];
                $fields  = ['id', 'clinic_name', 'job_type', 'created_at'];
                break;
            default:
                $headers = ['ID', 'Created At'];
                $fields  = ['id', 'created_at'];
                break;
        }

        // Write headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Write data
        $row = 2;
        foreach ($reports as $report) {
            $col = 'A';
            foreach ($fields as $field) {
                $sheet->setCellValue($col . $row, $report->$field ?? 'N/A');
                $col++;
            }
            $row++;
        }

        // Generate Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = "{$type}_report.xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export PDF file
     */
    public function exportPdf(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');
        $type = $request->query('type');

        $reports = $this->getReports($type, $from, $to);

        $pdf = Pdf::loadView('reports.pdf', compact('reports', 'type', 'from', 'to'));
        return $pdf->download("{$type}_report.pdf");
    }

    /**
     * Internal helper: fetch reports
     */
    private function getReports($type, $from, $to)
    {
        switch ($type) {
            case 'appointments':
                return Appointment::whereBetween('schedule_datetime', [$from, $to])->get();
            case 'deliveries':
                return Delivery::whereBetween('created_at', [$from, $to])->get();
            case 'billing':
                return Billing::whereBetween('created_at', [$from, $to])->get();
            case 'caseorder':
                return CaseOrder::whereBetween('created_at', [$from, $to])->get();
            default:
                return collect();
        }
    }
}

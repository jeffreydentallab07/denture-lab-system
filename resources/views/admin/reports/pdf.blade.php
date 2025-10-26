<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ ucfirst(str_replace('-', ' ', $reportType)) }} Report</title>
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            color: #666;
            margin: 5px 0;
        }

        .info-box {
            background: #f3f4f6;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background: #1e40af;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        .summary-box {
            background: #eff6ff;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #1e40af;
        }

        .summary-box h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #666;
        }

        .stat-value {
            font-weight: bold;
            color: #1e40af;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ ucfirst(str_replace('-', ' ', $reportType)) }} Report</h1>
        <p>Jeffrey Dental Lab Management System</p>
        <p>Report Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{
            \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <!-- Report Content -->
    @if($reportType === 'overview')
    <div class="summary-box">
        <h3>Case Orders Summary</h3>
        <div class="stat-row">
            <span class="stat-label">Total Cases:</span>
            <span class="stat-value">{{ $data['total_case_orders'] }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Completed:</span>
            <span class="stat-value">{{ $data['completed_cases'] }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Pending:</span>
            <span class="stat-value">{{ $data['pending_cases'] }}</span>
        </div>
    </div>

    <div class="summary-box">
        <h3>Revenue Summary</h3>
        <div class="stat-row">
            <span class="stat-label">Total Revenue:</span>
            <span class="stat-value">₱{{ number_format($data['total_revenue'], 2) }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Pending Revenue:</span>
            <span class="stat-value">₱{{ number_format($data['pending_revenue'], 2) }}</span>
        </div>
    </div>

    <div class="summary-box">
        <h3>Operational Summary</h3>
        <div class="stat-row">
            <span class="stat-label">Total Appointments:</span>
            <span class="stat-value">{{ $data['total_appointments'] }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Total Deliveries:</span>
            <span class="stat-value">{{ $data['total_deliveries'] }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Total Clinics:</span>
            <span class="stat-value">{{ $data['total_clinics'] }}</span>
        </div>
    </div>

    @elseif($reportType === 'case-orders')
    <div class="summary-box">
        <h3>Case Orders Summary</h3>
        <div class="stat-row">
            <span class="stat-label">Total Cases in Period:</span>
            <span class="stat-value">{{ $data['total_cases'] }}</span>
        </div>
    </div>

    <h3>Case Orders Details</h3>
    <table>
        <thead>
            <tr>
                <th>Case No.</th>
                <th>Clinic</th>
                <th>Patient</th>
                <th>Case Type</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['case_orders'] as $case)
            <tr>
                <td>CASE-{{ str_pad($case->co_id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $case->clinic->clinic_name }}</td>
                <td>{{ $case->patient->name ?? 'N/A' }}</td>
                <td>{{ $case->case_type }}</td>
                <td>{{ ucfirst(str_replace('-', ' ', $case->status)) }}</td>
                <td>{{ $case->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($reportType === 'revenue')
    <div class="summary-box">
        <h3>Revenue Summary</h3>
        <div class="stat-row">
            <span class="stat-label">Total Revenue (Paid):</span>
            <span class="stat-value">₱{{ number_format($data['total_revenue'], 2) }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Pending Payment:</span>
            <span class="stat-value">₱{{ number_format($data['pending_revenue'], 2) }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Partial Payment:</span>
            <span class="stat-value">₱{{ number_format($data['partial_revenue'], 2) }}</span>
        </div>
    </div>

    <h3>Billing Records</h3>
    <table>
        <thead>
            <tr>
                <th>Billing ID</th>
                <th>Clinic</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['billings'] as $billing)
            <tr>
                <td>BILL-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $billing->appointment->caseOrder->clinic->clinic_name }}</td>
                <td>₱{{ number_format($billing->total_amount, 2) }}</td>
                <td>{{ ucfirst($billing->payment_status) }}</td>
                <td>{{ $billing->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($reportType === 'materials')
    <div class="summary-box">
        <h3>Materials Summary</h3>
        <div class="stat-row">
            <span class="stat-label">Total Material Cost:</span>
            <span class="stat-value">₱{{ number_format($data['total_material_cost'], 2) }}</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Low/Out of Stock Materials:</span>
            <span class="stat-value">{{ $data['low_stock_materials']->count() }}</span>
        </div>
    </div>

    <h3>Material Usage</h3>
    <table>
        <thead>
            <tr>
                <th>Material Name</th>
                <th>Total Used</th>
                <th>Unit</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['material_usages'] as $usage)
            <tr>
                <td>{{ $usage->material_name }}</td>
                <td>{{ $usage->total_used }}</td>
                <td>{{ $usage->unit }}</td>
                <td>₱{{ number_format($usage->total_cost, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($reportType === 'clinic-performance')
    <h3>Clinic Performance Rankings</h3>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Clinic Name</th>
                <th>Total Orders</th>
                <th>Completed</th>
                <th>Completion Rate</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['clinic_stats'] as $index => $clinic)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $clinic->clinic_name }}</td>
                <td>{{ $clinic->total_orders }}</td>
                <td>{{ $clinic->completed_orders }}</td>
                <td>{{ $clinic->total_orders > 0 ? number_format(($clinic->completed_orders / $clinic->total_orders *
                    100), 1) : 0 }}%</td>
                <td>₱{{ number_format($clinic->total_revenue, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($reportType === 'technician-performance')
    <h3>Technician Performance Rankings</h3>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Technician Name</th>
                <th>Total Appointments</th>
                <th>Completed</th>
                <th>Completion Rate</th>
                <th>Materials Used</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['technician_stats'] as $index => $technician)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $technician->name }}</td>
                <td>{{ $technician->total_appointments }}</td>
                <td>{{ $technician->completed_appointments }}</td>
                <td>{{ $technician->total_appointments > 0 ? number_format(($technician->completed_appointments /
                    $technician->total_appointments * 100), 1) : 0 }}%</td>
                <td>{{ $technician->materials_used }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($reportType === 'delivery-performance')
    <h3>Rider Performance Rankings</h3>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Rider Name</th>
                <th>Total Deliveries</th>
                <th>Completed</th>
                <th>Total Pickups</th>
                <th>Completed Pickups</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['rider_stats'] as $index => $rider)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $rider->name }}</td>
                <td>{{ $rider->total_deliveries }}</td>
                <td>{{ $rider->completed_deliveries }}</td>
                <td>{{ $rider->total_pickups }}</td>
                <td>{{ $rider->completed_pickups }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Jeffrey Dental Lab - Confidential Report - Page 1</p>
    </div>
</body>

</html>
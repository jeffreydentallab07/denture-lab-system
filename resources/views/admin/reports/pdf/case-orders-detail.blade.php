<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Case Orders Report - Detailed Breakdown</title>
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 20px;
        }

        .header p {
            color: #666;
            margin: 3px 0;
            font-size: 9px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-item {
            display: table-cell;
            padding: 10px;
            text-align: center;
            background: #f3f4f6;
            border: 1px solid #ddd;
        }

        .summary-item h3 {
            margin: 0 0 5px 0;
            font-size: 9px;
            color: #666;
        }

        .summary-item p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background: #1e40af;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
        }

        td {
            padding: 6px 5px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin: 15px 0 8px 0;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 5px;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>Case Orders Report - Detailed Breakdown</h1>
        <p>Jeffrey Dental Lab Management System</p>
        <p>Report Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{
            \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <!-- Executive Summary -->
    <div class="summary-grid">
        <div class="summary-item">
            <h3>Total Cases</h3>
            <p>{{ $data['total_cases'] }}</p>
        </div>
        @foreach($data['status_breakdown'] as $status => $count)
        <div class="summary-item">
            <h3>{{ ucfirst(str_replace('-', ' ', $status)) }}</h3>
            <p>{{ $count }}</p>
        </div>
        @endforeach
    </div>

    <!-- Case Type Breakdown -->
    <h2 class="section-title">Case Type Analysis</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Case Type</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['case_type_breakdown'] as $index => $type)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $type->case_type }}</td>
                <td><strong>{{ $type->count }}</strong></td>
                <td>{{ $data['total_cases'] > 0 ? number_format(($type->count / $data['total_cases']) * 100, 1) : 0 }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Detailed Case List -->
    <h2 class="section-title">All Case Orders ({{ $data['total_cases'] }})</h2>
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
                <td><strong>CASE-{{ str_pad($case->co_id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                <td>{{ $case->clinic->clinic_name }}</td>
                <td>{{ $case->patient->name ?? 'N/A' }}</td>
                <td>{{ $case->case_type }}</td>
                <td>
                    <span
                        class="badge {{ $case->status === 'completed' ? 'badge-completed' : ($case->status === 'cancelled' ? 'badge-cancelled' : 'badge-pending') }}">
                        {{ ucfirst(str_replace('-', ' ', $case->status)) }}
                    </span>
                </td>
                <td>{{ $case->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Jeffrey Dental Lab - Confidential Report - Page 1 of 1</p>
    </div>
</body>

</html>
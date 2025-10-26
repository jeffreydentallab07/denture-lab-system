<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Revenue Report - Detailed Breakdown</title>
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
            border-bottom: 3px solid #059669;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #059669;
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
            border: 1px solid #ddd;
        }

        .summary-item h3 {
            margin: 0 0 5px 0;
            font-size: 9px;
            color: #666;
        }

        .summary-item p {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .revenue-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .revenue-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .revenue-partial {
            background: #fed7aa;
            color: #9a3412;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background: #059669;
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
            color: #059669;
            margin: 15px 0 8px 0;
            border-bottom: 2px solid #059669;
            padding-bottom: 5px;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-unpaid {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-partial {
            background: #fed7aa;
            color: #9a3412;
        }

        .total-row {
            background: #d1fae5 !important;
            font-weight: bold;
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
        <h1>Revenue Report - Detailed Breakdown</h1>
        <p>Jeffrey Dental Lab Management System</p>
        <p>Report Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{
            \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <!-- Financial Overview -->
    <div class="summary-grid">
        <div class="summary-item revenue-paid">
            <h3>Total Revenue (Paid)</h3>
            <p>Php {{ number_format($data['total_revenue'], 2) }}</p>
        </div>
        <div class="summary-item revenue-pending">
            <h3>Pending Payment</h3>
            <p>Php {{ number_format($data['pending_revenue'], 2) }}</p>
        </div>
        <div class="summary-item revenue-partial">
            <h3>Partial Payment</h3>
            <p>Php {{ number_format($data['partial_revenue'], 2) }}</p>
        </div>
    </div>

    <!-- Top Revenue Clinics -->
    <h2 class="section-title">Top 10 Revenue-Generating Clinics</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Clinic Name</th>
                <th>Total Revenue</th>
                <th>% of Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['revenue_by_clinic'] as $index => $revenue)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $index }}</td>
                <td><strong>Php {{ number_format($revenue, 2) }}</strong></td>
                <td>{{ $data['total_revenue'] > 0 ? number_format(($revenue / $data['total_revenue']) * 100, 1) : 0 }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Monthly Revenue -->
    <h2 class="section-title">Monthly Revenue Trend</h2>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['revenue_by_month'] as $month)
            <tr>
                <td>{{ date('F Y', mktime(0, 0, 0, $month->month, 1, $month->year)) }}</td>
                <td><strong>Php {{ number_format($month->total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- All Billing Records -->
    <h2 class="section-title">All Billing Records ({{ $data['billings']->count() }})</h2>
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
                <td><strong>BILL-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                <td>{{ $billing->appointment->caseOrder->clinic->clinic_name }}</td>
                <td><strong>Php {{ number_format($billing->total_amount, 2) }}</strong></td>
                <td>
                    <span
                        class="badge {{ $billing->payment_status === 'paid' ? 'badge-paid' : ($billing->payment_status === 'partial' ? 'badge-partial' : 'badge-unpaid') }}">
                        {{ ucfirst($billing->payment_status) }}
                    </span>
                </td>
                <td>{{ $billing->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2"><strong>TOTAL REVENUE (PAID):</strong></td>
                <td colspan="3"><strong>Php {{ number_format($data['total_revenue'], 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Jeffrey Dental Lab - Confidential Financial Report - Page 1 of 1</p>
    </div>
</body>

</html>
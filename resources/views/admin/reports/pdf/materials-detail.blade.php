<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Materials Report - Detailed Breakdown</title>
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
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #7c3aed;
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
            color: #7c3aed;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background: #7c3aed;
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
            color: #7c3aed;
            margin: 15px 0 8px 0;
            border-bottom: 2px solid #7c3aed;
            padding-bottom: 5px;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-available {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-low {
            background: #fed7aa;
            color: #9a3412;
        }

        .badge-out {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-section {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 10px;
            margin-bottom: 15px;
        }

        .alert-section h2 {
            color: #991b1b;
            margin: 0 0 10px 0;
            font-size: 12px;
        }

        .total-row {
            background: #ede9fe !important;
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
        <h1>Materials Report - Detailed Breakdown</h1>
        <p>Jeffrey Dental Lab Management System</p>
        <p>Report Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{
            \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
        <p>Generated: {{ $generatedAt }}</p>
    </div>

    <!-- Inventory Overview -->
    <div class="summary-grid">
        <div class="summary-item">
            <h3>Total Materials</h3>
            <p>{{ $data['materials']->count() }}</p>
        </div>
        <div class="summary-item">
            <h3>Total Material Cost</h3>
            <p>Php {{ number_format($data['total_material_cost'], 2) }}</p>
        </div>
        <div class="summary-item">
            <h3>Low/Out of Stock</h3>
            <p style="color: #dc2626;">{{ $data['low_stock_materials']->count() }}</p>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($data['low_stock_materials']->count() > 0)
    <div class="alert-section">
        <h2>⚠️ CRITICAL INVENTORY ALERTS</h2>
        <p style="font-size: 9px; margin: 0;">The following materials require immediate attention and restocking:</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Material Name</th>
                <th>Current Qty</th>
                <th>Unit</th>
                <th>Status</th>
                <th>Unit Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['low_stock_materials'] as $material)
            <tr style="{{ $material->status === 'out of stock' ? 'background: #fee2e2;' : 'background: #fed7aa;' }}">
                <td><strong>{{ $material->material_name }}</strong></td>
                <td style="color: #dc2626; font-weight: bold;">{{ $material->quantity }}</td>
                <td>{{ $material->unit }}</td>
                <td>
                    <span class="badge {{ $material->status === 'out of stock' ? 'badge-out' : 'badge-low' }}">
                        {{ ucfirst($material->status) }}
                    </span>
                </td>
                <td>Php {{ number_format($material->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Top Material Usage -->
    <h2 class="section-title">Top 10 Most Used Materials</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Material Name</th>
                <th>Total Used</th>
                <th>Unit</th>
                <th>Total Cost</th>
                <th>% of Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['material_usages']->take(10) as $index => $usage)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $usage->material_name }}</strong></td>
                <td><strong>{{ $usage->total_used }}</strong></td>
                <td>{{ $usage->unit }}</td>
                <td><strong>Php {{ number_format($usage->total_cost, 2) }}</strong></td>
                <td>{{ $data['total_material_cost'] > 0 ? number_format(($usage->total_cost /
                    $data['total_material_cost']) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Complete Material Usage -->
    <h2 class="section-title">Complete Material Usage Report</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Material Name</th>
                <th>Total Used</th>
                <th>Unit</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['material_usages'] as $index => $usage)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $usage->material_name }}</td>
                <td><strong>{{ $usage->total_used }}</strong></td>
                <td>{{ $usage->unit }}</td>
                <td><strong>Php {{ number_format($usage->total_cost, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4"><strong>TOTAL MATERIAL COST:</strong></td>
                <td><strong>Php {{ number_format($data['total_material_cost'], 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Complete Inventory Status -->
    <h2 class="section-title">Complete Inventory Status</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Material Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Total Value</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalInventoryValue = 0;
            @endphp
            @foreach($data['materials'] as $index => $material)
            @php
            $totalInventoryValue += ($material->quantity * $material->price);
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $material->material_name }}</td>
                <td><strong>{{ $material->quantity }}</strong></td>
                <td>{{ $material->unit }}</td>
                <td>Php {{ number_format($material->price, 2) }}</td>
                <td><strong>Php {{ number_format($material->quantity * $material->price, 2) }}</strong></td>
                <td>
                    <span
                        class="badge {{ $material->status === 'available' ? 'badge-available' : ($material->status === 'low stock' ? 'badge-low' : 'badge-out') }}">
                        {{ ucfirst($material->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5"><strong>TOTAL INVENTORY VALUE:</strong></td>
                <td colspan="2"><strong>Php {{ number_format($totalInventoryValue, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Jeffrey Dental Lab - Confidential Inventory Report - Page 1 of 1</p>
    </div>
</body>

</html>
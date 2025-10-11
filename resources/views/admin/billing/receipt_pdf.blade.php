<!DOCTYPE html>,
<html>

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
    <title>Delivery Receipt</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #222;
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }

        .receipt-container {
            background: #fff;
            border: 2px solid #189ab4;
            border-radius: 12px;
            max-width: 700px;
            margin: 30px auto;
            padding: 32px 36px 24px 36px;
            box-shadow: 0 4px 24px #189ab422;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #189ab4;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-right: 18px;
        }

        .lab-info {
            flex: 1;
        }

        .lab-info h1 {
            font-size: 1.6rem;
            color: #189ab4;
            margin: 0 0 2px 0;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .lab-info p {
            font-size: 11px;
            margin: 0;
            color: #444;
            line-height: 1.4;
        }

        .receipt-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #05445e;
            margin-bottom: 8px;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .meta .label {
            color: #555;
            font-weight: 600;
        }

        .meta .value {
            color: #d7263d;
            font-weight: bold;
        }

        .details {
            margin-bottom: 18px;
            font-size: 13px;
        }

        .details span {
            color: #05445e;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        th,
        td {
            padding: 7px 6px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 13px;
        }

        th {
            background: #189ab4;
            color: #fff;
            font-weight: bold;
            border-top: 2px solid #189ab4;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .totals td {
            font-weight: bold;
            color: #05445e;
            background: #f1f8fa;
        }

        .totals .label {
            text-align: right;
        }

        .totals .amount {
            text-align: right;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            font-size: 12px;
        }

        .footer .sign-box {
            text-align: center;
        }

        .footer .sign-line {
            border-top: 1px solid #222;
            width: 160px;
            margin: 18px auto 4px auto;
            height: 0;
        }

        .notes {
            margin-top: 18px;
            font-size: 11px;
            color: #666;
            background: #e3f6f9;
            border-left: 4px solid #189ab4;
            padding: 10px 16px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    @if($billing)
    <div class="receipt-container">
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Lab Logo">
            <div class="lab-info">
                <h1>Jeffrey Dental Laboratory</h1>
                <p>
                    Zone 7 Bulua District 1<br>
                    9000 Cagayan de Oro City (CAPITAL)<br>
                    Misamis Oriental Philippines<br>
                    <strong>JEFFREY D. GELLANGAO - PROP.</strong><br>
                    Non Vat Reg., TIN 408-400-984-00000
                </p>
            </div>
        </div>

        <div class="receipt-title">Delivery Receipt</div>
        <div class="meta">
            <div><span class="label">Billing No:</span> <span class="value">{{ $billing->id }}</span></div>
            <div><span class="label">Date:</span> <span>{{ $billing->created_at->format('F j, Y g:ia') }}</span></div>
        </div>

        @php
        $appointment = $billing->appointment;
        $caseOrder = $appointment->caseOrder ?? null;
        $material = $appointment->material ?? null;
        $dentist = $caseOrder && $caseOrder->dentist ? $caseOrder->dentist->name : 'N/A';
        $clinic = $caseOrder && $caseOrder->clinic ? $caseOrder->clinic->clinic_name : 'N/A';
        $clinicAddress = $caseOrder && $caseOrder->clinic ? $caseOrder->clinic->address : 'N/A';
        $technician = $appointment->technician ? $appointment->technician->name : 'N/A';
        @endphp

        <div class="details">
            <div>Delivered to: <span>{{ $dentist }}{{ $dentist && $clinic ? ' / ' : '' }}{{ $clinic }}</span></div>
            <div>Address: <span>{{ $clinicAddress }}</span></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Description</th>
                    <th class="text-right">U-Price</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if($material)
                <tr>
                    <td>1</td>
                    <td>pc</td>
                    <td>{{ $material->material_name }}</td>
                    <td class="text-right">
                        @if($material->price)
                        {{ number_format($material->price, 2) }}
                        @else
                        {{ number_format($billing->total_amount, 2) }}
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
                </tr>
                @else
                <tr>
                    <td>1</td>
                    <td>pc</td>
                    <td>Service / Product</td>
                    <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="totals">
                    <td colspan="4" class="label">Subtotal</td>
                    <td class="amount">{{ number_format($billing->total_amount, 2) }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="4" class="label">VAT (12%)</td>
                    <td class="amount">{{ number_format($billing->total_amount * 0.12, 2) }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="4" class="label">Total Amount</td>
                    <td class="amount" style="font-size: 1.1em;">{{ number_format($billing->total_amount * 1.12, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top:40px; font-size:12px;">

            <!-- Top row: Left and Right signatures -->
            <div style="display:flex; justify-content:space-between;">
                <!-- Created by / Authorized Signature (Left) -->
                <div style="text-align:left;">
                    <div class="text-xs mb-1">Created by:</div>
                    <div class="font-semibold mb-2">JEFFREY GELLANGAO</div>
                    <div style="border-top:1px solid #222; width:160px;"></div>
                    <div class="text-xs mt-1">Authorized Signature</div>
                </div>


                <div style="text-align:right;">
                    <div class="text-xs mb-1">Delivered by:</div>
                    <div class="font-semibold mb-2">
                        @if($appointment->delivery && $appointment->delivery->rider)
                        {{ $appointment->delivery->rider->name }}
                        @else
                        Not Assigned
                        @endif
                    </div>
                    <div style="border-top:1px solid #222; width:160px; margin-left:auto;"></div>
                    <div class="text-xs mt-1">Rider / Delivery</div>
                </div>
            </div>

            <!-- Bottom row: Centered Received by / Client -->
            <div style="text-align:center; margin-top:60px;">
                <div class="text-xs mb-1">Received by:</div>
                <div class="font-semibold mb-2">&nbsp;</div>
                <div style="border-top:1px solid #222; width:160px; margin:0 auto;"></div>
                <div class="text-xs mt-1">Client</div>
            </div>

        </div>




    </div>
    @else
    <div style="text-align:center; padding: 40px; color: #888;">
        <p>Billing record not found.</p>
    </div>
    @endif
</body>

</html>
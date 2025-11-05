<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #3490dc;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .in-transit {
            background: #3490dc;
            color: white;
        }

        .delivered {
            background: #38c172;
            color: white;
        }

        .info-box {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3490dc;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #777;
            font-size: 12px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #3490dc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Delivery Update</h1>
        </div>
        <div class="content">
            @if($recipientType === 'patient')
            <p>Dear {{ $caseOrder->patient->name }},</p>
            @else
            <p>Dear {{ $caseOrder->clinic->clinic_name }},</p>
            @endif

            @if($status === 'in transit')
            <p><strong>Great news!</strong> Your dental case order is now on its way to you.</p>
            <div class="status-badge in-transit">🚚 In Transit</div>
            @elseif($status === 'delivered')
            <p><strong>Your order has been successfully delivered!</strong></p>
            <div class="status-badge delivered">✓ Delivered</div>
            @endif

            <div class="info-box">
                <strong>Order Details:</strong><br>
                <strong>Case Order:</strong> CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Case Type:</strong> {{ $caseOrder->case_type }}<br>
                @if($recipientType === 'patient')
                <strong>Clinic:</strong> {{ $caseOrder->clinic->clinic_name }}<br>
                @else
                <strong>Patient:</strong> {{ $caseOrder->patient->name }}<br>
                @endif
                <strong>Delivery Date:</strong> {{ $delivery->delivery_date->format('F j, Y') }}<br>
                @if($status === 'delivered' && $delivery->delivered_at)
                <strong>Delivered At:</strong> {{ $delivery->delivered_at->format('F j, Y g:i A') }}
                @endif
            </div>

            @if($status === 'in transit')
            <p><strong>Expected Delivery:</strong> {{ $delivery->delivery_date->format('F j, Y') }}</p>
            <p>Our rider is on the way to deliver your order. Please ensure someone is available to receive it.</p>
            @elseif($status === 'delivered')
            <p>Thank you for your patience. If you have any questions or concerns about your order, please don't
                hesitate to contact us.</p>
            @endif

            @if($recipientType === 'clinic')
            <a href="{{ route('clinic.case-orders.show', $caseOrder->co_id) }}" class="btn">View Order Details</a>
            @endif
        </div>
        <div class="footer">
            <p>This is an automated email from {{ config('app.name') }}. Please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>
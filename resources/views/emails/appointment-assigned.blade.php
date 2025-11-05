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
            background: #6574cd;
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

        .info-box {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #6574cd;
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
            background: #6574cd;
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
            <h1>Work Scheduled</h1>
        </div>
        <div class="content">
            <p>Dear {{ $caseOrder->clinic->clinic_name }},</p>

            <p>Good news! A technician has been assigned to work on your dental case order.</p>

            <div class="info-box">
                <strong>Appointment Details:</strong><br>
                <strong>Case Order:</strong> CASE-{{ str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Case Type:</strong> {{ $caseOrder->case_type }}<br>
                <strong>Patient:</strong> {{ $caseOrder->patient->name }}<br>
                <strong>Technician:</strong> {{ $appointment->technician->name }}<br>
                <strong>Scheduled Date:</strong> {{ $appointment->schedule_datetime->format('F j, Y g:i A') }}<br>
                <strong>Purpose:</strong> {{ $appointment->purpose ?? 'Dental case work' }}
            </div>

            <p>Our technician will begin working on your case as scheduled. We will notify you once the work is
                completed.</p>

            <a href="{{ route('clinic.case-orders.show', $caseOrder->co_id) }}" class="btn">View Order Details</a>
        </div>
        <div class="footer">
            <p>This is an automated email from {{ config('app.name') }}. Please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>
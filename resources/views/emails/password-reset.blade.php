<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #189ab4 0%, #127a95 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }

        .content h2 {
            color: #189ab4;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background-color: #189ab4;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
        }

        .button:hover {
            background-color: #127a95;
        }

        .alert-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .alert-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }

        .footer p {
            margin: 5px 0;
        }

        .link {
            color: #189ab4;
            word-break: break-all;
            font-size: 14px;
            display: block;
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🦷 Jeffrey Dental Laboratory</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Reset Your Password</h2>

            <p>Hello,</p>

            <p>We received a request to reset the password for your account associated with <strong>{{ $email
                    }}</strong>.</p>

            <p>Click the button below to reset your password:</p>

            <div style="text-align: center;">
                <a href="{{ url('password/reset', $token) }}?email={{ urlencode($email) }}" class="button">
                    Reset Password
                </a>
            </div>

            <p style="text-align: center; margin-top: 10px;">
                <small style="color: #6c757d;">Or copy and paste this link into your browser:</small>
            </p>
            <span class="link">{{ url('password/reset', $token) }}?email={{ urlencode($email) }}</span>

            <div class="alert-box">
                <p>⏰ <strong>Important:</strong> This password reset link will expire in 60 minutes for security
                    reasons.</p>
            </div>

            <p>If you did not request a password reset, please ignore this email. Your password will remain unchanged.
            </p>

            <p>For security reasons, we recommend that you:</p>
            <ul>
                <li>Use a strong, unique password</li>
                <li>Never share your password with anyone</li>
                <li>Enable two-factor authentication if available</li>
            </ul>

            <p>If you're having trouble with the button above, you can also reset your password by visiting our website
                and clicking "Forgot Password".</p>

        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Jeffrey Dental Laboratory</strong></p>
            <p>Your trusted partner in creating perfect smiles</p>
            <p style="margin-top: 20px;">
                <small>This is an automated email. Please do not reply to this message.</small>
            </p>
            <p>
                <small>&copy; 2024 Jeffrey Dental Laboratory. All rights reserved.</small>
            </p>
        </div>
    </div>

</body>

</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailData['otp'] }} Is Your OTP to Login On E-Commerce</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .alert-info {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #004085;
            padding: 10px;
            border-radius: 4px;
            margin: 15px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        @if(isset($mailData['is_vendor_login']) && $mailData['is_vendor_login'])
            <h2>Vendor Login Verification Required</h2>
            <p>A vendor has attempted to log in to the e-commerce platform.</p>
            <div class="alert-info">
                <strong>Vendor Name:</strong> {{ $mailData['vendor_name'] }}<br>
                <strong>Email:</strong> {{ $mailData['email'] }}
            </div>
            <p>Please share the OTP below with the vendor to complete their login verification:</p>
            <p class="otp">{{ $mailData['otp'] }}</p>
            <p style="color: #d9534f;"><strong>Important:</strong> Only share this OTP with the vendor if you recognize them.</p>
        @else
            <h2>Welcome to E-Commerce, {{ $mailData['user_name'] }}!</h2>
            <p>Please use the OTP below to verify your email address on E-Commerce.</p>
            <p class="otp">{{ $mailData['otp'] }}</p>
            <p>Don't share this code with anyone.</p>
        @endif
        <div class="footer">
            <p>If you didn't request this, please ignore this email.</p>
        </div>
    </div>

</body>
</html>

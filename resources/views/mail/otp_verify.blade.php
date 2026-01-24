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
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome to E-Commerce, {{ $mailData['user_name'] }}!</h2>
        <p>Please use the OTP below to verify your email address on E-Commerce.</p>
        <p class="otp">{{ $mailData['otp'] }}</p>
        <p>Don't share this code with anyone.</p>
        <div class="footer">
            <p>If you didn't request this, please ignore this email.</p>
        </div>
    </div>

</body>
</html>

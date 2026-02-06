<!DOCTYPE html>
<html>
<head>
    <style>
        .wrapper { background-color: #f8f9fa; padding: 40px 20px; font-family: sans-serif; }
        .card { max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; color: #333; margin-bottom: 20px; }
        .otp-box { background-color: #eef2ff; border: 2px dashed #4f46e5; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0; }
        .otp-code { font-size: 32px; font-weight: bold; color: #4f46e5; letter-spacing: 5px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 25px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <h2 class="header">Login Verification</h2>
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>You  requested a one-time password (OTP) to access your account.</p>
            
            <div class="otp-box">
                <div class="otp-code">{{ $user->otp }}</div>
            </div>

            <p>This code is sensitive. Please do not share it with anyone.</p>
            <hr style="border: none; border-top: 1px solid #eee;">
            <p class="footer">If you did not expect this email, please contact your administrator immediately.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .otp-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            color: #fff;
        }

        .otp-logo {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }

        .otp-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .otp-subtitle {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 25px;
        }

        .form-control {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #fff;
            text-align: center;
            font-size: 20px;
            letter-spacing: 5px;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.15);
            color: #fff;
            box-shadow: none;
        }

        .btn-verify {
            background: #3b82f6;
            border: none;
            padding: 10px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-verify:hover {
            background: #2563eb;
        }

        .btn-resend {
            background: #6c757d;
            border: none;
            padding: 8px 14px;
            font-size: 14px;
        }

        .btn-resend:hover {
            background: #5a6268;
        }

        .invalid-feedback {
            display: block;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="otp-card">

    <!-- Logo -->
    <div class="text-center mb-4">
        <a href="{{ route('user.home') }}">
            <img src="{{ publicPath(getSetting('site_logo_light')) }}"
                 alt="Logo"
                 class="otp-logo">
        </a>
    </div>

    <h3 class="otp-title text-center">Verify Email & OTP</h3>
    <p class="otp-subtitle text-center">
        Enter the One-Time Password sent to your email.
    </p>

    @if (isset($message))
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @endif

    @if ($success = session('success'))
        <div class="alert alert-success">
            {{ $success }}
        </div>
    @endif

    <form action="{{ route('auth.otp_verify') }}" method="POST">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email"
                   name="email"
                   value="{{ $email ?? old('email') }}"
                   class="form-control"
                   readonly>
        </div>

        <!-- OTP -->
        <div class="mb-3">
            <label class="form-label">OTP</label>
            <input type="text"
                   name="otp"
                   class="form-control @error('otp') is-invalid @enderror"
                   maxlength="6"
                   required>

            @error('otp')
                <div class="invalid-feedback text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-verify w-100">
            Verify
        </button>
    </form>

    @if (session('otp_verification_type') === 'vendor')
        <div class="text-center mt-4">
            <p class="mb-2 small text-light opacity-75">
                Didn’t receive the OTP?
            </p>

            <form action="{{ route('auth.resend_otp') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                <button type="submit" class="btn btn-resend">
                    Resend OTP
                </button>
            </form>
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

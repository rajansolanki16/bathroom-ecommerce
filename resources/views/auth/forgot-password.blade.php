<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
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

        .forgot-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            color: #fff;
        }

        .forgot-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .forgot-subtitle {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 25px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            box-shadow: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-submit {
            background: #3b82f6;
            border: none;
            padding: 10px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #2563eb;
        }

        .back-link {
            font-size: 13px;
            color: #cbd5e1;
            text-decoration: none;
        }

        .back-link:hover {
            color: #fff;
        }

        .invalid-feedback {
            display: block;
            font-size: 13px;
        }

        .otp-logo {
            max-width: 160px;
            height: auto;
            object-fit: contain;
        }
    </style>
</head>

<body>

    <div class="forgot-card">


        <!-- Logo -->
        <div class="text-center mb-4">
            <a href="{{ route('view.home') }}" class="d-inline-block">
                <img src="{{ publicPath(getSetting('site_logo_light')) }}" alt="Logo" class="otp-logo img-fluid">
            </a>
        </div>


        <h3 class="forgot-title">Forgot your password?</h3>
        <p class="forgot-subtitle">
            Enter your email address and we’ll send you a One-Time Password (OTP).
        </p>

        @if (session()->has('message'))
            <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}">
                {{ session('message') }}
            </div>
        @endif

        <livewire:forgot-password />

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="back-link" wire:navigate>
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

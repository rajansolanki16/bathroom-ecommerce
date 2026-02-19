<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            color: #fff;
        }

        .login-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .login-subtitle {
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

        .btn-login {
            background: #3b82f6;
            border: none;
            padding: 10px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #2563eb;
        }

        .forgot-link {
            font-size: 13px;
            color: #cbd5e1;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #fff;
        }

        .invalid-feedback {
            display: block;
            font-size: 13px;
        }

        .remember-check label {
            font-size: 14px;
        }

        .login-logo {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <img src="{{ publicPath(getSetting('site_logo_light')) }}"
             alt="Logo"
             class="login-logo">
    </div>

    <h3 class="login-title">{{ __('common.login_heading') }}</h3>
    <p class="login-subtitle">Welcome back! Please login to your account.</p>

    @if (session()->has('message'))
        <div class="alert {{ session()->get('status') == 'success' ? 'alert-success' : 'alert-danger' }}">
            {{ session('message') }}
        </div>
    @endif


        <!-- Logo -->
        <livewire:login-form />

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = event.currentTarget.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

</body>
</html>

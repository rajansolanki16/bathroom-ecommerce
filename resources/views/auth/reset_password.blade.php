<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Password</title>
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

        .reset-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            color: #fff;
        }

        .reset-logo {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }

        .reset-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .reset-subtitle {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 25px;
        }

        .form-control {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #fff;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.15);
            color: #fff;
            box-shadow: none;
        }

        .form-control::placeholder {
            color: rgba(255,255,255,0.6);
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

        .invalid-feedback {
            display: block;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="reset-card">

    <!-- Logo -->
    <div class="text-center mb-4">
        <a href="{{ route('user.home') }}">
            <img src="{{ publicPath(getSetting('site_logo_light')) }}"
                 alt="Logo"
                 class="reset-logo">
        </a>
    </div>

    <h3 class="reset-title text-center">Create New Password</h3>
    <p class="reset-subtitle text-center">
        Please enter your new password below.
    </p>

    @if (session()->has('message'))
        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}">
            {{ session('message') }}
        </div>
    @endif

    <form action="{{ route('auth.password') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- New Password -->
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <div class="input-group">
                <input type="password"
                       name="password"
                       id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Enter new password"
                       required>

                <button type="button"
                        class="btn btn-outline-light"
                        onclick="togglePassword('password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="invalid-feedback text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       placeholder="Confirm password"
                       required>

                <button type="button"
                        class="btn btn-outline-light"
                        onclick="togglePassword('password_confirmation', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password_confirmation')
                <div class="invalid-feedback text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-submit w-100">
            Submit
        </button>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');

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

<x-header :meta="array('title'=> 'Login', 'description'=> getSetting('page_home_meta_description'))" />

<main class="auth-page">
    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-card">
                <h1 class="auth-title">{{ __('common.login_heading') }}</h1>
                <p class="auth-subtitle">Welcome back! Please login to your account.</p>

                @if (session()->has('message'))
                    <div class="alert {{ session()->get('status') == 'success' ? 'alert-success' : 'alert-danger' }}">
                        {{ session('message') }}
                    </div>
                @endif

                <form action="{{ route('auth.login') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="auth-group">
                        <label>{{ __('common.username_or_email') }}</label>
                        <input type="text"
                            name="email"
                            value="{{ old('email') }}"
                            class="@error('email') is-invalid @enderror"
                            placeholder="Enter your email"
                            required>
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="auth-group password-group">
                        <label>{{ __('common.password') }}</label>
                        <input type="password"
                            name="password"
                            id="password"
                            class="@error('password') is-invalid @enderror"
                            placeholder="Enter password"
                            required>

                        <span class="toggle-password" onclick="togglePassword()">👁</span>

                        @error('password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember -->
                    <div class="auth-options">
                        <label class="remember">
                            <input type="checkbox" name="remember">
                            {{ __('common.remember_me') }}
                        </label>
                    </div>

                    <button type="submit" class="auth-btn">
                        {{ __('common.login_button') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<x-footer />

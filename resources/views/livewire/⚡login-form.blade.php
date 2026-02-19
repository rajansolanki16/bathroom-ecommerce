<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Support\Str;
use Carbon\Carbon;

new class extends Component {
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        // 1. Manual Validation with your custom messages
        $this->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'The email or username field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
        ]);

        $login_input = $this->email;
        $password = $this->password;
        $remember = $this->remember;

        // 2. Attempt Login (Check email first, then username)
        $authEmail = Auth::attempt(['email' => $login_input, 'password' => $password], $remember);
        $authUsername = Auth::attempt(['username' => $login_input, 'password' => $password], $remember);

        if ($authEmail || $authUsername) {
            $user = Auth::user();
            session()->regenerate();

            // 3. Admin/Salesman Redirect
            if ($user->hasRole('admin') || $user->hasRole('salesman')) {
                return redirect()->route('admin.dashboard');
            } 
            
            // 4. Vendor OTP Flow
            elseif ($user->hasRole('vendor')) {
                $otp = rand(100000, 999999);
                $token = Str::random(32);
                
                $user->otp = $otp;
                $user->token = $token;
                $user->remember_token = 2; // Mark as vendor login verification
                $user->otp_expires_at = Carbon::now()->addMinutes((int) getSetting('otp_expiry_time') ?? 30);
                $user->otp_attempts = 0;
                $user->save();

                session(['otp_verification_type' => 'vendor']);
                
                $mailData = [
                    'email' => $user->email,
                    'otp' => $otp,
                    'user_name' => $user->name,
                    'vendor_name' => $user->name,
                    'is_vendor_login' => true,
                ];
                
                Mail::to(getSetting('admin_email'))->send(new OTPMail($mailData));
                
                Auth::logout(); // Logout temporarily as per your logic
                
                return redirect()->route('view.otp_verify', $token)
                    ->with('message', 'Vendor login verification. Check admin email for OTP.')
                    ->with("email", $user->email);
            } 
            
            // Default Redirect
            else {
                return redirect()->route('admin.dashboard');
            }
        }

        // 5. Handle Failure
        throw ValidationException::withMessages([
            'email' => 'Invalid credentials.',
        ]);
    }
};
?>

<div>
    <form wire:submit="login">
        {{-- Show Flash Messages if any --}}
        @if (session()->has('message'))
            <div class="alert alert-info mb-3">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">{{ __('common.username_or_email') }}</label>
            <input type="text" 
                   wire:model.blur="email" 
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Enter your email or username">

            @error('email')
                <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('common.password') }}</label>
            <div class="input-group">
                <input type="password" 
                       wire:model.blur="password" 
                       id="password"
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Enter password">

                <button type="button" class="btn btn-outline-light" onclick="togglePassword()">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 remember-check">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" wire:model="remember" id="remember">
                <label class="form-check-label text-white" for="remember">
                    {{ __('common.remember_me') }}
                </label>
            </div>
            <a href="{{ route('view.forget_password') }}" class="forgot-link" wire:navigate>Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-login w-100" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="login">{{ __('common.login_button') }}</span>
            <span wire:loading wire:target="login">
                <span class="spinner-border spinner-border-sm"></span> Processing...
            </span>
        </button>
    </form>
</div>
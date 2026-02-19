<?php

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail; // Ensure this is the correct path to your Mail class

new class extends Component {
    public $email = '';

    public function sendOtp()
    {
        // 1. Validation with your custom messages
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'This email is not registered.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
        ]);

        // 2. Logic from your controller
        $user = User::where('email', $this->email)->first();

        $otp = rand(100000, 999999);
        $token = Str::random(32);

        $user->otp = $otp;
        $user->token = $token;
        $user->remember_token = 1;
        $user->otp_expires_at = Carbon::now()->addMinutes((int) getSetting('otp_expiry_time') ?? 30);
        $user->otp_attempts = 0;
        $user->save();

        $mailData = [
            'email' => $user->email,
            'otp' => $otp,
            'user_name' => $user->name,
        ];

        Mail::to($user->email)->send(new OTPMail($mailData));

        // 3. Redirect to verification
        return redirect()->route('view.otp_verify', $token)
            ->with('message', 'Otp Sent Successfully. Check your mail for verification!')
            ->with("email", $user->email);
    }
};
?>

<div>
    <form wire:submit="sendOtp" novalidate>
        {{-- Flash messages --}}
        @if (session()->has('message'))
            <div class="alert alert-info">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <label class="form-label">Email address</label>
            <input type="email" 
                   wire:model.blur="email"
                   class="form-control @error('email') is-invalid @enderror" 
                   placeholder="Enter your registered email">

            @error('email')
                <div class="invalid-feedback text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-submit w-100" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="sendOtp">Get OTP</span>
            <span wire:loading wire:target="sendOtp">
                <span class="spinner-border spinner-border-sm"></span> Sending...
            </span>
        </button>
    </form>
</div>
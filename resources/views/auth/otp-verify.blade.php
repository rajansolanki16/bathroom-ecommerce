<x-header :meta="array('title'=> 'Otp Verification', 'description'=> getSetting('page_home_meta_description'),'sco-allow'=> false)" />

<main>
    <section class="ko-loginRegister-section ko-register-section">
        <div class="ko-container">
            <div class="ko-loginRegister-wrap">
                <h1 class="ko-loginRegister-title">Verify email and OTP</h1>
                <div class="ko-loginRegister-from">
                    <form action="{{ route('auth.otp_verify') }}" method="POST">
                        @csrf
                        @if (isset($message))
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @endif
                        @if ($message = session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="ko-row">
                            <div class="ko-col-12">
                                <div class="ko-loginRegister-grp">
                                    <label for="email">Email address <sup>*</sup></label>
                                    <input type="email" class="ko-loginRegister-control" name="email" id="email"
                                        value="{{ $email ?? old('email') }}" required readonly />
                                </div>
                            </div>
                            <div class="ko-col-12">
                                <div class="ko-loginRegister-grp">
                                    <label for="otp">OTP</label>
                                    <input type="text"
                                        class="ko-loginRegister-control @error('otp') is-invalid @enderror"
                                        name="otp" id="otp" required />
                                    @error('otp')
                                    <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="ko-btn">Verify</button>
                    </form>

                    @if (session('otp_verification_type') === 'vendor')
                    <div style="margin-top: 20px; text-align: center;">
                        <p style="margin-bottom: 10px; font-size: 14px; color: #666;">Didn't receive the OTP?</p>
                        <form action="{{ route('auth.resend_otp') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                            <button type="submit" class="ko-btn" style="background-color: #6c757d;">Resend OTP</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>

<x-footer />
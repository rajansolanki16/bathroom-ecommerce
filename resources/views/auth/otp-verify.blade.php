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
                </div>
            </div>
        </div>
    </section>
</main>

<x-footer />

<x-header :meta="array('title'=> 'Forgot Password', 'description'=> getSetting('page_home_meta_description') ,'sco-allow'=> false)" />


<main>
    <section class="ko-loginRegister-section ko-register-section">
        <div class="ko-container">
            <div class="ko-loginRegister-wrap">
                <h1 class="ko-loginRegister-title">Forgot your password</h1>
                <div class="ko-loginRegister-from">
                    @if (session()->has('message'))
                        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <form action="{{ route('auth.password.otp') }}" method="POST">
                        @csrf
                        <div class="ko-row">
                            <div class="ko-col-12">
                                <div class="ko-loginRegister-grp">
                                    <label for="">Email address <sup>*</sup></label>
                                    <input type="email" class="ko-loginRegister-control @error('email') is-invalid @enderror" name="email"
                                        id="email" value="{{ old('email') }}" required/>
                                        @error('email')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="ko-btn">Get OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<x-footer />

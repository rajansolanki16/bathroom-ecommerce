<x-header :meta="array('title'=> 'New Password', 'description'=> getSetting('page_home_meta_description'),'sco-allow'=> false)" />

<main>
    <section class="ko-loginRegister-section ko-register-section">
        <div class="ko-container">
            <div class="ko-loginRegister-wrap">
                <h1 class="ko-loginRegister-title">New password</h1>
                <div class="ko-loginRegister-from">
                    @if (session()->has('message'))
                        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <form action="{{ route('auth.password') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="ko-row">
                            <div class="ko-col-12">
                                <div class="ko-loginRegister-grp">
                                    <label for="password">New password</label>
                                    <input type="password"
                                        class="ko-loginRegister-control @error('password') is-invalid @enderror"
                                        name="password" id="password" required />
                                    @error('password')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="ko-col-12">
                                <div class="ko-loginRegister-grp">
                                    <label for="password_confirmation">Confirm password</label>
                                    <input type="password"
                                        class="ko-loginRegister-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" id="password_confirmation" required />
                                    @error('password_confirmation')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="ko-btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<x-footer />

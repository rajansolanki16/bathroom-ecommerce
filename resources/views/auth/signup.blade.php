<x-header :meta="array('title'=> 'Signup', 'description'=> getSetting('page_home_meta_description'))" />

<main>
    <section class="ko-loginRegister-section ko-register-section">
        <div class="ko-container">
            <div class="ko-loginRegister-wrap">
                <h1 class="ko-loginRegister-title">Register</h1>
                <div class="ko-loginRegister-from">
                    @if (session()->has('message'))
                        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <form action="{{ route('auth.signup') }}" method="POST">
                        @csrf
                        <div class="ko-row">
                            <div class="ko-col-6">
                                <div class="ko-loginRegister-grp">
                                    <label for="name">Name <sup>*</sup></label>
                                    <input type="text"
                                        class="ko-loginRegister-control @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') }}" required />
                                    @error('name')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="ko-col-6">
                                <div class="ko-loginRegister-grp">
                                    <label for="email">Email address <sup>*</sup></label>
                                    <input type="email"
                                        class="ko-loginRegister-control @error('email') is-invalid @enderror"
                                        name="email" id="email" value="{{ old('email') }}" required />
                                    @error('email')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="ko-col-6">
                                <div class="ko-loginRegister-grp">
                                    <label for="country">Country Code <sup>*</sup></label>
                                    <select name="country" id="country_code" data-url="{{ route(name: "get.states") }}" class="ko-loginRegister-control">
                                        <option value="Please select country code" disabled selected>Please select country code</option>
                                        @foreach ($countries as $country)
                                            <option
                                                value="{{ $country->c_name }}"
                                                data-country-code="{{ $country->c_code }}"
                                                {{ $country->c_name == old('country') ? 'selected' : '' }}>
                                                {{ $country->c_code }} - {{ $country->c_name }}
                                            </option>

                                        @endforeach
                                    </select>
                                    @error('country')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="ko-col-6">
                                <div class="ko-loginRegister-grp">
                                    <label for="mobile">Phone Number<sup>*</sup></label>
                                    <input type="tel"
                                        class="ko-loginRegister-control @error('mobile') is-invalid @enderror"
                                        name="mobile" id="ko-register-mobile"
                                        value="{{ old('mobile') }}"
                                        required
                                        minlength="10" maxlength="14" pattern="^\+?\d{10,15}$"
                                        title="Phone number must be between 10 to 14 digits and can optionally start with +"
                                    />

                                    @error('mobile')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="ko-col-12">
                                <div class="ko-loginRegister-grp">
                                    <label for="state">State <sup>*</sup></label>
                                    <select name="state" data-value="{{ old('state',0) }}" id="state" class="ko-loginRegister-control">
                                        <option value="" disabled selected>Please select state</option>
                                        <!-- States will be populated here -->
                                    </select>
                                    @error('state')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="ko-col-12">
                                <div class="ko-loginRegister-grp">
                                    <label for="password">Password <sup>*</sup></label>
                                    <input type="password"
                                        class="ko-loginRegister-control @error('password') is-invalid @enderror"
                                        name="password" id="password" value="{{ old('password') }}" required />
                                    @error('password')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="ko-col-12">
                                <div class="ko-loginRegister-grp">
                                    <label for="password_confirmation">Confirm Password <sup>*</sup></label>
                                    <input type="password"
                                        class="ko-loginRegister-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" value="{{ old('password_confirmation') }}" id="password_confirmation" required />
                                    @error('password_confirmation')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <p>An on time password for verify you email will be sent to your email address.</p>
                        <p>Your personal data will be used to support your experience throughout this website, to manage
                            access to your account, and for other purposes described in our <a href="#">Privacy
                                policy</a>.</p>
                        <button type="submit" class="ko-btn">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    $(document).ready(function() {
        get_states();
    });
</script>

<x-footer />

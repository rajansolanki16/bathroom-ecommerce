<footer class="site-footer bg-light border-top mt-5">

    <div class="container py-5">
        <div class="row gy-4">

            {{-- BRAND --}}
            <div class="col-lg-4 col-md-6">
                <img src="{{ publicPath(getSetting('site_logo_light')) }}"
                     alt="Logo"
                     height="42"
                     class="site-logo">

                <p class="text-muted small">
                    Premium products, seamless shopping, and trusted service.
                    Discover quality you can rely on.
                </p>
            </div>

            {{-- SHOP --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Shop</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('user.home') }}">All Products</a></li>
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                </ul>
            </div>

            {{-- ACCOUNT --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Account</h6>
                <ul class="list-unstyled footer-links">
                    @auth
                        <li><a href="#">My Account</a></li>
                        <li><a href="#">My Orders</a></li>
                        <li><a href="{{ route('auth.logout') }}">Logout</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                    @endauth
                </ul>
            </div>

            {{-- SUPPORT --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Support</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>

            {{-- NEWSLETTER --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Newsletter</h6>
                <p class="small text-muted">
                    Get offers & updates.
                </p>
                <form>
                    <input type="email"
                           class="form-control form-control-sm mb-2"
                           placeholder="Email address">
                    <button class="btn btn-dark btn-sm w-100">
                        Subscribe
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- BOTTOM BAR --}}
    <div class="border-top py-3 bg-white">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 small text-muted">
                © {{ date('Y') }} {{ getSetting('site_name') ?? 'Your Store' }}. All rights reserved.
            </p>

            <div class="d-flex gap-3">
                <a href="#" class="footer-social"><i class="bi bi-facebook"></i></a>
                <a href="#" class="footer-social"><i class="bi bi-instagram"></i></a>
                <a href="#" class="footer-social"><i class="bi bi-twitter-x"></i></a>
            </div>
        </div>
    </div>

    {{-- EXISTING SCRIPTS (UNCHANGED) --}}
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script src="{{ publicPath('assets/js/custom-script.js') }}?version={{ rand(10,99) }}.{{ rand(10,99) }}.{{ rand(100,999) }}"></script>
    {{-- {!! getSetting('page_custom_scrip_footer') !!} --}}
   <script src="{{ asset('assets/js/user-script.js') }}"></script>
   <script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</footer>

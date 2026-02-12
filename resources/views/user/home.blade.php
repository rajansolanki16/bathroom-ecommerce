<x-header :meta="array(
    'title' => getSetting('page_rooms_meta_title'),
    'description' => getSetting('page_rooms_meta_description')
)" />

<main class="bg-light">

    {{-- HERO SECTION --}}
    <section class="bg-white border-bottom">
        <div class="ko-container py-5">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <span class="badge bg-primary mb-3 px-3 py-2">
                        New Collection
                    </span>

                    <h1 class="fw-bold mb-3" style="font-size: 2.5rem;">
                        Premium Bathroom Hardware
                    </h1>

                    <p class="text-muted mb-4">
                        Discover durable, elegant and modern fittings
                        crafted for everyday luxury.
                    </p>

                    <div class="d-flex gap-3">
                        <a href="#products" class="btn btn-dark btn-lg px-4">
                            Shop Now
                        </a>

                        <a href="{{ route('wishlist.index') }}"
                           class="btn btn-outline-dark btn-lg px-4">
                            Wishlist
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <img src="{{ asset('assets/images/hero-products.png') }}"
                         class="img-fluid rounded-4 shadow-sm"
                         style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURE STRIP --}}
    <section class="bg-white py-4 border-bottom">
        <div class="ko-container">
            <div class="row text-center gy-3">
                <div class="col-md-3">
                    <div class="fw-semibold">🚚 Fast Delivery</div>
                    <small class="text-muted">Across India</small>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold">💳 Secure Payments</div>
                    <small class="text-muted">100% Protected</small>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold">⭐ Premium Quality</div>
                    <small class="text-muted">Rust-Proof Guarantee</small>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold">📞 24/7 Support</div>
                    <small class="text-muted">Dedicated Assistance</small>
                </div>
            </div>
        </div>
    </section>


    {{-- PRODUCT HEADER --}}
    <section id="products" class="bg-light sticky-top z-2 shadow-sm">
        <div class="ko-container py-3">
            <div class="row align-items-center gy-2">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold">
                        All Products
                        <span class="text-muted small">
                            ({{ $products->total() ?? 0 }} items)
                        </span>
                    </h5>
                </div>

                <div class="col-md-6 text-md-end">
                    <select class="form-select w-auto d-inline-block shadow-sm">
                        <option value="latest">Latest</option>
                        <option value="price_asc">Price: Low → High</option>
                        <option value="price_desc">Price: High → Low</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    {{-- PRODUCT GRID --}}
    <section class="py-5">
        <div class="ko-container">
            <div class="row g-4"
                 id="vec_product-grid"
                 data-fetch-url="{{ route('view.home') }}"
                 data-wishlist-url="{{ route('wishlist.toggle') }}">

                @include('components.product-card')

            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-5">
                {!! $products->links('pagination::bootstrap-4') !!}
            </div>
        </div>
    </section>


    {{-- CTA SECTION --}}
    <section class="bg-dark text-white py-5 mt-4">
        <div class="ko-container text-center">
            <h3 class="fw-bold mb-3">
                Need Help Choosing the Right Product?
            </h3>
            <p class="text-light mb-4">
                Our experts are ready to assist you with bulk orders & project consultation.
            </p>

            <a href="#"
               class="btn btn-primary btn-lg px-4 me-2">
                Contact Us
            </a>

            <a href="#"
               class="btn btn-outline-light btn-lg px-4">
                Send Inquiry
            </a>
        </div>
    </section>

</main>
<script>
    window.guestMergeUrl = "{{ route('guest.merge') }}";
   window.cartAddUrl = "{{ route('cart.add') }}";
    window.isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
</script>

<x-footer />

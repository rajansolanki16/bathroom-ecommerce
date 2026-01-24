<x-header :meta="array(
    'title' => getSetting('page_rooms_meta_title'),
    'description' => getSetting('page_rooms_meta_description')
)" />

<main class="bg-light">

    {{-- HERO SECTION --}}
    <section class="bg-white border-bottom">
        <div class="ko-container py-5">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-primary mb-3">New Collection</span>

                    <h1 class="display-5 fw-bold mb-3">
                        Discover Premium Products<br class="d-none d-lg-block">
                        Made for Everyday Luxury
                    </h1>

                    <p class="text-muted fs-5 mb-4">
                        Handpicked quality products, honest pricing,
                        and fast delivery at your doorstep.
                    </p>

                    <div class="d-flex gap-3">
                        <a href="#products" class="btn btn-primary btn-lg">
                            Shop Now
                        </a>

                        <a href="{{ route('wishlist.index') }}"
                           class="btn btn-outline-dark btn-lg">
                            View Wishlist
                        </a>
                    </div>
                </div>

                <div class="col-lg-5 text-center mt-5 mt-lg-0">
                    <img src="{{ asset('assets/images/hero-products.png') }}"
                         class="img-fluid rounded-4 shadow-sm"
                         alt="Products">
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURE STRIP --}}
    <section class="bg-light py-4">
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
                    <small class="text-muted">Curated Products</small>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold">📞 Support</div>
                    <small class="text-muted">24/7 Assistance</small>
                </div>
            </div>
        </div>
    </section>

    {{-- PRODUCT HEADER --}}
    <section id="products" class="bg-white border-top border-bottom sticky-top z-2">
        <div class="ko-container py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-semibold">
                        All Products
                        <span class="text-muted small">
                            ({{ $products->total() ?? '' }} items)
                        </span>
                    </h5>
                </div>

                <div class="col-md-6 text-md-end">
                    <select class="form-select d-inline-block w-auto">
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
                 data-fetch-url="{{ route('user.product') }}"
                 data-wishlist-url="{{ route('wishlist.toggle') }}">

                @include('components.product-card')

            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-4">
                {!! $products->links('pagination::bootstrap-4') !!}
            </div>
        </div>
    </section>

</main>
<script>
   const guestMergeUrl = "{{ route('guest.merge') }}";
</script>

<x-footer />

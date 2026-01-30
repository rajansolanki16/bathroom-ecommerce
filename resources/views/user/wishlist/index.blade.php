<x-header
    :meta="[
        'title' => getSetting('page_rooms_meta_title'),
        'description' => getSetting('page_rooms_meta_description')
    ]"
/>

<main>

    {{-- ================= BANNER ================= --}}
    <section class="ko-banner">
        <div class="ko-container">
            <div class="ko-banner-content text-center">
                <h2>
                    <i class="bi bi-heart-fill" style="font-size:60px;color:red;"></i>
                </h2>

                <nav>
                    <ol class="ko-banner-list justify-content-center">
                        <li><a href="{{ route('user.home') }}">Home</a></li>
                        <li>Wishlist</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    {{-- ================= CONTENT ================= --}}
    <section class="ko-container mt-5">

        <h2 class="mb-4">My Wishlist</h2>

        {{-- EMPTY --}}
        @if($wishlists->isEmpty())
            <p class="text-muted">No products in your wishlist.</p>
        @else

        <div class="row g-3">
            @foreach($wishlists as $item)

            @php
                /*
                    Guest   → $item is Product
                    Logged  → $item is Wishlist
                */
                $product = $isGuest ? $item : $item->product;
                $removeId = $isGuest ? $product->id : $item->id;
            @endphp

            <div class="col-12 col-md-6 col-lg-4 wishlist-card" id="wishlist-item-{{ $removeId }}">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img
                            src="{{ $product->getFirstMediaUrl('main_image') ?: asset('assets/images/no-image.png') }}"
                            class="card-img-top" style="height:220px;object-fit:cover;">

                        <button type="button" class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 vec_wishlist_remove" data-id="{{ $product->id }}" title="Remove from wishlist">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ Str::limit($product->product_title, 70) }}</h5>
                        <p class="text-muted small mb-2">{{ Str::limit($product->short_description ?? '', 80) }}</p>

                        <div class="mb-3">
                            @if($product->sell_price && $product->sell_price < $product->price)
                                <div class="fw-bold product-price-amount">₹{{ number_format($product->sell_price, 2) }}</div>
                                <div class="text-muted small"><s>₹{{ number_format($product->price, 2) }}</s></div>
                            @else
                                <div class="fw-bold product-price-amount">₹{{ number_format($product->price, 2) }}</div>
                            @endif

                            @if($product->stock > 0)
                                <span class="badge bg-success mt-2">In Stock</span>
                            @else
                                <span class="badge bg-danger mt-2">Out of Stock</span>
                            @endif
                        </div>

                        <div class="mt-auto d-flex gap-2">
                            @if($product->stock > 0)
                                <button type="button" class="btn btn-success btn-sm add-to-cart flex-grow-1" data-id="{{ $product->id }}">Add to Cart</button>
                            @else
                                <button class="btn btn-secondary btn-sm flex-grow-1" disabled>Out of Stock</button>
                            @endif

                            <a href="{{ route('product.user.show', $product->slug ?? '#') }}" class="btn btn-outline-secondary btn-sm">View</a>
                        </div>

                        {{-- per-product error --}}
                        <div class="text-danger mt-2 cart-error" id="cart-error-{{ $product->id }}" style="display:none;font-size:13px;"></div>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
        @endif
    </section>
</main>

<x-footer />
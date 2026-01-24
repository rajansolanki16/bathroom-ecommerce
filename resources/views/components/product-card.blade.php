@foreach($products as $product)
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <div class="product-card h-100 bg-white rounded-4 shadow-sm border overflow-hidden">

        {{-- IMAGE --}}
        <div class="product-image position-relative">
            <a href="{{ route('product.user.show', $product->slug ?? '#') }}">
                <img
                    src="{{ $product->product_image
                            ? asset('storage/'.$product->product_image)
                            : asset('assets/images/no-image.png') }}"
                    alt="{{ $product->product_title }}"
                    class="w-100"
                    style="aspect-ratio: 1 / 1; object-fit: cover;">
            </a>

            {{-- WISHLIST --}}
            <button
                type="button"
                class="wishlist-btn position-absolute top-0 end-0 m-2 bg-white rounded-circle shadow-sm
                       {{ auth()->check() && $product->is_wishlisted ? 'added' : '' }}"
                data-product-id="{{ $product->id }}"
                style="width:38px; height:38px;"
            >
                <i class="bi {{ auth()->check() && $product->is_wishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        </div>

        {{-- BODY --}}
        <div class="product-body p-3 d-flex flex-column">

            {{-- CATEGORY --}}
            <div class="text-muted small mb-1">
                {{ optional($product->categories)->pluck('name')->join(', ') ?: 'Uncategorized' }}
            </div>

            {{-- TITLE --}}
            <h6 class="fw-semibold mb-1">
                <a href="{{ route('product.user.show', $product->slug ?? '#') }}"
                   class="text-dark text-decoration-none">
                    {{ Str::limit($product->product_title, 45) }}
                </a>
            </h6>

            {{-- DESCRIPTION --}}
            <p class="text-muted small mb-2">
                {{ Str::limit($product->short_description, 60) }}
            </p>

            {{-- PRICE --}}
            <div class="fw-bold fs-5 text-dark mb-3">
                ₹{{ number_format($product->price) }}
            </div>

            {{-- ACTIONS --}}
            <div class="mt-auto">
                <a href="{{ route('product.user.show', $product->slug ?? '#') }}"
                   class="btn btn-outline-dark btn-sm w-100 mb-2">
                    View Details
                </a>

                <button type="button"
                        class="btn btn-success btn-sm w-100 add-to-cart"
                        data-id="{{ $product->id }}">
                    Add to Cart
                </button>
            </div>
        </div>

        {{-- CART ERROR --}}
        <div class="text-danger px-3 pb-2 cart-error"
            id="cart-error-{{ $product->id }}"
            style="font-size:13px; display:none;">
        </div>
    </div>
</div>
@endforeach

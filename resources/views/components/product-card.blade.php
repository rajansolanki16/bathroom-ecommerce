@foreach($products as $product)
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <div class="product-card h-100 bg-white rounded-4 shadow-sm border overflow-hidden" style="transition: transform .15s ease, box-shadow .15s ease;">

        {{-- IMAGE --}}
        <style>
            .product-card:hover{ transform: translateY(-6px); box-shadow: 0 8px 28px rgba(0,0,0,0.06); }
            .product-image .badge-vendor{ position: absolute; left:8px; top:8px; background:#fff; padding:4px 8px; border-radius:6px; font-size:12px; box-shadow:0 2px 6px rgba(0,0,0,0.06);} 
            .product-price-amount{ font-size:1.05rem; font-weight:700; }
            .product-price-old{ text-decoration:line-through; color:#888; margin-left:8px; font-size:0.95rem; }
            .product-discount{ background:#eaf6ff; color:#0b63d6; padding:2px 6px; border-radius:4px; font-weight:600; font-size:12px; margin-left:8px; }
            .product-rating { color:#f59e0b; font-weight:600; }
        </style>
        <div class="product-image position-relative">
            <a href="{{ route('product.user.show', $product->slug ?? '#') }}">
                <img
                    src="{{ $product->getFirstMediaUrl('main_image') ?: asset('assets/images/no-image.png') }}"
                    alt="{{ $product->product_title }}"
                    class="w-100"
                    style="aspect-ratio: 1 / 1; object-fit: cover;">
            </a>

            {{-- VENDOR BADGE --}}
            @if($product->brand)
            <div class="badge-vendor">{{ $product->brand->name }}</div>
            @endif

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
            <div class="d-flex align-items-center mb-3">
                @if($product->sell_price && $product->sell_price < $product->price)
                    <div class="product-price-amount">₹{{ number_format($product->sell_price) }}</div>
                    <div class="product-price-old">₹{{ number_format($product->price) }}</div>
                    @php
                        $off = $product->price > 0 ? round((($product->price - $product->sell_price) / $product->price) * 100) : 0;
                    @endphp
                    @if($off > 0)
                        <div class="product-discount">{{ $off }}% OFF</div>
                    @endif
                @else
                    <div class="product-price-amount">₹{{ number_format($product->price) }}</div>
                @endif
            </div>

            {{-- RATING & REVIEWS --}}
            <div class="d-flex align-items-center small mb-2">
                <div class="product-rating me-2">{{ str_repeat('★', round($product->avgRating())) }}{{ str_repeat('☆', 5 - round($product->avgRating())) }}</div>
                <div class="text-muted">{{ round($product->avgRating(),1) }} • {{ $product->reviews->count() }} Reviews</div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-auto d-grid">
                <button type="button"
                        class="btn btn-dark btn-lg add-to-cart"
                        data-id="{{ $product->id }}">
                    Add to Cart
                </button>
                <a href="{{ route('product.user.show', $product->slug ?? '#') }}" class="btn btn-outline-secondary mt-2">View</a>
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

    {{-- IMAGE --}}
        <style>
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 28px rgba(0,0,0,0.06);
        }
        .product-image {
            position: relative;
            overflow: hidden;
        }
        .product-image img {
            transition: transform .25s ease;
        }
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }
        .badge-vendor {
            position: absolute;
            left: 8px;
            top: 8px;
            background: #fff;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        }
        .product-discount {
            background: #eaf6ff;
            color: #0b63d6;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 12px;
        }
        .product-price-amount {
            font-size: 1.05rem;
            font-weight: 700;
        }
        .product-price-old {
            text-decoration: line-through;
            color: #888;
            margin-left: 8px;
            font-size: 0.95rem;
        }
        .stock-badge {
            position: absolute;
            right: 8px;
            bottom: 8px;
            font-size: 11px;
            padding: 3px 6px;
            border-radius: 4px;
        }

        </style>
@foreach($products as $product)
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <div class="product-card h-100 bg-white rounded-4 shadow-sm border overflow-hidden">

        {{-- IMAGE --}}
        <div class="product-image">
            <a href="{{ route('product.user.show', $product->slug ?? '#') }}">
                <img
                    src="{{ $product->getFirstMediaUrl('main_image') ?: asset('assets/images/no-image.png') }}"
                    alt="{{ $product->product_title }}"
                    class="w-100"
                    style="aspect-ratio: 1/1; object-fit: cover;">
            </a>

            {{-- BRAND --}}
            @if($product->brand)
                <div class="badge-vendor">{{ $product->brand->name }}</div>
            @endif

            {{-- STOCK --}}
            @if($product->stock <= 0)
                <span class="badge bg-danger stock-badge">Out of Stock</span>
            @else
                <span class="badge bg-success stock-badge">In Stock</span>
            @endif

            {{-- WISHLIST --}}
            <button
                type="button"
                class="wishlist-btn position-absolute top-0 end-0 m-2 bg-white rounded-circle shadow-sm
                       {{ auth()->check() && $product->is_wishlisted ? 'added' : '' }}"
                data-product-id="{{ $product->id }}"
                style="width:38px; height:38px;">
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
                    {{ Str::limit($product->product_title, 50) }}
                </a>
            </h6>

            {{-- SHORT DESC --}}
            <p class="text-muted small mb-2">
                {{ Str::limit($product->short_description, 60) }}
            </p>

            {{-- PRICE --}}
            <div class="d-flex align-items-center mb-2">
                @if($product->sell_price && $product->sell_price < $product->price)
                    <div class="product-price-amount">₹{{ number_format($product->sell_price) }}</div>
                    <div class="product-price-old">₹{{ number_format($product->price) }}</div>
                    @php
                        $off = $product->price > 0
                            ? round((($product->price - $product->sell_price) / $product->price) * 100)
                            : 0;
                    @endphp
                    @if($off > 0)
                        <div class="product-discount ms-2">{{ $off }}% OFF</div>
                    @endif
                @else
                    <div class="product-price-amount">₹{{ number_format($product->price) }}</div>
                @endif
            </div>

            {{-- RATING --}}
            @php
                $rating = round($product->avgRating());
                $reviewCount = $product->reviews->count();
            @endphp

            <div class="small mb-3 d-flex align-items-center">
                <div class="text-warning me-2">
                    @for($i=1;$i<=5;$i++)
                        <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                </div>
                <div class="text-muted">
                    {{ number_format($product->avgRating(),1) }} • {{ $reviewCount }}
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-auto d-grid gap-2">

                {{-- INQUIRY MODE --}}
                @if(config('app.inquiry_mode'))
                    <a href="{{ route('product.user.show', $product->slug ?? '#') }}"
                       class="btn btn-dark">
                        Send Inquiry
                    </a>
                @else
                    <button type="button"
                            class="btn btn-dark add-to-cart"
                            data-id="{{ $product->id }}"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        Add to Cart
                    </button>
                @endif
                <a href="{{ route('product.user.show', $product->slug ?? '#') }}" class="btn btn-outline-secondary">View</a>
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

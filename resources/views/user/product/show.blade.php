<x-header :meta="[
    'title' => $product->product_title,
    'description' => $product->short_description,
]" />

<style>
    .product-price {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .product-old-price {
        font-size: 1rem;
    }

    .variant-btn.active {
        background-color: #212529;
        color: #fff;
        border-color: #212529;
    }

    .variant-btn {
        border-radius: 6px;
    }

    .review-card {
        border-bottom: 1px solid #eee;
        padding: 1.25rem 0;
    }

    .review-card:last-child {
        border-bottom: none;
    }

    .sticky-summary {
        top: 90px;
    }

    .rating-badge {
        background: #fff7e6;
        border: 1px solid #ffe1a8;
        color: #f59e0b;
    }
</style>


<section class="bg-light py-5">
    <div class="container">

        <div class="row g-5 align-items-start">

            {{-- PRODUCT GALLERY --}}
            <div class="col-lg-6">

                <div class="bg-white rounded-3 shadow-sm p-4">
                    <img id="mainImage" src="{{ $product->getFirstMediaUrl('main_image') ?: asset('assets/images/no-image.png') }}"
                        class="img-fluid rounded-2 w-100" style="aspect-ratio:1/1;object-fit:cover;">
                </div>

                @if($product->hasMedia('product_gallery'))
                    <div class="d-flex gap-3 mt-3">
                        @foreach($product->getMedia('gallery') as $media)
                            <img
                                src="{{ $media->getUrl() }}"
                                class="border rounded-2 p-1 bg-white"
                                style="width:80px;height:80px;object-fit:cover;cursor:pointer"
                                onclick="document.getElementById('mainImage').src='{{ $media->getUrl() }}'">
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- PRODUCT INFORMATION --}}
            <div class="col-lg-6">

                <div class="position-sticky" style="top:90px">

                    {{-- CATEGORY --}}
                    <div class="text-uppercase text-secondary small fw-semibold mb-2">
                        {{ $product->categories->pluck('name')->join(' • ') }}
                    </div>

                    {{-- TITLE --}}
                    <h1 class="fw-bold mb-3 lh-sm">
                        {{ $product->product_title }}
                    </h1>

                    {{-- PRICE --}}
                    <div class="mb-3">
                        @if ($product->sell_price)
                            <span class="product-price text-dark">
                                ₹{{ number_format($product->sell_price) }}
                            </span>
                            <span class="text-muted text-decoration-line-through ms-2 product-old-price">
                                ₹{{ number_format($product->price) }}
                            </span>
                            <span class="badge rating-badge ms-2">
                                {{ round((($product->price - $product->sell_price) / $product->price) * 100) }}% OFF
                            </span>
                        @else
                            <span class="product-price text-dark">
                                ₹{{ number_format($product->price) }}
                            </span>
                        @endif
                    </div>

                    {{-- RATING --}}
                    <div class="d-flex align-items-center gap-2 mb-4">
                        @php $avg = round($product->avgRating(), 1); @endphp

                        <div class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= floor($avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>

                        <small class="text-muted">
                            {{ $avg }} • {{ $product->reviews->count() }} Reviews
                        </small>
                    </div>


                    {{-- SHORT DESCRIPTION --}}
                    <p class="text-muted fs-6 mb-4">
                        {{ $product->short_description }}
                    </p>

                    {{-- VARIANTS --}}
                    @if ($product->product_type == \App\Enums\ProductType::VARIANTS->value)
                        <div class="mb-4">

                            @foreach ($product->variants->groupBy(fn($v) => $v->attributeValues->first()->attribute->name ?? '') as $attr => $variants)
                                <div class="mb-3">
                                    <label class="fw-semibold d-block mb-2">{{ $attr }}</label>

                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($variants as $variant)
                                            <button type="button"
                                                class="btn btn-outline-secondary variant-btn px-3 py-2"
                                                data-price="{{ $variant->sell_price ?? $variant->price }}"
                                                data-image="{{ $variant->image ? asset('storage/' . $variant->image) : '' }}">
                                                {{ $variant->attributeValues->pluck('value')->join(' / ') }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @endif

                    {{-- ADD TO CART --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                        @csrf

                        <div class="d-flex gap-3 align-items-center">
                            <input type="number" name="qty" value="1" min="1"
                                class="form-control w-25">

                            <button class="btn btn-dark btn-lg flex-grow-1 add-to-cart" data-id="{{ $product->id }}">
                                <i class="bi bi-cart-plus me-2"></i> Add to Cart
                            </button>
                        </div>
                    </form>



                    {{-- ASSURANCE --}}
                    <div class="border-top pt-3 text-muted small">
                        <div class="mb-1">• Secure & encrypted payments</div>
                        <div class="mb-1">• 7-day hassle-free returns</div>
                        <div>• Verified authentic products</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- PRODUCT DETAILS --}}
        <div class="bg-white rounded-3 shadow-sm mt-5 p-5">

            {{-- TABS --}}
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc">
                        Description
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#shipping">
                        Shipping
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">
                        Reviews ({{ $product->reviews->count() }})
                    </button>
                </li>
            </ul>
            {{-- TAB CONTENT --}}
            <div class="tab-content">

                {{-- DESCRIPTION --}}
                <div class="tab-pane fade show active" id="desc">
                    <div class="text-muted lh-lg">
                        {!! $product->product_decscription !!}
                    </div>
                </div>

                {{-- SHIPPING --}}
                <div class="tab-pane fade" id="shipping">
                    <p class="text-muted lh-lg">
                        Orders are dispatched within 24–48 business hours.<br>
                        Returns are accepted within 7 days of delivery in original condition.
                    </p>
                </div>

                {{-- ✅ REVIEWS CONTENT --}}
                <div class="tab-pane fade" id="reviews">

                    {{-- WRITE REVIEW --}}
                    @auth
                        @if (\App\Models\OrderItem::hasPurchased($product->id))
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="fw-semibold mb-3">Write a Review</h5>

                                    <form action="{{ route('reviews.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Rating</label>
                                            <select name="rating" class="form-select" required>
                                                <option value="">Select rating</option>
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}">{{ $i }} Star</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Your Review</label>
                                            <textarea name="review" class="form-control" rows="3" placeholder="What did you like or dislike?"></textarea>
                                        </div>

                                        <button class="btn btn-dark">Submit Review</button>
                                    </form>
                                </div>
                            </div>
                        @endif

                    @endauth

                    {{-- REVIEWS LIST --}}
                    @forelse($product->reviews as $review)
                        <div class="review-card">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>{{ $review->user->name }}</strong>
                                <small class="text-muted">
                                    {{ $review->created_at->format('d M Y') }}
                                </small>
                            </div>

                            <div class="text-warning mb-2">
                                @for($i=1;$i<=5;$i++)
                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>

                            <p class="text-muted mb-0">{{ $review->review }}</p>
                        </div>
                        @empty
                        <p class="text-muted">No reviews yet.</p>
                        @endforelse

                </div>
            </div>
        </div>

    </div>
</section>
<script>
    document.querySelectorAll('.variant-btn').forEach(btn => {
        btn.addEventListener('click', function() {

            document.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const price = this.dataset.price;
            const image = this.dataset.image;

            if (price) {
                document.querySelector('.fs-3').innerText = '₹' + price;
            }

            if (image) {
                document.getElementById('mainImage').src = image;
            }
        });
    });
</script>
<x-footer />

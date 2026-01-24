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

        <table class="table align-middle">
            <thead>
                <tr>
                    <th width="60"></th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th width="160">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($wishlists as $item)

                @php
                    /*
                        Guest   → $item is Product
                        Logged  → $item is Wishlist
                    */
                    $product = $isGuest ? $item : $item->product;
                    $removeId = $isGuest ? $product->id : $item->id;
                @endphp

                <tr id="wishlist-row-{{ $removeId }}">

                    {{-- REMOVE --}}
                    <td class="text-center">
                        <i class="bi bi-trash vec_wishlist_remove"
                        data-id="{{ $product->id }}"
                        style="cursor:pointer;font-size:20px;color:#cfcfcf;">
                        </i>
                    </td>

                    {{-- PRODUCT --}}
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img
                                src="{{ $product->product_image
                                    ? asset('storage/'.$product->product_image)
                                    : asset('assets/images/no-image.png') }}"
                                width="80"
                                class="rounded">

                            <span class="fw-semibold">
                                {{ $product->product_title }}
                            </span>
                        </div>
                    </td>

                    {{-- PRICE --}}
                    <td>
                        ₹{{ number_format($product->price, 2) }}
                    </td>

                    {{-- STOCK --}}
                    <td>
                        @if($product->stock > 0)
                            <span class="badge bg-success">In Stock</span>
                        @else
                            <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td>
                        @if($product->stock > 0)
                            <button type="button" class="btn btn-sm btn-success add-to-cart" data-id="{{ $product->id }}">
                                Add to Cart
                            </button>
                        @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                Out of Stock
                            </button>
                        @endif

                        {{-- per-product error --}}
                        <div
                            class="text-danger mt-1 cart-error"
                            id="cart-error-{{ $product->id }}"
                            style="display:none;font-size:13px;">
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </section>
</main>

<x-footer />
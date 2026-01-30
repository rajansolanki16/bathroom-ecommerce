<x-header :meta="array('title' => 'Cart - E-commerce Store', 'description' => 'Your shopping cart')" />

<main class="ko-container py-4">
    <section class="ko-bann">
        <div class="ko-container">
            <div class="ko-banner-content">
                <h2><i class="bi bi-cart" style="font-size:30px;color:black;"></i></h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <h2 class="mb-3">My Cart</h2>
            </div>

            @if(empty($cart))
            <div class="col-12">
                <div class="alert alert-info">Your cart is empty.</div>
            </div>
            @else
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive d-none d-lg-block">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp

                                    @foreach($cart as $item)
                                    @php
                                    $total = $item['price'] * $item['quantity'];
                                    $grandTotal += $total;
                                    @endphp
                                    <tr id="cart-row-{{ $item['id'] }}">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $item['image'] ?: asset('assets/images/no-image.png') }}" width="60" style="object-fit:cover">
                                                <div>
                                                    <div class="fw-semibold">{{ $item['name'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>₹{{ $item['price'] }}</td>
                                        <td style="width:110px">
                                            <input
                                                type="number"
                                                name="quantity"
                                                value="{{ $item['quantity'] }}"
                                                min="1"
                                                class="form-control form-control-sm update-quantity"
                                                data-id="{{ $item['id'] }}"
                                                data-price="{{ $item['price'] }}"
                                                style="width:70px">
                                        </td>

                                        <td class="item-total">₹{{ $total }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger remove-from-cart"
                                                data-id="{{ $item['id'] }}"
                                                data-row="cart-row-{{ $item['id'] }}">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile cards --}}
                        <div class="d-lg-none">
                            @foreach($cart as $item)
                                @php $total = $item['price'] * $item['quantity']; @endphp
                                <div class="card mb-3" id="cart-card-{{ $item['id'] }}">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-4">
                                            <img src="{{ $item['image'] ?: asset('assets/images/no-image.png') }}" class="img-fluid rounded-start" alt="{{ $item['name'] }}" style="height:100px;object-fit:cover;" />
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-2">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="fw-semibold">{{ $item['name'] }}</div>
                                                    <div class="fw-bold">₹{{ $total }}</div>
                                                </div>

                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="input-group input-group-sm">
                                                        <button class="btn btn-outline-secondary btn-sm qty-decrease" data-id="{{ $item['id'] }}">-</button>
                                                        <input type="number" class="form-control form-control-sm text-center update-quantity" data-id="{{ $item['id'] }}" value="{{ $item['quantity'] }}" style="width:70px">
                                                        <button class="btn btn-outline-secondary btn-sm qty-increase" data-id="{{ $item['id'] }}">+</button>
                                                    </div>

                                                    <button class="btn btn-sm btn-danger ms-auto remove-from-cart" data-id="{{ $item['id'] }}" data-row="cart-card-{{ $item['id'] }}">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Grand Total</span>
                            <strong id="grand-total">₹{{ $grandTotal }}</strong>
                        </div>
                        <div class="mt-3 d-grid">
                            <a href="{{ route('checkout') ?? '#' }}" class="btn btn-primary">Proceed to Checkout</a>
                            <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary mt-2">← Back to Wishlist</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</main>

<x-footer />
<x-header :meta="[
    'title' => 'Order #' . $order->order_number,
    'description' => 'Order details'
]" />

<div class="container py-5">

    {{-- PAGE TITLE --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Order #{{ $order->order_number }}</h3>
            <small class="text-muted">
                Placed on {{ $order->created_at->format('d M Y, h:i A') }}
            </small>
        </div>

        <span class="badge px-3 py-2 fs-6
            @if($order->status == 'delivered') bg-success
            @elseif($order->status == 'cancelled') bg-danger
            @else bg-warning text-dark @endif">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="row g-4">

        {{-- LEFT SIDE --}}
        <div class="col-lg-8">

            {{-- ORDER ITEMS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-4">Ordered Items</h5>

                    @foreach($order->items as $item)
                    <div class="d-flex gap-3 border-bottom pb-3 mb-3">
                        <img src="{{ $item->product->getFirstMediaUrl('product_image') ?: asset('admin/images/no-image.png') }}"
                             class="rounded"
                             width="80"
                             height="80"
                             style="object-fit: cover">

                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">
                                {{ $item->product->name }}
                            </h6>

                            <small class="text-muted">
                                Qty: {{ $item->quantity }}
                            </small>

                            <div class="fw-semibold mt-1">
                                ₹{{ number_format($item->price) }}
                            </div>
                        </div>

                        {{-- REVIEW BUTTON --}}
                        @if($order->status === 'delivered')
                            <div class="text-end">
                                <a href="{{ route('products.show', $item->product->slug) }}"
                                   class="btn btn-outline-dark btn-sm">
                                    Write Review
                                </a>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- SHIPPING ADDRESS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Shipping Address</h5>

                    <p class="mb-1 fw-semibold">{{ $order->shipping_name }}</p>
                    <p class="mb-1">{{ $order->shipping_address }}</p>
                    <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                    <p class="mb-1">{{ $order->shipping_country }} - {{ $order->shipping_pincode }}</p>
                    <p class="mb-0">📞 {{ $order->shipping_phone }}</p>
                </div>
            </div>

        </div>

        {{-- RIGHT SIDE --}}
        <div class="col-lg-4">

            {{-- ORDER SUMMARY --}}
            <div class="card shadow-sm sticky-top" style="top: 90px;">
                <div class="card-body">
                    <h5 class="fw-semibold mb-4">Order Summary</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($order->subtotal) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span class="text-success">Free</span>
                    </div>

                    @if($order->discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount</span>
                        <span class="text-danger">-₹{{ number_format($order->discount) }}</span>
                    </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span>₹{{ number_format($order->total) }}</span>
                    </div>

                    <hr>

                    {{-- PAYMENT --}}
                    <div class="mb-2">
                        <small class="text-muted">Payment Method</small>
                        <div class="fw-semibold">
                            {{ strtoupper($order->payment_method) }}
                        </div>
                    </div>

                    <div>
                        <small class="text-muted">Payment Status</small>
                        <div class="fw-semibold text-success">
                            {{ ucfirst($order->payment_status) }}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- BACK BUTTON --}}
    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            ← Back to Orders
        </a>
    </div>

</div>

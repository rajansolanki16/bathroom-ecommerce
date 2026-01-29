<x-header :meta="[
    'title' => 'Order #' . $order->order_number,
    'description' => 'Order details'
]" />

<h4 class="mb-4">Order Details</h4>

<!-- Order Info -->
<div class="card mb-4">
    <div class="card-body">
        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
        <p><strong>Date & Time:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
        <p>
            <strong>Status:</strong>
            {{ optional($order->status)->label() ?? 'N/A' }}
        </p>

    </div>
</div>

<!-- Products -->
@foreach($order->items as $item)
<div class="card mb-3">
    <div class="card-body d-flex align-items-center gap-3">

        <!-- Product Image -->
        <img src="{{ $item->product->getFirstMediaUrl('product_image') ?: asset('admin/images/no-image.png') }}"
            width="80" class="rounded">

        <!-- Product Info -->
        <div>
            <h6 class="mb-1">{{ $item->product->product_title }}</h6>
            <p class="mb-0">Price: ₹{{ number_format($item->price, 2) }}</p>
            <p class="mb-0">Quantity: {{ $item->quantity }}</p>
        </div>

    </div>
</div>
@endforeach

</div>

<x-footer />
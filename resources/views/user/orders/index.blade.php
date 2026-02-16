<x-header :meta="[
    'title' => 'My Inquiries',
    'description' => 'View and track your inquiry history'
]" />

<section class="bg-light py-5">
    <div class="container">

        {{-- PAGE HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">My Inquiries</h2>
                <p class="text-muted mb-0">
                    Track your requested products and inquiry status
                </p>
            </div>
        </div>

        {{-- INQUIRIES --}}
        @forelse($orders as $order)

            <div class="bg-white rounded-3 shadow-sm mb-4">

                {{-- INQUIRY HEADER --}}
                <div class="border-bottom p-4 d-flex justify-content-between flex-wrap gap-3">

                    <div>
                        <div class="fw-semibold fs-5">
                            Inquiry #{{ $order->order_number ?? $order->id }}
                        </div>
                        <small class="text-muted">
                            Submitted on {{ $order->created_at->format('d M Y') }}
                        </small>
                    </div>

                    <div class="text-end">
                        <div class="fw-bold fs-5">
                            ₹{{ number_format($order->total) }}
                        </div>

                        @php
                            $statusColor = match ($order->status) {
                                \App\Enums\OrderStatus::PROCESSING => 'info',
                                \App\Enums\OrderStatus::COMPLETED  => 'success',
                                \App\Enums\OrderStatus::CANCELLED  => 'danger',
                                default => 'secondary',
                            };
                        @endphp

                        <span class="badge bg-{{ $statusColor }}">
                            {{ $order->status->label() }}
                        </span>
                    </div>
                </div>

                {{-- INQUIRY ITEMS PREVIEW --}}
                <div class="p-4">

                    @foreach($order->items->take(2) as $item)
                        <div class="d-flex align-items-center gap-3 mb-3">

                            {{-- PRODUCT IMAGE --}}
                            <a href="{{ route('product.user.show', $item->product->slug) }}">
                                <img
                                    src="{{ $item->product->getFirstMediaUrl('product_image') ?: asset('admin/images/no-image.png') }}"
                                    class="rounded-2 border"
                                    style="width:70px;height:70px;object-fit:cover"
                                    alt="{{ $item->product->product_title }}">
                            </a>

                            {{-- PRODUCT INFO --}}
                            <div>
                                <div class="fw-semibold">
                                    <a href="{{ route('product.user.show', $item->product->slug) }}"
                                       class="text-dark text-decoration-none">
                                        {{ $item->product->product_title }}
                                    </a>
                                </div>

                                <small class="text-muted">
                                    Qty: {{ $item->quantity }} • ₹{{ number_format($item->price) }}
                                </small>
                            </div>

                        </div>
                    @endforeach

                    {{-- MORE ITEMS --}}
                    @if($order->items->count() > 2)
                        <small class="text-muted">
                            +{{ $order->items->count() - 2 }} more item(s)
                        </small>
                    @endif

                </div>

                {{-- ACTIONS --}}
                <div class="border-top p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="text-muted small">
                        {{ $order->items->count() }} item(s) requested
                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('user.order.details', $order->id) }}"
                           class="btn btn-outline-dark btn-sm">
                            View Inquiry Details
                        </a>

                        @if($order->status === 'completed')
                            <span class="badge bg-light text-success border">
                                Responded
                            </span>
                        @endif

                    </div>
                </div>

            </div>

        @empty

            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-3 shadow-sm p-5 text-center">
                <h5 class="fw-semibold mb-2">No inquiries yet</h5>
                <p class="text-muted mb-4">
                    Looks like you haven’t submitted any inquiries.
                </p>
                <a href="{{ route('orders.index') }}" class="btn btn-dark px-4">
                    Browse Products
                </a>
            </div>

        @endforelse

        @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</section>

<x-footer />

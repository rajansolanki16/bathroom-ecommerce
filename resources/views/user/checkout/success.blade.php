<x-header :meta="array('title' => 'Order Placed Successfully','description' => 'Thank you for your purchase')" />

<main class="ko-container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 64px;"></i>
                    </div>

                    <h3 class="fw-bold mb-2">Thank you for your order!</h3>
                    <p class="text-muted mb-4">
                        Your order has been placed successfully.
                        We’ll contact you shortly with delivery details.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            Continue Shopping
                        </a>

                        <a href="{{ route('cart.index') ?? '#' }}" class="btn btn-outline-secondary">
                            View Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<x-footer />

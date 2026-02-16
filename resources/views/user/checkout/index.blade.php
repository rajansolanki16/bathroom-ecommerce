<x-header :meta="array(
    'title' => 'Product Inquiry - E-commerce Store',
    'description' => 'Send your inquiry'
)" />

@if ($errors->any()) <div class="alert alert-danger"> {{ $errors->first() }} </div> @endif
@if (session('error'))<div class="alert alert-danger"> {{ session('error') }} </div> @endif
@if (session('success')) <div class="alert alert-success"> {{ session('success') }} </div> @endif

<main class="ko-container py-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Product Inquiry</h2>
            <p class="text-muted mb-0">Submit your inquiry for the selected products</p>
        </div>
    </div>

    {{-- @if(session('applied_coupon'))
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            <span>
                Reference Code <strong>{{ session('applied_coupon')['code'] }}</strong> attached
            </span>

            <form method="POST" action="{{ route('checkout.remove.coupon') }}">
                @csrf
                <button class="btn btn-sm btn-outline-danger">Remove</button>
            </form>
        </div>
    @else
        <form method="POST" action="{{ route('checkout.apply.coupon') }}" class="mb-3">
            @csrf
            <div class="input-group">
                <input type="text" name="code" class="form-control" placeholder="Enter reference code (optional)">
                <button class="btn btn-outline-secondary">Attach</button>
            </div>
        </form>
    @endif --}}

    <form method="POST" action="{{ route('checkout.place') }}">
        @csrf

        <div class="row g-4">
            <!-- LEFT : Inquiry Details -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-4">Your Details</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="John Doe"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="john@example.com"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    placeholder="+91 98765 43210"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Message / Inquiry Details</label>
                                <textarea
                                    name="address"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Write your inquiry, quantity requirement, company name, or any specific request..."
                                    required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT : Inquiry Summary -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-4">Selected Products</h5>

                        @foreach($cart as $item)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img
                                        src="{{ asset('storage/'.$item['image']) }}"
                                        width="50"
                                        height="50"
                                        class="rounded"
                                        style="object-fit: cover">

                                    <div>
                                        <div class="fw-medium">{{ $item['name'] }}</div>
                                        <small class="text-muted">
                                            Qty: {{ $item['quantity'] }}
                                        </small>
                                    </div>
                                </div>

                                <div class="fw-semibold text-muted">
                                    For Inquiry
                                </div>
                            </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Items</span>
                            <span>{{ $cart->sum('quantity') }}</span>
                        </div>

                        <div class="d-flex justify-content-between fs-6 fw-bold mb-4">
                            <span>Inquiry Type</span>
                            <span>Product Inquiry</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Submit Inquiry
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                📩 Our team will contact you soon
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
</script>

<x-footer />

@extends('layout.app')

@section('title', 'Your Inquiry Cart - Hardware Store')

@section('content')

<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Inquiry Checkout</h1>
                    <p class="mb-4">Please provide your details to receive a formal quote for your selected items.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="untree_co-section">
    <div class="container">
        
        @if ($errors->any() || session('error') || session('success'))
        <div class="row mb-4">
            <div class="col-md-12">
                @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
                @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
                @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('checkout.place') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Inquiry Details</h2>
                    <div class="p-3 p-lg-5 border bg-white">
                        
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="name" class="text-black">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-6">
                                <label for="email" class="text-black">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="+91 ..." required value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <label for="address" class="text-black">Message / Requirement Details <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" cols="30" rows="5" class="form-control" 
                                    placeholder="Tell us about your specific requirements, quantity, or company name..." required>{{ old('address') }}</textarea>
                            </div>
                        </div>

                        {{-- Optional Coupon/Reference Code Section --}}
                        <div class="form-group mt-4">
                            <label for="c_code" class="text-black">Reference Code (Optional)</label>
                            <div class="input-group w-100">
                                <input type="text" name="coupon_code" class="form-control" id="c_code" placeholder="Code">
                                <div class="input-group-append">
                                    <button class="btn btn-black btn-sm" type="button">Apply</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Inquiry Summary</h2>
                            <div class="p-3 p-lg-5 border bg-white">
                                <table class="table site-block-order-table mb-5">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cart as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/'.$item['image']) }}" alt="Image" class="img-fluid rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    <span>{{ $item['name'] }} <strong class="mx-1">x</strong> {{ $item['quantity'] }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end text-muted small">For Inquiry</td>
                                        </tr>
                                        @endforeach
                                        
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Total Items</strong></td>
                                            <td class="text-black text-end"><strong>{{ $cart->sum('quantity') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Inquiry Type</strong></td>
                                            <td class="text-black text-end">Product Request</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="border p-3 mb-4 bg-light rounded">
                                    <p class="mb-0 small text-muted">
                                        <strong>Note:</strong> This is not a purchase. Our sales team will review your requirements and contact you with the best pricing and availability within 24 hours.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-black btn-lg py-3 btn-block w-100">Submit Inquiry Request</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
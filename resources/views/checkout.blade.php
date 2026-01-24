<x-header :meta="array('title'=> getSetting('page_checkout_meta_title'), 'description'=> getSetting('page_checkout_meta_description'))" />

    <main>
        <section class="ko-banner" style="background-image: url('{{ publicPath('assets/images/cart-banner.webp') }}');">
            <div class="ko-container">
                <div class="ko-banner-content">
                    <h2>Checkout</h2>
                    {!! getSetting('page_checkout_description') !!}
                    <nav>
                        <ol class="ko-banner-list">
                            <li><a href="{{ route('view.home') }}">Home</a></li>
                            <li class="active">Checkout</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        <section class="ko-checkout-section">
            <div class="ko-container">
                <div class="ko-row">
                    <div class="ko-col-8">
                        <div class="ko-checkout-form">
                            <form action="{{ route('checkout') }}" method="post">
                                @csrf
                                <div class="ko-checkout-infoBlock">
                                    <div class="ko-infoBlock-head">
                                        <h3 class="ko-checkout-title">Contact information</h3>

                                        @if(!auth()->check())
                                            <a href="{{ route('login') }}">Login</a>
                                        @endif
                                    </div>
                                    <p>We'll use this email to send you details and updates about your booking.</p>

                                    <div class="ko-form-group">
                                        <input type="text" name="name" class="ko-form-control @error('name') is-invalid @enderror" placeholder="" required value="{{ old('name', ($user->name ?? null)) }}" {{ isset($user) ? 'disabled':''}}/>
                                        <label for="" class="ko-form-label">Full Name</label>
                                    </div>
                                    @error('name')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror

                                    <div class="ko-form-group">
                                        <input type="email" name="email" class="ko-form-control @error('email') is-invalid @enderror" placeholder="" required value="{{ old('email', ($user->email ?? null)) }}" {{ isset($user) ? 'disabled':''}}/>
                                        <label for="" class="ko-form-label">Email address</label>
                                    </div>
                                    @error('email')
                                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="ko-billingAddress-block">
                                    <h3 class="ko-checkout-title">Billing address</h3>
                                    <p>Enter the billing information that matches your payments.</p>
                                    <div class="ko-row">

                                        <div class="ko-col-6">
                                            <div class="ko-form-group">
                                                <select name="country" id="country_code" data-url="{{ route(name: "get.states") }}" class="ko-select-control" {{ isset($user) ? 'disabled':''}}>
                                                    <option value="Please select country code" disabled selected>Please select country code</option>
                                                    @foreach ($countries as $country)
                                                        <option
                                                            value="{{ $country->c_name }}"
                                                            data-country-code="{{ $country->c_code }}"
                                                            {{ $country->c_name == old('country',($user->country)?? 'India') ? 'selected' : '' }}>
                                                            {{ $country->c_code }} - {{ $country->c_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('country')
                                                    <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="ko-col-6">
                                            <div class="ko-form-group">
                                                <select name="state" data-value="{{ old('state',isset($sid->id) ? $sid->id:0 ) }}" id="state" class="ko-form-control" {{ isset($user) ? 'disabled':''}}/>
                                                    <option disabled selected>Please select state</option>
                                                </select>
                                                @error('state')
                                                    <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ko-checkout-infoBlock">
                                        <div class="ko-form-group">
                                            <input type="tel" name="mobile" id="ko-register-mobile" class="ko-form-control @error('mobile') is-invalid @enderror" placeholder="" required value="{{ old('mobile', ($user->mobile ?? "+91")) }}" {{ isset($user) ? 'disabled':''}}/>
                                            <label for="" class="ko-form-label">Phone</label>
                                        </div>
                                        @error('mobile')
                                            <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="ko-payment-block">
                                    <h3 class="ko-checkout-title">Payment options</h3>
                                    <div class="ko-payment-option">
                                        <ul>
                                            <li>
                                                <div class="ko-payment-radio">
                                                    <input type="radio" name="gateway" value="CASHFREE" {{ old('gateway') == 'CASHFREE' ? 'checked' : '' }} required/>
                                                    <label for="ko_bank_transfer1">Cashfree</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="ko-payment-radio">
                                                    <input type="radio" name="gateway" value="PAYUMONEY" {{ old('gateway') == 'PAYUMONEY' ? 'checked' : '' }} />
                                                    <label for="ko_bank_transfer2">Payumoney</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="ko-payment-radio">
                                                    <input type="radio" name="gateway" value="CASH" {{ old('gateway') == 'CASH' ? 'checked' : '' }} />
                                                    <label for="ko_bank_transfer2">Cash On Check-In</label>
                                                </div>
                                            </li>
                                        </ul>
                                        @error("gateway")
                                            <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="ko-checkbox-group">
                                        <input type="checkbox" class="ko-form-checkbox" id="add_customer_note" />
                                        <label for="account_with_hotel" class="ko-form-label">Add a note to your order</label>
                                        <textarea name="guest_note" class="ko-form-control ko-customer-note" style="display: none" placeholder="Write your customer note here">{{ old('guest_note') }}</textarea>
                                    </div>
                                    <p class="ko-paymentProccess-ctn">By proceeding with your purchase you agree to our Terms and Conditions and Privacy Policy</p>
                                </div>

                                @error("general")
                                    <div class="invalid-response" style="display:flex">{{ $message }}</div>
                                @enderror
                                <div class="ko-payment-btns">
                                    <a href="{{ route('view.home') }}" class="ko-btn ko-payment-back">Back to home</a>
                                    <button type="submit" class="ko-btn ko-payment-submit">Confirm Payment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="ko-col-4">
                        <div class="ko-cart-totals-wrap">
                            <h3>Booking summary</h3>
                            <ul>
                                <li class="ko-payment-roomDetails">
                                    <div class="ko-payemntRoom-img">
                                        <img src="{{ publicPath($room->feature_img) }}" alt="room img" />
                                        <span class="ko-room-qty">{{ $ac->room_count }}</span>
                                    </div>
                                    <div class="ko-room-info">
                                        <h5>{{ $room->name }}</h5>
                                        <strong>₹{{ $room->offer_price }}</strong>
                                    </div>
                                    <div class="ko-room-price">
                                        <span>₹{{ $ac->total_cost }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="ko-cartTotals-accordian">
                                        <div class="ko-cartTotals-head">
                                            <span>Add a coupon</span>
                                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true" class="wc-block-components-panel__button-icon" focusable="false">
                                                <path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ko-cartTotals-ctn">
                                            <div class="ko-addCoupon-wrap">
                                                <div class="ko-addCoupon-control">
                                                    <input type="text" autocomplete="off" placeholder="" name="" id="ko_add_coupon" />
                                                    <label for="ko_add_coupon">Enter code</label>
                                                </div>
                                                <button type="button" class="ko-btn ko-addCoupon-btn">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="ko-cart-subtotal">
                                    <span>Subtotal</span>
                                    <strong>₹{{ $ac->total_cost }}</strong>
                                </li>
                                <li class="ko-cart-total">
                                    <strong>Total</strong>
                                    <strong>₹{{ $ac->total_cost }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
    $(document).ready(function() {
        get_states();
    });
    </script>
    
<x-footer />
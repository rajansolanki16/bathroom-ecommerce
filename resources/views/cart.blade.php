<x-header :meta="array('title'=> getSetting('page_cart_meta_title'), 'description'=> getSetting('page_cart_meta_description'))"/>

<main>
    <section class="ko-banner" style="background-image: url('{{ publicPath('assets/images/cart-banner.webp') }}');">
        <div class="ko-container">
            <div class="ko-banner-content">
                <h2>Cart</h2>
                {!! getSetting('page_cart_description') !!}
                <nav>
                    <ol class="ko-banner-list">
                        <li><a href="{{ route('view.home') }}">Home</a></li>
                        <li class="active">Room's Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <form action="{{ route('cart.store') }}" method="post">
        @csrf
        <section class="ko-cart-section">
            <div class="ko-container">
                <div class="ko-row">
                    <div class="ko-col-8">
                        <div class="ko-cart-table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Rooms</th>
                                        <th></th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="ko-cart-room-img">
                                                <a href="{{ route('view.room', $room->slug) }}" target="_blank">
                                                    <img src="{{ publicPath($room->feature_img) }}" alt="room img" />
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="ko-room-details">
                                                <h4>{{ $room->name }}</h4>
                                                <span class="ko-room-price">₹{{ $room->offer_price }}</span>
                                                <ul>
                                                    <li>
                                                        <span><strong>Date: </strong>
                                                            {{ \Carbon\Carbon::parse($ac->check_in)->format('Y-m-d') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($ac->check_out)->format('Y-m-d') }}
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <span><strong>Details: </strong> Extra Bed:
                                                            <small>{{ $ac->extra_beds }}</small>, Adult:
                                                            <small>{{ $ac->adults }}</small>, Children:
                                                            <small>{{ $ac->children }}</small></span>
                                                    </li>
                                                    @if(count($services) > 0)
                                                        <li>
                                                            <span>
                                                                <strong>Extra Services: </strong>
                                                                @foreach ($services as $service)
                                                                    {{ $service->name }},
                                                                @endforeach
                                                            </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                                <div class="ko-counter-wrap">
                                                    <div class="ko-qty-counter">
                                                        <button type="button" class="ko-qty-minus"id="ko_cart_room_count_dec">-</button>
                                                        <input  type="text" name="count" class="ko-qty-input" id="ko_cart_room_count" value="{{ $ac->room_count }}" data-max="{{ $room->quantity }}" />
                                                        <button type="button" class="ko-qty-plus" id="ko_cart_room_count_inc">+</button>
                                                    </div>

                                                    <button id="ko_cart_remove_room" data-url="{{ route('cart.remove_item', $ac->id) }}"  class="ko-remove-room">Remove List</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>₹<span id="ko_cart_cost_total" data-total_cost="{{ $ac->total_cost }}">{{ $ac->total_cost }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="ko-col-4">
                        <div class="ko-cart-totals-wrap">
                            <h3>Cart totals</h3>
                            <ul>
                                <li>
                                    <div class="ko-cartTotals-accordian">
                                        <div class="ko-cartTotals-head">
                                            <span>Add a coupon</span>
                                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" aria-hidden="true"
                                                class="wc-block-components-panel__button-icon" focusable="false">
                                                <path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ko-cartTotals-ctn">
                                            <div class="ko-addCoupon-wrap">
                                                <div class="ko-addCoupon-control">
                                                    <input type="text" autocomplete="off" placeholder="" name=""
                                                        id="ko_add_coupon" />
                                                    <label for="ko_add_coupon">Enter code</label>
                                                </div>
                                                <button type="button" class="ko-btn ko-addCoupon-btn">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="ko-cart-subtotal">
                                    <span>Subtotal</span>
                                    <strong>₹<span id="ko_cart_sub_total">{{ $ac->total_cost }}</span></strong>
                                </li>
                                <li class="ko-cart-total">
                                    <strong>Total</strong>
                                    <strong>₹<span id="ko_cart_grand_total">{{ $ac->total_cost }}</span></strong>
                                </li>
                            </ul>

                            @error('count')
                                <div class="invalid-response h1 text-center" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            @error('general')
                                <div class="invalid-response h1 text-center" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            <input type="hidden" id="cart-data-hiddens" data-total_cost="{{ $ac->total_cost }}" data-qty="{{ $ac->room_count }}" data-rp="{{ $room->offer_price }}" data-c_in="{{ \Carbon\Carbon::parse($ac->check_in)->format('Y-m-d') }}" data-c_out="{{ \Carbon\Carbon::parse($ac->check_out)->format('Y-m-d') }}" /> 
                            <button type="submit" class="ko-btn">Proceed to Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        </section> 
    </form>
</main>

<x-footer />
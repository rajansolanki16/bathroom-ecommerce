<x-header :meta="array('title'=> $room->name, 'description'=> getSetting('page_room_meta_description'))"/>


@php
    $gallery = json_decode($room->gallery_img);
    $bed = json_decode($room->beds);
    $amenities = json_decode($room->amenities);
    $def_services = json_decode($room->service);

    if(isset($find_booking) && count($find_booking)){
        if (strtotime($find_booking['check_in']) < strtotime(date('Y-m-d'))) {
            $find_booking = [];
        }
    }else{
        $find_booking = [];
    }
@endphp

<main>
    <section class="ko-banner" style="background-image: url('{{ publicPath($room->feature_img) }}');">
        <div class="ko-container">
            <div class="ko-banner-content">
                <h2>{{ $room->name }}</h2>
                <p>{!! getSetting('page_room_description') !!}</p>
                <nav>
                    <ol class="ko-banner-list">
                        <li><a href="{{ route('view.home') }}">Home</a></li>
                        <li><a href="{{ route('view.rooms') }}">Rooms</a></li>
                        <li class="active">{{ $room->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <div style="height:40px; "></div>
    @if (count($gallery) > 0)
        <section class="ko-splide-rooms splide" role="group" aria-label="Splide Basic HTML Example">
            <div class="splide__track">
                <ul class="splide__list">
                    @if (count($gallery) == 1)
                        @foreach ($gallery as $url)
                            <li class="splide__slide">
                                <div class="ko-rooms">
                                    <img src="{{ publicPath($url) }}" alt="room-img" />
                                </div>
                            </li>
                            <li class="splide__slide">
                                <div class="ko-rooms">
                                    <img src="{{ publicPath($url) }}" alt="room-img" />
                                </div>
                            </li>
                            @php
                                exit;
                            @endphp
                        @endforeach
                    @else
                        @foreach ($gallery as $url)
                            <li class="splide__slide">
                                <div class="ko-rooms">
                                    <img src="{{ publicPath($url) }}" alt="room-img" />
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </section>
    @endif

    <section class="ko-deluxe-info">
        <div class="ko-container">
            <div class="ko-deluxe-info-content">
                <div class="ko-deluxe-details">
                    <h2>{{ $room->name }}</h2>
                    <ul class="ko-deluxe-details-list">
                        <li>{{ $room->allowd_guests }} Guests</li>
                        <li>{{ $room->size }} Feets Size</li>
                        <li>{{ $bed->quentity }} {{ $bed->name }}</li>
                    </ul>
                    <p>{!! $room->description !!}</p>
                    @if ($amenities)
                        <div class="ko-room-amenities">
                            <h4 class="ko-com-title">Room Amenities</h4>
                            <ul class="ko-room-amenities-list">
                                @foreach ($amenities as $aid)
                                    @php
                                        $amenity = App\Models\Amenity::find($aid);
                                    @endphp
                                    @if ($amenity->id)
                                        <li><img src="{{ publicPath($amenity->icon) }}" width="35" height="35" style="margin-right:1rem;" />
                                            {{ $amenity->name }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(strlen($room->features) > 1)
                        <div class="ko-room-features">
                            <h4 class="ko-com-title">Room Features</h4>
                            {!! $room->features !!}
                        </div>
                    @endif

                    <div class="ko-room-features" style="margin-top:1rem">
                        <h4 class="ko-com-title">Hotel Surroundings</h4>
                        {!! getSetting("hotel_surroundings") !!}
                    </div>

                    <div class="ko-room-features" style="margin:2.5rem 0px;">
                        <h4 class="ko-com-title">Hotel Rules</h4>
                        {!! getSetting("hotel_rules") !!}
                    </div>

                    @if ($room->tour_video)
                        <div class="ko-tour-video-section">
                            <h4 class="ko-com-title">Room Tour</h4>
                            <video style="border-radius:20px;" crossorigin="anonymous" aria-label="Video" src="{{ publicPath($room->tour_video) }}"
                                controlslist="nodownload" autoplay playsinline muted="muted" loop preload="auto"
                                type="video/m3u8" x-webkit-airplay="allow" width="100%" height="500"></video>
                        </div>
                    @endif
                    
                </div>
                <div class="ko-deluxe-reserve">
                    <form action="{{ route('book.stay') }}" method="post" id="ko_booking_form">
                        @csrf

                        <div class="ko-reserve-title">
                            <h2 class="ko-resecomm-title">Reserve</h2>
                            <p>From <del>₹{{ $room->price }}</del> <strong>₹{{ $room->offer_price }}</strong> night</p>
                        </div>

                        <div class="ko-booking-date-control">
                            <div class="ko-form-group">
                                <h6 class="field-label">Check In</h6>
                                <input type="text" id="booking-data-check_in" class="booking-date-picker form-control checkin_date_picker" name="check_in" value="{{ old('check_in', (count($find_booking) > 0 ? $find_booking['check_in'] : date('Y-m-d'))) }}" data-old="{{ old('check_in', (count($find_booking) > 0 ? $find_booking['check_in'] : date('Y-m-d'))) }}" placeholder="Checkin date">
                            </div>
                            <div class="ko-form-group">
                                <h6 class="field-label">Check Out</h6>
                                <input type="text" id="booking-data-check_out" class="booking-date-picker form-control checkout_date_picker" name="check_out" value="{{ old('check_out', (count($find_booking) > 0 ? $find_booking['check_out'] : date('Y-m-d', strtotime('+1 day')))) }}" data-old="{{ old('check_out', (count($find_booking) > 0 ? $find_booking['check_out'] : date('Y-m-d', strtotime('+1 day')))) }}" placeholder="Checkout date" >
                            </div>
                        </div>

                        <div class="ko-room-control">
                            <div class="ko-quantity-selector">
                                <h4>Adult</h4>
                                <div class="ko-selector-wrap qty-container">
                                    <span class="ko-count-minus room-control-btn qty-btn-minus">-</span>
                                    <input type="number" class="ko-count input-qty" id="booking-data-adults" name="adults" value="{{ old('adults',2) }}" />
                                    <span class="ko-count-plus room-control-btn qty-btn-plus">+</span>
                                </div>
                            </div>
                            <div class="ko-quantity-selector">
                                <h4>Children</h4>
                                <div class="ko-selector-wrap qty-container">
                                    <span class="ko-count-minus room-control-btn qty-btn-minus">-</span>
                                    <input type="number" class="ko-count input-qty" id="booking-data-children" name="children" value="{{ old('children',0) }}" />
                                    <span class="ko-count-plus room-control-btn qty-btn-plus">+</span>
                                </div>
                            </div>
                            <div class="ko-quantity-selector">
                                <h4>Rooms</h4>
                                <div class="ko-selector-wrap qty-container">
                                    <span class="ko-count-minus room-control-btn qty-btn-minus">-</span>
                                    <input type="number" class="ko-count input-qty" id="booking-data-quantity" name="quantity"  value="{{ old('quantity', (count($find_booking) > 0 ? $find_booking['quantity'] : 1 )) }}" data-price="{{ $room->offer_price }}"/>
                                    <span class="ko-count-plus room-control-btn qty-btn-plus" >+</span>
                                </div>
                            </div>
                            <div class="ko-quantity-selector">
                                <h4>Extra Bed</h4>
                                <div class="ko-selector-wrap qty-container">
                                    <span class="ko-count-minus room-control-btn qty-btn-minus">-</span>
                                    <input type="number" class="ko-count input-qty" id="booking-data-extra_beds" name="extra_beds"  value="{{ old('extra_beds',0) }}" data-price="{{ $room->bed_price }}" />
                                    <span class="ko-count-plus room-control-btn qty-btn-plus">+</span>
                                </div>
                            </div>
                        </div>

                        @if (count($services) > 0)
                            <div class="ko-extra-service">
                                <h2 class="ko-resecomm-title">Extra Services</h2>
                                <ul class="ko-extra-service-list">
                                    @foreach ($services as $service)
                                        <li>
                                            <div class="ko-check-wrap">
                                                <input type="checkbox" class="form-check-input" id="booking-data-services" name="services[]" value="{{ $service->id }}" data-price="{{ $service->price }}" {{ in_array($service->id, old('services', $def_services ?? [])) ? 'checked' : '' }} {{ in_array($service->id, ($def_services ?? []))? 'disabled' : '' }} />
                                                <label for="btncheck">{{ $service->name }}</label>
                                            </div>
                                            <p>₹{{ in_array($service->id, ($def_services ?? []))? 0 : $service->price }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        @endif

                        <div class="ko-room-page-errors">
                            @error('check_in')
                                <div class="invalid-response ko-room-error" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            @error('room')
                                <div class="invalid-response ko-room-error" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            @error('check_out')
                                <div class="invalid-response ko-room-error" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            @error('quantity')
                                <div class="invalid-response ko-room-error" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            @error('adults')
                                <div class="invalid-response ko-room-error" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            @error('children')
                                <div class="invalid-response ko-room-error" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            @error('extra_beds')
                                <div class="invalid-response ko-room-error" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                            @error('general')
                                <div class="invalid-response ko-room-error" style="font-size: 1rem; display:block;" >{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="ko-total-cost">
                            <h2 class="ko-resecomm-title">Total Cost</h2>
                            <p class="ko-resecomm-title">₹<span id="booking-grand-total">0</span></p>
                        </div>

                        <input type="hidden" name="room" id="booking-data-hiddens" value="{{ $room->slug}}" data-url="{{ route('check.availability') }}" data-max_guest="{{ $room->allowd_guests }}" data-max_rooms="{{ $room->quantity }}" data-max_extra_beds = {{ $room->extra_beds }} />
                        <button type="submit" class="ko-btn ko-book-btn" id="ko-book-form-sumbit" >Book Your Stay</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ------map section start------ -->
    <section class="ko-map-section">
        <iframe src="{{ getSetting("map_link") }} " width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
    <!-- ------map section end------ -->
</main>

<x-footer />
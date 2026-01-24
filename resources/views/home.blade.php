<x-header :meta="array('title'=> getSetting('page_home_meta_title'), 'description'=> getSetting('page_home_meta_description'))" />

    <main>
        <!-- ------------Hero section start------------ -->
        <section class="ko-hero-section">
            <div class="ko-hero-img">
                <img src="{{ publicPath(getSetting('home_top_section')) }}" alt="hero-img" />
            </div>
            <div class="ko-container">

                <div class="ko-hero-ctn">
                    <h1>{{ getSetting('home_top_heading') }}</h1>
                    {!! getSetting('home_top_description') !!}
                    <a href="{{ getSetting('home_top_btn_link') }}" class="ko-btn">{{ getSetting('home_top_btn_text') }}</a>
                    <div class="ko-hero-counter">
                        <ul>
                            <li>
                                <h3><span id="customers">{{ getSetting('home_top_counter_1_count') }}</span></h3>
                                <span>{{ getSetting('home_top_counter_1_text') }}</span>
                            </li>
                            <li>
                                <h3><span id="reviews">{{ getSetting('home_top_counter_2_count') }}</span></h3>
                                <span>{{ getSetting('home_top_counter_2_text') }}</span>
                            </li>
                            <li>
                                <h3><span id="experiences">{{ getSetting('home_top_counter_3_count') }}</span></h3>
                                <span>{{ getSetting('home_top_counter_3_text') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- ------------Hero section end------------ -->

        <!-- ------------Reservation block start------------- -->
        <section class="ko-reservation-section">
            <div class="ko-container">
                <div class="ko-reservation-form">
                    <div class="ko-reservation-title">
                        <h3>Reservation</h3>
                    </div>
                    <div class="ko-reservation-inner">
                        <form action="{{ route('home.book') }}" method="post">
                            @csrf

                            <div class="ko-reservation-wrap">
                                <div class="ko-reservation-group">
                                    <h6>Check In</h6>
                                    <input class="ko-reservation-date checkin_date_picker" type="text" name="check_in" value="{{ old('check_in', date('Y-m-d')) }}" data-old="{{ old('check_in', date('Y-m-d')) }}" placeholder="Checkin date" />
                                </div>
                                <div class="ko-reservation-group">
                                    <h6>Check Out</h6>
                                    <input class="ko-reservation-date checkout_date_picker" type="text" name="check_out" value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}" data-old="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}" placeholder="Checkout date" />
                                </div>
                            </div>
                             <div class="ko-reservation-wrap">
                                <div class="ko-reservation-group">
                                    <h6>Rooms</h6>
                                    <div class="ko-number qty-container">
                                        <select class="ko-rooms-info ko-home-room-select" name="room_type" required>
                                            <option disabled selected>select room</option>
                                            @if(count($rooms ?? [])> 0)
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->slug }}" {{ (old('room_type') == $room->slug)?'selected':''  }} data-max_qty={{ $room->quantity}}  >{{ $room->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="ko-reservation-group">
                                    <h6>No. of Rooms</h6>
                                    <div class="ko-number qty-container">
                                        <span class="ko-minus qty-btn-minus">-</span>
                                        <input type="text" class="ko-num-input input-qty" id="ko-home-room-qty" value="{{ old('quantity',1) }}" name="quantity" required />
                                        <span class="ko-plus qty-btn-plus">+</span>
                                    </div>
                                </div>
                             </div>
                             <div class="">
                                <button type="submit" class="ko-btn">Check Availability</button>
                             </div>
                        </form>
                    </div>
                </div>
                @error('check_in')
                    <div class="invalid-response ko-home-error" style="font-size: 1rem; display:block;" >{{ $message}}</div>
                @enderror
                @error('check_out')
                    <div class="invalid-response ko-home-error" style="font-size: 1rem; display:block;" >{{ $message}}</div>
                @enderror
                @error('room_type')
                    <div class="invalid-response ko-home-error" style="font-size: 1rem; display:block;" >{{ $message}}</div>
                @enderror
                @error('quantity')
                    <div class="invalid-response ko-home-error" style="font-size: 1rem; display:block;" >{{ $message}}</div>
                @enderror
                @error('quick_reserve')
                    <div class="invalid-response ko-home-error" style="font-size: 1rem; display:block;" >{{ $message}}</div>
                @enderror
            </div>
        </section>
        <!-- ------------Reservation block end------------- -->

        <!-- -----------about section start----------------  -->
        <section class="ko-about-section">
            <div class="ko-container">
                <div class="ko-about-row">
                    <div class="ko-about-col">
                        <div class="ko-about-main-img-inner">
                            <div class="ko-about-main-img">
                                <img src="{{ publicPath(getSetting('home_middle_img_1')) }}" alt="img-main" class="ko-about-img-1" />
                            </div>
                            <div class="ko-about-main-img-2">
                                <img src="{{ publicPath(getSetting('home_middle_img_2')) }}" alt="img-2" class="ko-about-img-2" />
                            </div>
                        </div>

                    </div>
                    <div class="ko-about-col">
                        <div class="ko-about-content">
                            <span>Welcome To Hotel</span>
                            <h3>{{ getSetting('home_middle_heading') }}</h3>
                            {!! getSetting('home_middle_description') !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- -----------about section end---------------- -->

        <!-- --------------testimonials section start---------------- -->
        @php
            $reviews = json_decode(getSetting('home_review_area')) ?? [];
        @endphp

        @if (count($reviews ?? []) > 0)
            <section class="ko-testimonials-section" role="group" aria-label="Splide Basic HTML Example">
                <div class="ko-splide-testimonials splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            
                            @foreach ($reviews as $review)
                                <li class="splide__slide">
                                    <div class="ko-testimonials">
                                        <ul class="ko-testimonials-list">
                                            @for ($s=1; $s<6; $s++)
                                                @if ($s <= $review->rate)
                                                    <li class="full"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" baseProfile="tiny" height="24px" id="Layer_1" version="1.2" viewBox="0 0 24 24" width="24px" xml:space="preserve"><g><path d="M9.362,9.158c0,0-3.16,0.35-5.268,0.584c-0.19,0.023-0.358,0.15-0.421,0.343s0,0.394,0.14,0.521    c1.566,1.429,3.919,3.569,3.919,3.569c-0.002,0-0.646,3.113-1.074,5.19c-0.036,0.188,0.032,0.387,0.196,0.506    c0.163,0.119,0.373,0.121,0.538,0.028c1.844-1.048,4.606-2.624,4.606-2.624s2.763,1.576,4.604,2.625    c0.168,0.092,0.378,0.09,0.541-0.029c0.164-0.119,0.232-0.318,0.195-0.505c-0.428-2.078-1.071-5.191-1.071-5.191    s2.353-2.14,3.919-3.566c0.14-0.131,0.202-0.332,0.14-0.524s-0.23-0.319-0.42-0.341c-2.108-0.236-5.269-0.586-5.269-0.586    s-1.31-2.898-2.183-4.83c-0.082-0.173-0.254-0.294-0.456-0.294s-0.375,0.122-0.453,0.294C10.671,6.26,9.362,9.158,9.362,9.158z"/></g></svg></li>
                                                @else
                                                    <li class="empty"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" baseProfile="tiny" height="24px" id="Layer_1" version="1.2" viewBox="0 0 24 24" width="24px" xml:space="preserve"><g><path d="M9.362,9.158c0,0-3.16,0.35-5.268,0.584c-0.19,0.023-0.358,0.15-0.421,0.343s0,0.394,0.14,0.521    c1.566,1.429,3.919,3.569,3.919,3.569c-0.002,0-0.646,3.113-1.074,5.19c-0.036,0.188,0.032,0.387,0.196,0.506    c0.163,0.119,0.373,0.121,0.538,0.028c1.844-1.048,4.606-2.624,4.606-2.624s2.763,1.576,4.604,2.625    c0.168,0.092,0.378,0.09,0.541-0.029c0.164-0.119,0.232-0.318,0.195-0.505c-0.428-2.078-1.071-5.191-1.071-5.191    s2.353-2.14,3.919-3.566c0.14-0.131,0.202-0.332,0.14-0.524s-0.23-0.319-0.42-0.341c-2.108-0.236-5.269-0.586-5.269-0.586    s-1.31-2.898-2.183-4.83c-0.082-0.173-0.254-0.294-0.456-0.294s-0.375,0.122-0.453,0.294C10.671,6.26,9.362,9.158,9.362,9.158z"/></g></svg></li>
                                                @endif
                                            @endfor
                                        </ul>
                                        <h2>{{ $review->review }}</h2>
                                        <p>{{ $review->name }}</p>
                                    </div>
                                </li>
                            @endforeach
                            
                            
                        </ul>
                    </div>
                </div>
            </section>
        @endif
        <!-- --------------testimonials section end---------------- -->

        <!-- ----------accomodation section start----------- -->
        <section class="ko-accomodation">
            <div class="ko-container">
                <h3 class="ko-subtitle">Our Rooms</h3>
                <h2 class="ko-title">Accomodation</h2>
                <div class="ko-accomodation-cards-wrap">
                    @if(count($rooms ?? [])> 0)
                        @foreach ($rooms as $room)
                            <div class="ko-accomodation-card">
                                <div class="ko-accomodation-inner-card">
                                    <div class="ko-accomodation-graphic">
                                        <a href="{{ route('view.room', $room->slug ) }}"><img src="{{ publicPath($room->feature_img) }}" alt="room-img" /></a>
                                    </div>
                                    <div class="ko-accomodation-card-content">
                                        <h2>{{ $room->name }}</h2>
                                        <ul class="ko-accomodation-list">
                                            <li>{{ $room->allowd_guests }} Guests</li>
                                            <li>{{ $room->size }} Feets Size</li>
                                            <li>₹{{ $room->offer_price }} </li>
                                        </ul>
                                    </div>
                                    <a class="ko-btn" href="{{ route('view.room', $room->slug ) }}">Book Now</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        <!-- ----------accomodation section end----------- -->

        <!-- --------------------facilities section start------------------- -->
        @if (!empty($blogs) && count($blogs ?? []) > 2)
            
            <section class="ko-facilities-section">
                <div class="ko-facilities-row">
                    @foreach ($blogs as $blog)
                    
                        <div class="ko-facilities-col">
                            <div class="ko-facilities-imgblock">
                                <img src="{{ publicPath($blog->image) }}" alt="facilities" />
                                <h4>{{ $blog->title }}</h4>
                            </div>
                            <div class="ko-facilities-content">
                                <h3>{{ $blog->title }}</h3>
                                <a href="{{ route('blog.list', $blog->slug) }}" class="ko-btn">Read More</a>
                            </div>
                        </div>
                    
                    @endforeach

                </div>
            </section>
        @endif
        <!-- --------------------facilities section end--------------------- -->

    </main>
<x-footer />

<x-header :meta="array('title'=> getSetting('page_about_meta_title'), 'description'=> getSetting('page_about_meta_description'))" />


    <main>
        <!-- banner section start-->
        <section class="ko-banner" style="background-image: url('{{ publicPath('assets/images/cart-banner.webp')}} ');">
            <div class="ko-container">
                <div class="ko-banner-content">
                    <h2>About Us</h2>
                    {!! getSetting('page_about_description') !!}
                    <nav>
                        <ol class="ko-banner-list">
                            <li><a href="{{ route('view.home') }}">Home</a></li>
                            <li class="active">About Us</li>
                        </ol>
                      </nav>
                </div>
            </div>
        </section>
        <!-- banner section end-->

        <!-- about us section start -->
         <section class="ko-aboutus-hoh">
            <div class="ko-container">
                <div class="ko-hoh-content-wrap">
                    <div class="ko-col-6">
                        <h4 class="ko-sub-tit">Welcome</h4>
                        <h2 class="ko-tit">{{ getSetting('about_welcome_title') }}</h2>
                        {!! getSetting('about_welcome_description') !!}
                    </div>
                    <div class="ko-col-6">
                        <div class="ko-hoh-graphic-cards">
                            <div class="ko-hoh-inner-card">
                                <img src="{{ publicPath(getSetting('about_welcome_img_1')) }}"/>
                            </div>
                            <div class="ko-hoh-inner-card">
                                <div class="ko-menu-selection">
                                    <h2>{{ getSetting('about_welcome_counter_count_1') }}</h2>
                                    <p>{{ getSetting('about_welcome_counter_text_1') }}</p>
                                </div>
                            </div>
                            <div class="ko-hoh-inner-card">
                                <div class="ko-menu-selection">
                                    <h2>{{ getSetting('about_welcome_counter_count_2') }}</h2>
                                    <p>{{ getSetting('about_welcome_counter_text_2') }}</p>
                                </div>
                            </div>
                            <div class="ko-hoh-inner-card">
                                <img src="{{ publicPath(getSetting('about_welcome_img_2')) }}" alt="fooddish" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </section>

         <section class="ko-aboutus-romfacility">
            <div class="ko-container">
                <div class="ko-romfacility-wrap">
                    <div class="ko-col-6">
                        @php
                            $selected_amenities = json_decode(getSetting('about_amenities')) ?? [];
                        @endphp
                        @if (count($selected_amenities)>0)
                            <h2 class="ko-tit">Room Facilities</h2>
                            <ul class="ko-romfacility-item">
                                @foreach ($selected_amenities as $selected_amenity)
                                    @php
                                        $amenity = App\Models\Amenity::find($selected_amenity)
                                    @endphp
                                    @if(isset($amenity->id))
                                        <li>
                                            <img src="{{ publicPath($amenity->icon) }}" class="ko-room-facilities-img"/> 
                                            <div class="ko-romfacility-item-cnt">
                                                <h3>{{ $amenity->name }}</h3>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="ko-col-6">
                        <div class="ko-romfacility-graphic">
                            <img src="{{ publicPath(getSetting('about_amenity_img_1')) }}" alt="romimg" />
                            <img src="{{ publicPath(getSetting('about_amenity_img_2')) }}" alt="romimg" />
                        </div>
                    </div>
                </div>
            </div>
         </section>
        <!-- about us section end -->

    </main>
    
<x-footer />
<x-header :meta="array('title'=> getSetting('page_contact_meta_title'), 'description'=> getSetting('page_contact_meta_description'))" />

    <main>
        <!-- banner section start-->
        <section class="ko-banner" style="background-image: url('{{ publicPath('assets/images/cart-banner.webp') }}');">
            <div class="ko-container">
                <div class="ko-banner-content">
                    <h2>Contact Us</h2>
                    {!! getSetting('page_contact_description') !!}
                    <nav>
                        <ol class="ko-banner-list">
                            <li><a href="{{ route('view.home') }}">Home</a></li>
                          <li class="active">Contact Us</li>
                        </ol>
                      </nav>
                </div>
            </div>
        </section>
        <!-- banner section end-->

        <!-- contact us form start -->
         <section class="ko-contact-us">
            <div class="ko-container">
                <div class="ko-contact-us-content">
                    <h3>Write a Message</h3>
                    <form action="{{ route('contact.mail') }}" method="post">
                        @csrf
                        <div class="ko-col-6">
                            <div class="ko-form-group">
                                <label for="fname" class="ko-contact-label @error('fname') is-invalid @enderror">First Name</label>
                                <input id="fname" name="fname" class="ko-form-control" type="text" value="{{ old('fname') }}" placeholder="First Name" required>
                                @error('fname')
                                    <span class="form-error-message invalid-response">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="ko-col-6">
                            <div class="ko-form-group">
                                <label for="lname"class="ko-contact-label @error('lname') is-invalid @enderror" >Last Name</label>
                                <input id="lname" name="lname" class="ko-form-control" type="text" value="{{ old('lname') }}" placeholder="Last Name" required>
                                @error('lname')
                                    <span class="form-error-message invalid-response">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="ko-col-12">
                            <div class="ko-form-group">
                                <label for="email"class="ko-contact-label @error('email') is-invalid @enderror" >Email <span>*</span></label>
                                <input id="email" name="email" class="ko-form-control" type="email" value="{{ old('email') }}" placeholder="Email Address" required>
                                @error('email')
                                    <span class="form-error-message invalid-response">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="ko-col-12">
                            <div class="ko-form-group">
                                <label for="subject" class="ko-contact-label @error('subject') is-invalid @enderror" >Subject</label>
                                <input id="subject" name="subject"  class="ko-form-control" type="text" value="{{ old('subject') }}" placeholder="Subject" required>
                                @error('subject')
                                    <span class="form-error-message invalid-response">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="ko-col-12">
                            <div class="ko-form-group">
                                <label for="message" class="ko-contact-label @error('message') is-invalid @enderror" >Your Message <span>*</span></label>
                                <textarea name="message" class="ko-form-control"  rows="4" cols="2" id="message" placeholder="Your Message" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="form-error-message invalid-response">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="ko-col-12">
                            <div class="ko-form-group">
                                <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response')
                                    <span class="form-error-message invalid-response">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="ko-col-12">
                            <button type="submit" class="ko-btn">Submit Your Message</button>
                        </div>
                    </form>
                </div>
            </div>
         </section>
        <!-- contact us form end -->

    </main>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<x-footer />

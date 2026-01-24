<x-header :meta="array('title'=> getSetting('page_faq_contact_meta_title'), 'description'=> getSetting('page_faq_contact_meta_description'))"/>

    <main>
        <!-- banner section start-->
        <section class="ko-banner" style="background-image: url('{{ publicPath('assets/images/cart-banner.webp') }}');">
            <div class="ko-container">
                <div class="ko-banner-content">
                    <h2>Frequently Asked Questions</h2>
                    {!! getSetting('page_faq_contact_description') !!}
                    <nav>
                        <ol class="ko-banner-list">
                            <li><a href="{{ route('view.home') }}">Home</a></li>
                          <li class="active">FAQs</li>
                        </ol>
                      </nav>
                </div>
            </div>
        </section>
        <!-- banner section end-->

        <section class="ko-faq-section">
            <div class="ko-container">
                <h2>Frequently asked questions</h2>
                <p>Question you might ask about our services.</p>
                <div class="ko-faq-accordion">
                    @if (!empty($faqs) && count($faqs) > 0)
                        @foreach ($faqs as $faq)
                            <div class="ko-accordion-item">
                                <div class="ko-accordion-item-header">{{ $faq->question }}</div>
                                <div class="ko-accordion-item-body">
                                    <p class="ko-accordion-item-body-content">{!! $faq->answer !!}</p>
                                </div>
                            </div>
                        @endforeach
                        
                    @else
                        <div class="ko-accordion-item">
                            <p class="ko-accordion-item-body-content">No FAQs to show here</p>
                        </div>
                    @endif
                    
                </div>
            </div>
        </section>   
    </main>
    
<x-footer />
@extends('layout.app')
@section('title', 'Inquiry Sent Successfully - Hardware Store')
@section('content')


<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Success</h1>
                </div>
            </div>
            <div class="col-lg-7">
                </div>
        </div>
    </div>
</div>
<div class="untree_co-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center pt-5">
                <span class="display-3 thankyou-icon text-primary">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope-check mb-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2zm3.708 8.5H1v5h14v-5h-4.708l-.292.292-1.303 1.303a1 1 0 0 1-1.414 0L5.708 10.5z"/>
                        <path d="M15.854 10.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708l1.146 1.147 2.646-2.647a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </span>
                
                <h2 class="display-3 text-black">Thank you!</h2>
                <p class="lead mb-4">Your inquiry has been submitted successfully.</p>
                <p class="text-muted mb-5">
                    Our team will review your request and contact you shortly with the availability and formal quote.
                </p>
                
                <div class="d-flex justify-content-center gap-2">
                    <p>
                        <a href="{{ url('/') }}" class="btn btn-sm btn-black">Back to shop</a>
                    </p>
                    <p>
                        <a href="{{ url('/home') }}" class="btn btn-sm btn-outline-black">Continue Browsing</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

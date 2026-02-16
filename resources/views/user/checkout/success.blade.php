<x-header :meta="array('title' => 'Inquiry Sent Successfully','description' => 'Thank you for your inquiry')" />

<main class="ko-container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">

                    <div class="mb-4">
                        <i class="bi bi-envelope-check-fill text-success" style="font-size: 64px;"></i>
                    </div>

                    <h3 class="fw-bold mb-2">Thank you for your inquiry!</h3>

                    <p class="text-muted mb-4">
                        Your inquiry has been submitted successfully.  
                        Our team will review your request and contact you shortly with the details.
                    </p>

                    <div class="d-flex justify-content-center gap-3">

                        <a href="{{ url('/') }}" class="btn btn-primary">
                            Continue Browsing
                        </a>

                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            Back to Product
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<x-footer />

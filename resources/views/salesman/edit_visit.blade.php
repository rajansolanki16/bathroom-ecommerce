<x-admin.header :title="'Edit Store Visit'" />

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase">Edit Store Visit</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Sales</a></li>
                        <li class="breadcrumb-item active">Edit Visit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10 col-xxl-8">
            <form action="{{ route('salesman.visit.update', $visit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card shadow-lg border-0 overflow-hidden">
                    <div class="card-header bg-primary-subtle py-3 border-bottom border-primary-subtle">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-primary text-white rounded-circle fs-20 shadow-sm">
                                    <i class="ri-store-2-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">Visit Details</h5>
                                <p class="text-muted mb-0 small">Update visit information</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-white text-primary border border-primary-subtle px-3 py-2 fs-12">
                                    <i class="ri-calendar-check-line align-middle me-1"></i> {{ $visit->created_at->format('D, d M Y | h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Select Vendor / Store <span class="text-danger">*</span></label>
                                <select name="vendor_id" class="form-select border-primary-subtle bg-light-subtle @error('vendor_id') is-invalid @enderror">
                                    <option value="">Choose a vendor...</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id', $visit->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }} ({{ $vendor->email }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Purpose of Visit <span class="text-danger">*</span></label>
                                <select name="purpose" class="form-select border-primary-subtle bg-light-subtle @error('purpose') is-invalid @enderror">
                                    <option value="">Choose a purpose...</option>
                                    <option value="new_business" {{ old('purpose', $visit->purpose) == 'new_business' ? 'selected' : '' }}>New Business</option>
                                    <option value="follow_up" {{ old('purpose', $visit->purpose) == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                                    <option value="product_demo" {{ old('purpose', $visit->purpose) == 'product_demo' ? 'selected' : '' }}>Product Demo</option>
                                    <option value="complaint_resolution" {{ old('purpose', $visit->purpose) == 'complaint_resolution' ? 'selected' : '' }}>Complaint Resolution</option>
                                    <option value="other" {{ old('purpose', $visit->purpose) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('purpose')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Store Location / Address </label>
                                <div class="input-group border-primary-subtle shadow-sm rounded">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="ri-map-pin-line text-primary"></i>
                                    </span>
                                    <textarea name="location_address" class="form-control border-0" rows="2" placeholder="Enter shop / store full address or current location...">{{ old('location_address', $visit->location_address) }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">Meeting Summary & Notes</label>
                                <textarea name="notes" class="form-control border-primary-subtle shadow-sm" rows="4" placeholder="Briefly describe the key discussion points...">{{ old('notes', $visit->notes) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Client Feedback</label>
                                <div class="input-group border-primary-subtle shadow-sm rounded">
                                    <span class="input-group-text bg-light border-0"><i class="ri-chat-quote-line text-muted"></i></span>
                                    <input type="text" name="feedback" class="form-control border-0" placeholder="Direct feedback from client..." value="{{ old('feedback', $visit->feedback) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Overall Visit Rating</label>
                                <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded border border-dashed px-3">
                                    @for($i=1; $i<=5; $i++)
                                        <div class="form-check form-check-inline me-0">
                                        <input class="form-check-input" type="radio" name="rating" id="rate{{$i}}" value="{{$i}}" {{ old('rating', $visit->rating) == $i ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="rate{{$i}}">{{$i}} <i class="ri-star-fill text-warning fs-13"></i></label>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="bg-light p-4 rounded shadow-sm border">
                                <label class="form-label fw-bold d-block mb-3 text-uppercase fs-11 tracking-wider text-muted">Session Outcome  <span class="text-danger">*</span></label>
                                <div class="row text-center g-3">
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="outcome" id="pos" value="positive" autocomplete="off" {{ old('outcome', $visit->outcome) == 'positive' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center justify-content-center" for="pos">
                                            <i class="ri-emotion-happy-line fs-24 mb-1"></i>
                                            <span class="fw-bold">Positive</span>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="outcome" id="neu" value="neutral" autocomplete="off" {{ old('outcome', $visit->outcome) == 'neutral' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center justify-content-center" for="neu">
                                            <i class="ri-emotion-normal-line fs-24 mb-1"></i>
                                            <span class="fw-bold">Neutral</span>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="outcome" id="neg" value="negative" autocomplete="off" {{ old('outcome', $visit->outcome) == 'negative' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger w-100 py-3 d-flex flex-column align-items-center justify-content-center" for="neg">
                                            <i class="ri-emotion-unhappy-line fs-24 mb-1"></i>
                                            <span class="fw-bold">Negative</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="p-3 w-100 rounded border border-primary-subtle bg-primary-subtle bg-opacity-10">
                                <div class="form-check form-switch form-switch-lg mb-0">
                                    <input class="form-check-input" type="checkbox" id="followUpSwitch" name="follow_up_required" {{ old('follow_up_required', $visit->follow_up_required) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-primary ms-2" for="followUpSwitch">Require Follow-up?</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" id="followUpDateField" style="display:{{ old('follow_up_required', $visit->follow_up_required) ? 'block' : 'none' }};">
                            <div class="p-3 rounded border bg-white shadow-sm">
                                <label class="form-label fw-bold text-dark">Schedule Next Visit</label>
                                <input type="date" name="next_follow_up_date" class="form-control border-primary shadow-sm" min="{{ date('Y-m-d') }}" value="{{ old('next_follow_up_date', $visit->next_follow_up_date) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light p-3 border-top d-flex justify-content-between">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-danger fw-medium">
                        <i class="ri-close-line align-middle me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-5 shadow">
                        Update Visit Report <i class="ri-save-line align-middle ms-1"></i>
                    </button>
                </div>
        </div>
        </form>
    </div>
</div>
</div>

<script>
    // Logic to toggle Follow-up Date with a smooth transition
    document.getElementById('followUpSwitch').addEventListener('change', function() {
        const field = document.getElementById('followUpDateField');
        if (this.checked) {
            field.style.display = 'block';
            field.classList.add('animate__animated', 'animate__fadeIn');
        } else {
            field.style.display = 'none';
        }
    });
</script>

<x-admin.footer />
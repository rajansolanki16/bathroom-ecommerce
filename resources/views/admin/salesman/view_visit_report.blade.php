<x-admin.header :title="'Store Visit Report Details'" />
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0">

                {{-- Header --}}
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        Store Visit Report #{{ $storeVisit->id }}
                    </h5>

                    <span class="text-muted small">
                        {{ $storeVisit->created_at->format('d M Y H:i') }}
                    </span>
                </div>
                <div class="card-body">

                    {{-- Basic Info Grid --}}
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="text-muted small">Salesman</label>
                            <div class="fw-semibold">
                                {{ $storeVisit->salesman->name ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Vendor</label>
                            <div class="fw-semibold">
                                {{ $storeVisit->vendor->name ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Purpose</label>
                            <div>{{ $storeVisit->purpose }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Location</label>
                            <div>{{ $storeVisit->location_address ?? '-' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Rating</label>
                            <div class="fw-semibold">
                                ⭐ {{ $storeVisit->rating ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Outcome</label>
                            @php
                                $outcome = strtolower($storeVisit->outcome);
                                $badgeClass = 'bg-secondary';
                                if ($outcome === 'positive') $badgeClass = 'bg-success';
                                elseif ($outcome === 'negative') $badgeClass = 'bg-danger';
                                elseif ($outcome === 'neutral') $badgeClass = 'bg-warning text-dark';
                            @endphp
                            <div>
                                <span class="badge rounded-pill {{ $badgeClass }}">
                                    {{ ucfirst($storeVisit->outcome) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Follow Up Required</label>
                            <div>
                                <span class="badge rounded-pill bg-{{ $storeVisit->follow_up_required ? 'success' : 'secondary' }}">
                                    {{ $storeVisit->follow_up_required ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Next Follow Up</label>
                            <div>
                                {{ $storeVisit->next_follow_up_date ?? '-' }}
                            </div>
                        </div>

                        {{-- Notes Section --}}
                        <div class="col-12">
                            <hr>
                            <label class="text-muted small">Notes</label>
                            <div class="p-3 bg-light rounded">
                                {{ $storeVisit->notes ?? 'No notes provided.' }}
                            </div>
                        </div>

                        {{-- Feedback Section --}}
                        <div class="col-12">
                            <label class="text-muted small">Feedback</label>
                            <div class="p-3 bg-light rounded">
                                {{ $storeVisit->feedback ?? 'No feedback provided.' }}
                            </div>
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">

                        <form action="{{ route('visit_report.reject', $storeVisit->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger px-4">
                                <i class="bi bi-x-circle me-1"></i> Reject
                            </button>
                        </form>

                        <form action="{{ route('visit_report.approve', $storeVisit->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-circle me-1"></i> Approve
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
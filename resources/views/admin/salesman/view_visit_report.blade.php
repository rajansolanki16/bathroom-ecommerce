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

                    {{-- STATUS BADGE --}}
                    <div class="mb-3">
                        @if(is_null($storeVisit->is_approve))
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($storeVisit->is_approve == 1)
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </div>

                    {{-- REJECT REASON --}}
                    @if($storeVisit->is_approve === 0 && $storeVisit->reject_reason)
                        <div class="alert alert-danger">
                            <strong>Reject Reason:</strong><br>
                            {{ $storeVisit->reject_reason }}
                        </div>
                    @endif

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
                                <span class="badge bg-{{ $storeVisit->follow_up_required ? 'success' : 'secondary' }}">
                                    {{ $storeVisit->follow_up_required ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Next Follow Up</label>
                            <div>
                                {{ $storeVisit->next_follow_up_date?->format('d M Y') ?? '-' }}
                            </div>
                        </div>

                        <div class="col-12">
                            <hr>
                            <label class="text-muted small">Notes</label>
                            <div class="p-3 bg-light rounded">
                                {{ $storeVisit->notes ?? 'No notes provided.' }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small">Feedback</label>
                            <div class="p-3 bg-light rounded">
                                {{ $storeVisit->feedback ?? 'No feedback provided.' }}
                            </div>
                        </div>

                    </div>

                    {{-- ACTION BUTTONS --}}
                    @if(is_null($storeVisit->is_approve))
                        <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">

                            {{-- REJECT BUTTON (OPEN MODAL) --}}
                            <button class="btn btn-outline-danger px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i> Reject
                            </button>

                            {{-- APPROVE BUTTON --}}
                            <form action="{{ route('visit_report.approve', $storeVisit->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-check-circle me-1"></i> Approve
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- REJECT MODAL --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('visit_report.reject', $storeVisit->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Visit Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">
                        Reject Reason <span class="text-danger">*</span>
                    </label>

                    <textarea name="reject_reason"
                        class="form-control"
                        rows="4"
                        placeholder="Enter reject reason..."
                        required></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                        class="btn btn-danger">
                        Confirm Reject
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<x-admin.footer />
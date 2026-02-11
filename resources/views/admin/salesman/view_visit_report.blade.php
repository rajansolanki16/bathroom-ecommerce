<x-admin.header :title="'Store Visit Report Details'" />
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Store Visit Report #{{ $storeVisit->id }}</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped align-middle">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Salesman</th>
                                <td>{{ $storeVisit->salesman->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Vendor</th>
                                <td>{{ $storeVisit->vendor->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Purpose</th>
                                <td>{{ $storeVisit->purpose }}</td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td>{{ $storeVisit->notes }}</td>
                            </tr>
                            <tr>
                                <th>Feedback</th>
                                <td>{{ $storeVisit->feedback }}</td>
                            </tr>
                            <tr>
                                <th>Rating</th>
                                <td>{{ $storeVisit->rating }}</td>
                            </tr>
                            <tr>
                                <th>Outcome</th>
                                <td>
                                    @php
                                    $outcome = strtolower($storeVisit->outcome);
                                    $badgeClass = 'bg-secondary';
                                    if ($outcome === 'positive') $badgeClass = 'bg-success';
                                    elseif ($outcome === 'negative') $badgeClass = 'bg-danger';
                                    elseif ($outcome === 'neutral') $badgeClass = 'bg-warning text-dark';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($storeVisit->outcome) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Follow Up Required</th>
                                <td>
                                    <span class="badge bg-{{ $storeVisit->follow_up_required ? 'success' : 'secondary' }}">
                                        {{ $storeVisit->follow_up_required ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Next Follow Up</th>
                                <td>{{ $storeVisit->next_follow_up_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $storeVisit->location_address }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ $storeVisit->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex gap-3 justify-content-end mt-4">
                        <form action="{{ route('visit_report.approve', $storeVisit->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <form action="{{ route('visit_report.reject', $storeVisit->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
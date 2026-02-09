<x-admin.header :title="'Dashboard'" />

@role('admin')
<div class="row">
    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-primary-subtle text-primary fs-3xl rounded p-3">
                        <i class="ri-shopping-cart-line"></i>
                    </div>
                </div>
                <h4>{{ $totalOrders }}</h4>
                <p class="text-muted mb-4">Total Orders</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-secondary-subtle text-secondary fs-3xl rounded p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 1v22m8.56-9h-16.12a4 4 0 0 0-3.98 3.9v2.16a4 4 0 0 0 3.98 3.9h16.12a4 4 0 0 0 3.98-3.9v-2.16a4 4 0 0 0-3.98-3.9z"></path>
                            <circle cx="12" cy="16" r="2.5"></circle>
                        </svg>
                    </div>
                </div>
                <h4>₹<span>{{ number_format($totalRevenue, 2) }}</span></h4>
                <p class="text-muted mb-4">Total Revenue</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-secondary" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-info-subtle text-info fs-3xl rounded p-3">
                        <i class="ri-product-hunt-line"></i>
                    </div>
                </div>
                <h4>{{ $totalProducts }}</h4>
                <p class="text-muted mb-4">Total Products</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-info" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-warning-subtle text-warning fs-3xl rounded p-3">
                        <i class="ri-bookmark-line"></i>
                    </div>
                </div>
                <h4>{{ $totalBrands }}</h4>
                <p class="text-muted mb-4">Total Brands</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-warning" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Recent Orders</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="fw-medium">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name ?? $order->name }}</td>
                                <td>₹{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status->value === '1' ? 'success' : ($order->status->value === '0' ? 'warning' : 'danger') }}">
                                        {{ $order->status->label() }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Top Brands</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Brand Name</th>
                                <th scope="col">Products</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topBrands as $brand)
                            <tr>
                                <td>
                                    <a href="{{ route('brands.show', $brand->id) }}" class="fw-medium">
                                        {{ $brand->name }}
                                    </a>
                                </td>
                                <td>{{ $brand->products_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $brand->is_active ? 'success' : 'danger' }}">
                                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No brands available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endrole

{{-- Salesman Dashboard Section --}}
@role('salesman')
<div class="row">
    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-success-subtle text-success fs-3xl rounded p-3">
                        <i class="ri-store-2-line"></i>
                    </div>
                </div>
                <h4>{{ $totalVisits }}</h4>
                <p class="text-muted mb-4">Total Visits</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-success" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-info-subtle text-info fs-3xl rounded p-3">
                        <i class="ri-calendar-check-line"></i>
                    </div>
                </div>
                <h4>{{ $todayVisits }}</h4>
                <p class="text-muted mb-4">Today's Visits</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-info" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Recent Store Visits</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Salesman</th>
                                <th>Outcome</th>
                                <th>Purpose</th>
                                <th>Notes</th>
                                <th>Feedback</th>
                                <th>Follow-up Required</th>
                                <th>Next Follow-up Date</th>
                                <th>Rating</th>
                                <th>Location</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentVisits as $visit)
                            <tr>
                                <td>{{ $visit->vendor->name ?? '-' }}</td>
                                <td>{{ $visit->salesman->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $visit->outcome === 'positive' ? 'success' :
                                        ($visit->outcome === 'neutral' ? 'warning' : 'danger')
                                    }}">
                                        {{ ucfirst($visit->outcome) }}
                                    </span>
                                </td>
                                <td>{{ $visit->purpose ?? '-' }}</td>
                                <td>{{ Str::limit($visit->notes ?? '-', 30) }}</td>
                                <td>{{ Str::limit($visit->feedback ?? '-', 30) }}</td>
                                <td>
                                    @if($visit->follow_up_required)
                                    <span class="badge bg-warning">Yes</span>
                                    @else
                                    <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>{{ $visit->next_follow_up_date ? \Carbon\Carbon::parse($visit->next_follow_up_date)->format('M d, Y') : '-' }}</td>
                                <td>
                                    @if($visit->rating)
                                    <span class="badge bg-info">{{ $visit->rating }}/5</span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ Str::limit($visit->location_address ?? '-', 20) }}</td>
                                <td>{{ $visit->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('salesman.visit.edit', $visit->id) }}" class="btn btn-sm">
                                        <i class="ri-edit-line"></i> Edit
                                    </a> 
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">
                                    No visits recorded
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endrole


<x-admin.footer />
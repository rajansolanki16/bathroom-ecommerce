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
                    <p class="text-muted mb-4">Total Inquiries</p>
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
                        <div class="avatar-title bg-success-subtle text-success fs-3xl rounded p-3">
                            <i class="bi bi-shop"></i>
                        </div>
                    </div>

                    <h4>
                        <span>{{ $totalVendors }}</span>
                    </h4>

                    <p class="text-muted mb-4">Total Users</p>
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
                    <h4 class="card-title mb-0">Recent Inquiries</h4>
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
                                            <span
                                                class="badge bg-{{ $order->status->value === '1' ? 'success' : ($order->status->value === '0' ? 'warning' : 'danger') }}">
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
                                            <span class="badge bg-{{ $brand->show_on_home ? 'success' : 'danger' }}">
                                                {{ $brand->show_on_home ? 'Active' : 'Inactive' }}
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

                {{-- Filter Section --}}
                <div class="card-body border-bottom bg-light">
                    <form action="{{ route('admin.dashboard') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Vendor</label>
                                <select name="vendor_id" class="form-select">
                                    <option value="">All Vendors</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="form-control"
                                    value="{{ request('from_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="form-control"
                                    value="{{ request('to_date') }}">
                            </div>

                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-search-line"></i> Filter
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Outcome</th>
                                    <th>Purpose</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentVisits as $visit)
                                    <tr>
                                        <td>{{ $visit->vendor->name ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $visit->outcome === 'positive' ? 'success' : ($visit->outcome === 'neutral' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($visit->outcome) }}
                                            </span>
                                        </td>
                                        @php
                                            $purposes = [
                                                'new_business' => 'New Business',
                                                'follow_up' => 'Follow-up',
                                                'product_demo' => 'Product Demo',
                                                'complaint_resolution' => 'Complaint Resolution',
                                                'other' => 'Other',
                                            ];
                                        @endphp

                                        <td>{{ $purposes[$visit->purpose] ?? '-' }}</td>
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

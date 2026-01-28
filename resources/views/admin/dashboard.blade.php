<x-admin.header :title="'Dashboard'" />

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
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22m8.56-9h-16.12a4 4 0 0 0-3.98 3.9v2.16a4 4 0 0 0 3.98 3.9h16.12a4 4 0 0 0 3.98-3.9v-2.16a4 4 0 0 0-3.98-3.9z"></path><circle cx="12" cy="16" r="2.5"></circle></svg>
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
                    <div class="avatar-title bg-danger-subtle text-danger fs-3xl rounded p-3">
                        <i class="ri-calendar-line"></i>
                    </div>
                </div>
                <h4>{{ $monthlyOrders }}</h4>
                <p class="text-muted mb-4">This Month Orders</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-danger" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-success-subtle text-success fs-3xl rounded p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22m8.56-9h-16.12a4 4 0 0 0-3.98 3.9v2.16a4 4 0 0 0 3.98 3.9h16.12a4 4 0 0 0 3.98-3.9v-2.16a4 4 0 0 0-3.98-3.9z"></path><circle cx="12" cy="16" r="2.5"></circle></svg>
                    </div>
                </div>
                <h4>₹<span>{{ number_format($monthlyRevenue, 2) }}</span></h4>
                <p class="text-muted mb-4">This Month Revenue</p>
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

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Low Stock Products</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Product Name</th>
                                <th scope="col">SKU</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <a href="{{ route('products.show', $product->id) }}" class="fw-medium">
                                            {{ Str::limit($product->product_title, 20) }}
                                        </a>
                                    </td>
                                    <td>{{ $product->sku_number }}</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $product->stock }} units
                                        </span>
                                    </td>
                                    <td>₹{{ number_format($product->sell_price, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">All products have sufficient stock</td>
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
                <h4 class="card-title mb-0">Orders by Status</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Status</th>
                                <th scope="col">Count</th>
                                <th scope="col">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalOrdersCount = $ordersByStatus->sum('count');
                            @endphp
                            @forelse($ordersByStatus as $statusData)
                                @php
                                    $percentage = $totalOrdersCount > 0 ? round(($statusData->count / $totalOrdersCount) * 100) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $statusData->status->value === '1' ? 'success' : ($statusData->status->value === '0' ? 'warning' : 'danger') }}">
                                            {{ $statusData->status->label() }}
                                        </span>
                                    </td>
                                    <td class="fw-medium">{{ $statusData->count }}</td>
                                    <td>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $percentage }}%</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No orders data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />
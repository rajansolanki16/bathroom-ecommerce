<x-admin.header :title="'order Details'" />
<div class="col-xl-12">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-nowrap">
            <h4 class="mb-0 card-title">User Order list</h4>
        </div>
        <div class="card-body">
            <p class="text-muted"> this is the list of all User Order. </p>

            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download me-1"></i> Export
                </button>

                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('orders.export', 'csv') }}">
                            <i class="bi bi-filetype-csv me-2"></i>Export CSV
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('orders.export', 'excel') }}">
                            <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                        </a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <form method="GET" action="{{ route('orders.show') }}">
                    <div class="row g-3 align-items-end">

                        <div class="col-xxl">
                            <div class="search-box">
                                <input type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control"
                                    placeholder="Search products and User...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl col-sm-6">
                            <select
                                class="form-control"
                                name="status"
                                data-choices
                                data-choices-search="true">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Date range -->
                        <div class="col-xxl col-sm-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>
                        <div class="col-xxl col-sm-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>

                        <div class="col-xxl-auto col-sm-6">
                            <button class="btn btn-primary w-md">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>

                        <div class="col-xxl-auto col-sm-6">
                            <a href="{{ route('orders.show') }}" class="btn btn-light w-md">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table id="fixed-header" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">product Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Total</th>
                            <th scope="col">status</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? $order->name }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->email }}</td>

                            <td>
                                @if($order->items->count())
                                @foreach($order->items as $item)
                                {{ $item->product?->product_title ?? 'N/A' }}
                                @endforeach
                                @else
                                N/A
                                @endif
                            </td>

                            <td>{{ $order->phone }}</td>
                            <td>{{ $order->total }}</td>

                            <td>
                                <select class="form-select order-status" data-id="{{ $order->id }}">
                                    @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}"
                                        {{ optional($order->status)->value === $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
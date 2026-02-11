<x-admin.header :title="'store visit report'" />
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Store Visit Reports</h4>
                </div>


                {{-- Filter Section --}}
                <div class="card-body border-bottom bg-light">
                    <form action="{{ route('store_visits.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Salesman</label>
                                <select name="salesman_id" class="form-select">
                                    <option value="">All Salesmen</option>
                                    @foreach($salesmen as $salesman)
                                    <option value="{{ $salesman->id }}" {{ request('salesman_id') == $salesman->id ? 'selected' : '' }}>
                                        {{ $salesman->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Vendor</label>
                                <select name="vendor_id" class="form-select">
                                    <option value="">All Vendors</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>

                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-search-line"></i> Filter
                                </button>
                                <a href="{{ route('store_visits.index') }}" class="btn btn-secondary">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                    {{-- Export Button --}}
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success  dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download"></i> Export
                        </button>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('visit_report.export.excel', request()->query()) }}">
                                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel (filtered)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('visit_report.export.pdf', request()->query()) }}">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF (filtered)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Salesman</th>
                                <th>Vendor</th>
                                <th>Purpose</th>
                                <th>Outcome</th>
                                <th>Date</th>
                                <th>View</th>
                            </tr>
                            @foreach($visits as $visit)
                            <tr>
                                <td>#{{ $visit->id }}</td>
                                <td>{{ $visit->salesman->name ?? '-' }}</td>
                                <td>{{ $visit->vendor->name ?? '-' }}</td>
                                <td>{{ $visit->purpose }}</td>
                                <td>
                                    @php
                                    $outcome = strtolower($visit->outcome);
                                    $badgeClass = 'bg-secondary';
                                    if ($outcome === 'positive') $badgeClass = 'bg-success';
                                    elseif ($outcome === 'negative') $badgeClass = 'bg-danger';
                                    elseif ($outcome === 'neutral') $badgeClass = 'bg-warning text-dark';
                                    @endphp
                                    <span class="badge rounded-pill {{ $badgeClass }}">{{ ucfirst($visit->outcome) }}</span>
                                </td>
                                <td>{{ $visit->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-subtle-secondary btn-icon" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('visit_report.view', $visit->id) }}" class="dropdown-item">
                                                    <i class="bi bi-eye me-1"></i> View
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $visits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
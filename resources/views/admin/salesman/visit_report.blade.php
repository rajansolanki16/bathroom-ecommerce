<x-admin.header :title="'Store Visit Report'" />
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                {{-- Card Header --}}
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Store Visit Reports</h5>

                    {{-- Export Dropdown --}}
                    <div class="dropdown">
                        <button type="button"
                                class="btn btn-success btn-sm dropdown-toggle"
                                data-bs-toggle="dropdown">
                            <i class="bi bi-download me-1"></i> Export
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('visit_report.export.excel', request()->query()) }}">
                                    <i class="bi bi-file-earmark-excel me-2 text-success"></i>
                                    Export Excel (Filtered)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('visit_report.export.pdf', request()->query()) }}">
                                    <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                                    Export PDF (Filtered)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Filter Section --}}
                <div class="card-body border-bottom bg-light">
                    <form action="{{ route('store_visits.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Salesman</label>
                                <select name="salesman_id" class="form-select">
                                    <option value="">All Salesmen</option>
                                    @foreach($salesmen as $salesman)
                                        <option value="{{ $salesman->id }}"
                                            {{ request('salesman_id') == $salesman->id ? 'selected' : '' }}>
                                            {{ $salesman->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Vendor</label>
                                <select name="vendor_id" class="form-select">
                                    <option value="">All Vendors</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">From Date</label>
                                <input type="date"
                                       name="from_date"
                                       class="form-control"
                                       value="{{ request('from_date') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">To Date</label>
                                <input type="date"
                                       name="to_date"
                                       class="form-control"
                                       value="{{ request('to_date') }}">
                            </div>

                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-search-line me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Table Section --}}
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Index</th>
                                <th>Salesman</th>
                                <th>Vendor</th>
                                <th>Purpose</th>
                                <th>Outcome</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($visits as $visit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
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

                                        <span class="badge rounded-pill {{ $badgeClass }}">
                                            {{ ucfirst($visit->outcome) }}
                                        </span>
                                    </td>

                                    <td>{{ $visit->created_at->format('d M Y') }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('visit_report.view', $visit->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No visit reports found.
                                    </td>
                                </tr>
                            @endforelse
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
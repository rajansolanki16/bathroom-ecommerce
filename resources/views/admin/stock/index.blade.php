<x-admin.header :title="'stock'" />

<style>
    /* Same fix used in Users */
    .table-responsive {
        overflow: visible !important;
    }
</style>

<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Stocks</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Stocks</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <!-- Header Actions -->
                    <div class="row g-4 mb-3">
                        <div class="col-sm-auto">
                            <a href="{{ route('stocks.create') }}" class="btn btn-success add-btn">
                                <i class="ri-add-line align-bottom me-1"></i> Add
                            </a>
                        </div>

                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end gap-2 flex-wrap">
                                <!-- Search Box -->
                                <div class="search-box">
                                    <input type="text" class="form-control" id="searchStocks" placeholder="Search...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>

                                <!-- Date Filter Form -->
                                <form method="GET" action="{{ route('stocks.index') }}" class="d-flex gap-2">
                                    <input type="date" class="form-control" name="from_date" 
                                           value="{{ request('from_date') }}" placeholder="From">
                                    
                                    <input type="date" class="form-control" name="to_date" 
                                           value="{{ request('to_date') }}" placeholder="To">
                                    
                                    <button type="submit" class="btn btn-outline-info">
                                        <i class="ri-filter-line"></i> Filter
                                    </button>
                                    
                                    <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">
                                        <i class="ri-refresh-line"></i> Reset
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        <table class="table align-middle table-nowrap" id="stockTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Notes</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($stocks as $stock)
                                <tr>
                                    <td>#{{ $stock->id }}</td>
                                    <td>{{ $stock->product->product_title }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $stock->quantity }}</span>
                                    </td>
                                    <td>{{ $stock->notes ?? '-' }}</td>
                                    <td>{{ $stock->created_at->format('d M Y') }}</td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-subtle-secondary btn-icon"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item text-danger"
                                                        data-delete-url="{{ route('stocks.destroy', $stock->id) }}"
                                                        onclick="setDeleteFormAction(this)"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal">
                                                        <i class="ph-trash me-1"></i> Remove
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No stocks found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="noresult" style="display:none">
                            <div class="text-center py-4">
                                <h5>No results found</h5>
                                <p class="text-muted mb-0">Try a different search</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination (JS same as Brands) -->
                    <div class="d-flex justify-content-end" id="paginationWrapper">
                        <div class="gap-2 pagination-wrap hstack">
                            <a href="#" class="page-item pagination-prev disabled" id="prevStockPage">Previous</a>
                            <ul class="mb-0 pagination" id="stockPagination"></ul>
                            <a href="#" class="page-item pagination-next" id="nextStockPage">Next</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.delete-modal')

<script>
    setupPaginatedTable({
        searchInputId: "searchStocks",
        tableId: "stockTable",
        paginationWrapperId: "paginationWrapper",
        paginationListId: "stockPagination",
        prevBtnId: "prevStockPage",
        nextBtnId: "nextStockPage",
        noResultClass: "noresult"
    });
</script>

<x-admin.footer />
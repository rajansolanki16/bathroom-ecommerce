<x-admin.header :title="'Stocks'" />

<style>
    .table-responsive {
        overflow: visible !important;
    }
</style>

<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-sm-0">Stocks</h4>
                    <p class="text-muted mb-0">Manage product stock entries</p>
                </div>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Products</a>
                        </li>
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
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <!-- Header Actions -->
                    <div class="row mb-4 align-items-center">

                        <!-- Add Button -->
                        <div class="col-md-auto mb-2 mb-md-0">
                            <a href="{{ route('stocks.create') }}" class="btn btn-primary">
                                <i class="ri-add-line align-bottom me-1"></i> Add Stock
                            </a>
                        </div>

                        <!-- Filters -->
                        <div class="col">
                            <form method="GET" action="{{ route('stocks.index') }}"
                                class="row g-2 justify-content-end align-items-center">

                                <!-- Search -->
                                <div class="col-md-4">
                                    <div class="search-box">
                                        <input type="text" class="form-control" id="searchStocks"
                                            placeholder="Search product...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <!-- From Date -->
                                <div class="col-md-3">
                                    <input type="date" class="form-control" name="from_date"
                                        value="{{ request('from_date') }}">
                                </div>

                                <!-- To Date -->
                                <div class="col-md-3">
                                    <input type="date" class="form-control" name="to_date"
                                        value="{{ request('to_date') }}">
                                </div>

                                <!-- Buttons -->
                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-outline-info w-100">
                                        <i class="ri-filter-line"></i>
                                    </button>

                                    <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="ri-refresh-line"></i>
                                    </a>
                                </div>

                            </form>
                        </div>

                    </div>
                    <br>    

                    <!-- Table -->
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap" id="stockTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Index</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Notes</th>
                                    <th>Date</th>
                                    <th width="80">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($stocks as $stock)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td class="fw-medium">
                                            {{ $stock->product->product_title }}
                                        </td>

                                        <td>
                                            <span class="badge bg-soft-primary text-primary">
                                                {{ $stock->quantity }}
                                            </span>
                                        </td>

                                        <td class="text-muted">
                                            {{ $stock->notes ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $stock->created_at->format('d M Y') }}
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-subtle-secondary btn-icon"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item text-danger"
                                                            data-delete-url="{{ route('stocks.destroy', $stock->id) }}"
                                                            onclick="setDeleteFormAction(this)" data-bs-toggle="modal"
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
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ri-inbox-line fs-24 d-block mb-2"></i>
                                                No stocks found
                                            </div>
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
                    </div><br>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3" id="paginationWrapper">
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

<livewire:modals.delete-record />

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
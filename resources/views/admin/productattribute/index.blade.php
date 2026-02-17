<x-admin.header :title="'Product Attributes'" />
<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product Attributes</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Attributes</li>
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
                            <a href="{{ route('product_attributes.create') }}"
                               class="btn btn-success add-btn">
                                <i class="ri-add-line align-bottom me-1"></i> Add
                            </a>
                        </div>

                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end">
                                <div class="search-box ms-2">
                                    <input type="text"
                                           class="form-control"
                                           id="searchAttributes"
                                           placeholder="Search...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        <table class="table align-middle table-nowrap"
                               id="attributeTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($attributes as $attribute)
                                    <tr>
                                        <td>#{{ $attribute->id }}</td>
                                        <td>{{ $attribute->name }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $attribute->slug }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-subtle-secondary btn-icon"
                                                        data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('product_attributes.edit', $attribute->id) }}"
                                                           class="dropdown-item">
                                                            <i class="ph-pencil me-1"></i> Edit
                                                        </a>
                                                    </li>

                                                    <li><hr class="dropdown-divider"></li>

                                                    <li>
                                                        <a href="javascript:void(0);"
                                                           class="dropdown-item text-danger"
                                                           data-delete-url="{{ route('product_attributes.destroy', $attribute->id) }}"
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
                                        <td colspan="4"
                                            class="text-center py-4 text-muted">
                                            No product attributes found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="noresult" style="display:none">
                            <div class="text-center py-4">
                                <h5>No results found</h5>
                                <p class="text-muted mb-0">
                                    Try a different search
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end" id="paginationWrapper">
                        <div class="gap-2 pagination-wrap hstack">
                            <a href="#"
                               class="page-item pagination-prev disabled"
                               id="prevAttributePage">
                                Previous
                            </a>

                            <ul class="mb-0 pagination"
                                id="attributePagination"></ul>

                            <a href="#"
                               class="page-item pagination-next"
                               id="nextAttributePage">
                                Next
                            </a>
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
        searchInputId: "searchAttributes",
        tableId: "attributeTable",
        paginationWrapperId: "paginationWrapper",
        paginationListId: "attributePagination",
        prevBtnId: "prevAttributePage",
        nextBtnId: "nextAttributePage",
        noResultClass: "noresult"
    });
</script>

<x-admin.footer />
    
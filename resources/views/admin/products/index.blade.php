<x-admin.header :title="'Products'" />

<style>
    /* Fix dropdown scrollbar issue */
    .table-responsive {
        overflow: visible !important;
    }
</style>

<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Products</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Ecommerce</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <div class="search-box">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search products...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <select class="form-control" name="category">
                            <option value="">All Categories</option>
                            @foreach ($categories as $parent)
                                <optgroup label="{{ $parent->name }}">
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @foreach ($parent->children as $child)
                                        <option value="{{ $child->id }}">— {{ $child->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-auto">
                        <button class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                    </div>

                    <div class="col-md-auto">
                        <a href="{{ route('products.index') }}" class="btn btn-light">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Product Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <!-- Header Actions -->
                    <div class="row g-4 mb-3">
                        <div class="col-sm-auto">
                            <a href="{{ route('products.create') }}" class="btn btn-success add-btn">
                                <i class="ri-add-line align-bottom me-1"></i> Add
                            </a>

                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="ri-upload-2-line align-bottom me-1"></i> Import Excel
                            </button>
                        </div>



                        <div class="col-sm">
                            <div class="text-sm-end text-muted">
                                Total Products: <strong>{{ $products->total() }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        <table class="table align-middle table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Rating</th>
                                    <th>Published</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-light rounded p-1 me-2">
                                                    <img src="{{ $product->getFirstMediaUrl('main_image') ?: asset('admin/images/new-document.png') }}"
                                                        class="img-fluid" alt="">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('products.edit', $product->id) }}"
                                                            class="text-reset">
                                                            {{ $product->product_title }}
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            {{ $product->categories->pluck('name')->join(', ') ?: '-' }}
                                        </td>

                                        <td>{{ $product->stock ?? 0 }}</td>

                                        <td>₹{{ number_format($product->price, 2) }}</td>

                                        <td>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="bi bi-star-fill"></i> 4.5
                                            </span>
                                        </td>

                                        <td>{{ $product->created_at->format('d M, Y') }}</td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-subtle-secondary btn-icon"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('products.show', $product->id) }}">
                                                            <i class="ph-eye me-1"></i> View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('products.edit', $product->id) }}">
                                                            <i class="ph-pencil me-1"></i> Edit
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item text-danger"
                                                            data-delete-url="{{ route('products.destroy', $product->id) }}"
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
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            No products found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row align-items-center mt-3">
                        <div class="col-sm">
                            <p class="text-muted mb-0">
                                Showing
                                <span class="fw-semibold">{{ $products->firstItem() ?? 0 }}</span>
                                to
                                <span class="fw-semibold">{{ $products->lastItem() ?? 0 }}</span>
                                of
                                <span class="fw-semibold">{{ $products->total() }}</span>
                                results
                            </p>
                        </div>
                        <div class="col-sm-auto">
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Choose Excel File (.xlsx, .csv)</label>
                        <input type="file" name="import_file" class="form-control" required>
                    </div>
                    <div class="alert alert-info">
                        <small>Headers should be: <strong>title, type, short_description,
                                description, price, discount</strong></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('partials.delete-modal')

<x-admin.footer />

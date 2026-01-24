<x-admin.header :title="'Product'" />
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Products</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                                <li class="breadcrumb-item active">Products</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div id="productList">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('products.index') }}">
                                    <div class="row g-3 align-items-end">

                                        <div class="col-xxl">
                                            <div class="search-box">
                                                <input type="text"
                                                    name="search"
                                                    value="{{ request('search') }}"
                                                    class="form-control"
                                                    placeholder="Search products...">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>

                                        <div class="col-xxl col-sm-6">
                                            <select
                                                class="form-control"
                                                name="category"
                                                data-choices
                                                data-choices-search="true"
                                            >
                                                <option value="">All Categories</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xxl-auto col-sm-6">
                                            <button class="btn btn-primary w-md">
                                                <i class="bi bi-funnel me-1"></i> Filter
                                            </button>
                                        </div>

                                        <div class="col-xxl-auto col-sm-6">
                                            <a href="{{ route('products.index') }}" class="btn btn-light w-md">
                                                Reset
                                            </a>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">Products <span class="badge bg-dark-subtle text-dark ms-1">{{ $products->count() }}</span></h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <button class="btn btn-subtle-danger d-none" id="remove-actions" onclick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary add-btn">
                                            <i class="bi bi-plus-circle align-baseline me-1"></i> Add Product
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-centered align-middle table-nowrap mb-0">
                                        <thead class="table-active">
                                            <tr>
                                                <th>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="option" id="checkAll">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </th>
                                                <th class="sort cursor-pointer" data-sort="products">Products</th>
                                                <th class="sort cursor-pointer" data-sort="category">Category</th>
                                                <th class="sort cursor-pointer" data-sort="stock">Stock</th>
                                                <th class="sort cursor-pointer" data-sort="price">Price</th>
                                                <th class="sort cursor-pointer" data-sort="orders">Orders</th>
                                                <th class="sort cursor-pointer" data-sort="rating">Rating</th>
                                                <th class="sort cursor-pointer" data-sort="published">Published</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @forelse ($products as $product)
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="{{ $product->id }}">
                                                        </div>
                                                    </td>

                                                    <td class="products">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs bg-light rounded p-1 me-2">
                                                                <img
                                                                    src="{{ $product->product_image
                                                                        ? asset('storage/'.$product->product_image)
                                                                        : asset('admin/images/no-image.png') }}"
                                                                    class="img-fluid d-block"
                                                                    alt="Product Image">
                                                            </div>

                                                            <div>
                                                                <h6 class="mb-0">
                                                                    <a href="{{ route('products.edit', $product->id) }}" class="text-reset">
                                                                        {{ $product->product_title }}
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="category">
                                                        {{ $product->categories->pluck('name')->join(', ') ?: '-' }}
                                                    </td>

                                                    <td class="stock">
                                                        {{ $product->stock ?? 0 }}
                                                    </td>

                                                    <td class="price">
                                                        ₹{{ number_format($product->price, 2) }}
                                                    </td>

                                                    <td class="orders">
                                                        0
                                                    </td>

                                                    <td class="rating">
                                                        <span class="badge bg-warning-subtle text-warning">
                                                            <i class="bi bi-star-fill"></i> 4.5
                                                        </span>
                                                    </td>

                                                    <td class="published">
                                                        {{ $product->created_at->format('d M, Y') }}
                                                    </td>

                                                    <td>
                                                        <div class="dropdown position-static">
                                                            <button class="btn btn-subtle-secondary btn-sm btn-icon"
                                                                    data-bs-toggle="dropdown">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a class="dropdown-item"  href="{{ route('products.show', $product->id) }}">
                                                                        <i class="ph-eye me-1"></i> View
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('products.edit', $product->id) }}">
                                                                        <i class="ph-pencil me-1"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item text-danger" 
                                                                            type="button" 
                                                                            data-delete-url="{{ route('products.destroy', $product->id) }}"
                                                                            onclick="setDeleteFormAction(this)">
                                                                        <i class="ph-trash me-1"></i> Remove
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted py-4">
                                                        No products found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                    </table>
                                </div><!--end table-responsive-->

                                <div class="noresult" style="display: none">
                                    <div class="text-center py-4">
                                        <div class="avatar-md mx-auto mb-4">
                                            <div class="avatar-title bg-light text-primary rounded-circle fs-4xl">
                                                <i class="bi bi-search"></i>
                                            </div>
                                        </div>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We've searched more than 150+ products We did not find any products for you search.</p>
                                    </div>
                                </div>
                                <!-- end noresult -->

                                <div class="row align-items-center mt-3">
                                    <div class="col-sm">
                                        <p class="text-muted mb-0">
                                            Showing
                                            <span class="fw-semibold">{{ $products->firstItem() }}</span>
                                            to
                                            <span class="fw-semibold">{{ $products->lastItem() }}</span>
                                            of
                                            <span class="fw-semibold">{{ $products->total() }}</span>
                                            results
                                        </p>
                                    </div>

                                    <div class="col-sm-auto">
                                     {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>

                                <!-- end pagination-element -->
                            </div>
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div>
        </div>
        <!-- container-fluid -->
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteRecordModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-md-5">
                    <div class="text-center">
                        <div class="text-danger">
                            <i class="bi bi-trash display-4"></i>
                        </div>
                        <div class="mt-4">
                            <h3 class="mb-2">Are you sure?</h3>
                            <p class="mx-3 mb-0 text-muted fs-lg">
                                Are you sure you want to remove this product <b>permanently</b>?
                            </p>
                        </div>
                    </div>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="gap-2 mt-4 mb-2 d-flex justify-content-center">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">No</button>
                            <button type="submit" class="btn w-sm btn-danger">Yes!</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-admin.footer />

<x-admin.header :title="'Brand Management'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Brands</h4>
        <div class="page-title-right">
            <a href="{{ route('brands.create') }}" class="btn btn-primary add-btn">
                <i class="bi bi-plus-circle align-baseline me-1"></i> Add Brand
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered align-middle table-nowrap mb-0">
                        <thead class="table-active">
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td>#{{ $brand->id }}</td>
                                    <td>
                                        @if($brand->hasMedia('brand_logo'))
                                            <img src="{{ $brand->getFirstMediaUrl('brand_logo') }}"
                                                alt="{{ $brand->name }}"
                                                style="height: 40px; width: 40px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <span class="badge bg-secondary">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $brand->slug }}</td>
                                    <td>
                                        @if($brand->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown position-static">
                                            <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="{{ route('brands.edit', $brand->id) }}" class="dropdown-item edit-item-btn"><i class="align-middle ph-pencil me-1"></i>Edit</a></li>
                                                <li>
                                                    <a class="dropdown-item remove-item-btn" href="javascript:void(0);"
                                                        data-delete-url="{{ route('brands.destroy', $brand->id) }}"
                                                        onclick="setDeleteFormAction(this)"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal">
                                                        <i class="align-middle ph-trash me-1"></i> Remove
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No brands found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="row align-items-center mt-3">
                    <div class="col-sm">
                        <p class="text-muted mb-0">
                            Showing
                            <span class="fw-semibold">{{ $brands->firstItem() ?? 0 }}</span>
                            to
                            <span class="fw-semibold">{{ $brands->lastItem() ?? 0 }}</span>
                            of
                            <span class="fw-semibold">{{ $brands->total() }}</span>
                            results
                        </p>
                    </div>
                    <div class="col-sm-auto">
                        {{ $brands->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            Are you sure you want to remove this brand <b>permanently</b>?
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

<script>
    function setDeleteFormAction(element) {
        const deleteUrl = element.getAttribute('data-delete-url');
        document.getElementById('deleteForm').action = deleteUrl;
    }
</script>

<x-admin.footer />

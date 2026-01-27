<x-admin.header :title="'product category Listings'" />
<!--datatable css-->

<div class="col-xl-12">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-nowrap">
            <h4 class="mb-0 card-title">Category list</h4>

            <a href="{{ route('categories.create') }}" class="btn btn-primary add-btn">
                <i class="align-baseline bi bi-plus-circle me-1"></i>
                Add Category
            </a>
        </div>
        <div class="card-body">
            <p class="text-muted"> this is the list of all product categories </p>
            <div class="table-responsive">
                <table id="fixed-header" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">slug</th>
                            <th scope="col">Is Visible</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($categories as $category)
                        <tr>
                            <td class="fw-medium">{{ $category->id }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" height="50">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>
                                @if($category->parent_id)
                                <span class="badge bg-secondary me-1">Sub</span>
                                @else
                                <span class="badge bg-primary me-1">Main</span>
                                @endif
                                {{ $category->name }}
                            </td>
                            <td class="fw-medium">{{ $category->slug }}</td>
                            <td>
                                @if($category->is_visible)
                                <span class="badge bg-success">Visible</span>
                                @else
                                <span class="badge bg-danger">Hidden</span>
                                @endif
                            </td>


                            <td>
                                <div class="dropdown position-static">
                                    <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="{{ route('categories.edit', $category->id) }}" class="dropdown-item edit-item-btn"><i class="align-middle ph-pencil me-1"></i>Edit</a></li>
                                        <li>
                                            <a class="dropdown-item remove-item-btn" href="javascript:void(0);"
                                                data-delete-url="{{ route('categories.destroy', $category->id) }}"
                                                onclick="setDeleteFormAction(this)">
                                                <i class="align-middle ph-trash me-1"></i> Remove
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>

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
                        <p class="mx-3 mb-0 text-muted fs-lg">Are you sure you want to remove this product category<b>permanently</b>?</p>
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
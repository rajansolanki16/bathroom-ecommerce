<x-admin.header :title="'Blog Listings'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" >
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" >
<div id="productList">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="mb-4 card-title">Blog Listings</h5>
                    </div>
                    <div class="mb-4 search-box">
                        <a href="{{ route('blogs.create') }}" class="btn btn-primary add-btn">
                            <i class="align-baseline bi bi-plus-circle me-1"></i> Add Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="fixed-header" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>TITLE</th>
                                <th>CATEGORIES</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($blog))
                                @foreach ($blog as $b)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::limit($b->title, 80, '...') }}</td>
                                    <td>
                                        @if ($b->categories->isNotEmpty())
                                            @foreach ($b->categories as $category)
                                                <span class="badge bg-primary">{{ $category->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No Categories</span>
                                        @endif
                                    </td>                                    
                                    <td><div class="dropdown position-static">
                                        <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item edit-item-btn"
                                                    href="{{ route('blogs.edit', $b->id) }}"><i
                                                        class="align-middle ph-pencil me-1"></i> Edit</a></li>
                                            <a class="dropdown-item remove-item-btn" href="javascript:void(0);"
                                                data-delete-url="{{ route('blogs.destroy', $b->id) }}"
                                                onclick="setDeleteFormAction(this)">
                                                <i class="align-middle ph-trash me-1"></i> Remove
                                            </a>
                                        </ul>
                                    </div></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
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
                        <p class="mx-3 mb-0 text-muted fs-lg">Are you sure you want to remove this blog category<b>permanently</b>?</p>
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

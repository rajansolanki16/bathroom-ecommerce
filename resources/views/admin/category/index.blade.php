<x-admin.header :title="'Categories'" />

<style>
    /* Same dropdown scrollbar fix */
    .table-responsive {
        overflow: visible !important;
    }
</style>

<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Categories</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Categories</li>
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
                            <a href="{{ route('categories.create') }}" class="btn btn-success add-btn">
                                <i class="ri-add-line align-bottom me-1"></i> Add
                            </a>
                        </div>

                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end">
                                <div class="search-box ms-2">
                                    <input type="text" class="form-control" id="searchCategories"
                                        placeholder="Search...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        <table class="table align-middle table-nowrap" id="categoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Index</th>
                                    {{-- <th>Image</th> --}}
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Visibility</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($categories as $category)
                                    <tr id="row-category-{{ $category->id }}">
                                        <td>{{ $loop->iteration }}</td>

                                        {{-- <td>
                                            @php
                                                $logoUrl = null;
                                                if ($category->media_library_logo_id) {
                                                    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find(
                                                        $category->media_library_logo_id,
                                                    );
                                                    if (
                                                        $media &&
                                                        file_exists(
                                                            storage_path(
                                                                'app/public/' . $media->id . '/' . $media->file_name,
                                                            ),
                                                        )
                                                    ) {
                                                        $logoUrl = asset(
                                                            'storage/' . $media->id . '/' . $media->file_name,
                                                        );
                                                    }
                                                }
                                            @endphp

                                            @if ($logoUrl)
                                                <img src="{{ $logoUrl }}"
                                                    style="width:40px;height:40px;object-fit:cover" class="rounded"
                                                    alt="{{ $category->name }}">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif --}}
                                        </td>

                                        <td>
                                            @if ($category->parent_id)
                                                <span class="badge bg-secondary me-1">Sub</span>
                                            @else
                                                <span class="badge bg-primary me-1">Main</span>
                                            @endif
                                            {{ $category->name }}
                                        </td>

                                        <td>
                                            <span class="badge bg-info">{{ $category->slug }}</span>
                                        </td>

                                        <td>
                                            @if ($category->is_visible)
                                                <span class="badge bg-success">Visible</span>
                                            @else
                                                <span class="badge bg-danger">Hidden</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-subtle-secondary btn-icon"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('categories.edit', $category->id) }}"
                                                            class="dropdown-item">
                                                            <i class="ph-pencil me-1"></i> Edit
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item text-danger"
                                                            onclick="Livewire.dispatch('confirmDelete', { 
                                                                id: {{ $category->id }}, 
                                                                model: 'App\\Models\\Category' 
                                                        })">
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
                                            No categories found
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

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end" id="paginationWrapper">
                        <div class="gap-2 pagination-wrap hstack">
                            <a href="#" class="page-item pagination-prev disabled"
                                id="prevCategoryPage">Previous</a>
                            <ul class="mb-0 pagination" id="categoryPagination"></ul>
                            <a href="#" class="page-item pagination-next" id="nextCategoryPage">Next</a>
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
        searchInputId: "searchCategories",
        tableId: "categoryTable",
        paginationWrapperId: "paginationWrapper",
        paginationListId: "categoryPagination",
        prevBtnId: "prevCategoryPage",
        nextBtnId: "nextCategoryPage",
        noResultClass: "noresult"
    });

    document.addEventListener('livewire:init', () => {

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));

        Livewire.on('open-delete-modal', () => {
            deleteModal.show();
        });

        Livewire.on('close-delete-modal', () => {
            deleteModal.hide();
        });

        Livewire.on('record-deleted', (event) => {

            let data = event[0];

            let modelName = data.model.split('\\').pop().toLowerCase();
            let row = document.getElementById(`row-${modelName}-${data.id}`);

            if (row) {
                row.remove();
            }

        });

    });
</script>

<x-admin.footer />

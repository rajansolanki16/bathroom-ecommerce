<x-admin.header :title="'Brands'" />

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
                <h4 class="mb-sm-0">Brands</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Brands</li>
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
                            <a href="{{ route('brands.create') }}" class="btn btn-success add-btn">
                                <i class="ri-add-line align-bottom me-1"></i> Add
                            </a>
                        </div>

                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end gap-2">
                                <div class="search-box ms-2">
                                    <input type="text" class="form-control" id="searchBrands"
                                        placeholder="Search...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>

                                <a href="{{ request()->has('enabled') ? route('brands.index') : route('brands.index', ['enabled' => 1]) }}"
                                    class="btn btn-outline-info">
                                    {{ request()->has('enabled') ? 'Show All' : 'Show Enabled' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        <table class="table align-middle table-nowrap" id="brandTable">
                            <thead class="table-light">
                                <tr>
                                    {{-- <th>Index</th> --}}
                                     <th>Name</th>
                                    {{-- <th>Banner Image</th> --}}
                                   
                                    <th>Slug</th>
                                    <th>Show on Home</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($brands as $index => $brand)
                                    <tr id="row-brand-{{ $brand->id }}">
                                        {{-- <td>{{ $index + 1 }}</td> --}}
                                          <td>{{ $brand->name }}</td>

                                        {{-- <td>
                                            @php
                                                $logoUrl = null;
                                                if ($brand->media_library_logo_id) {
                                                    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find(
                                                        $brand->media_library_logo_id,
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
                                                <img src="{{ $logoUrl }}" class="rounded"
                                                    style="width:40px;height:40px;object-fit:cover;">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td> --}}

                                      

                                        <td>
                                            <span class="badge bg-info">{{ $brand->slug }}</span>
                                        </td>

                                        <td>
                                            <button
                                                class="btn btn-sm toggle-home-btn
                                                {{ $brand->show_on_home ? 'btn-success' : 'btn-outline-secondary' }}"
                                                data-id="{{ $brand->id }}">
                                                {{ $brand->show_on_home ? 'Enabled' : 'Disabled' }}
                                            </button>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-subtle-secondary btn-icon"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('brands.edit', $brand->id) }}"
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
                                                                id: {{ $brand->id }}, 
                                                                model: 'App\\Models\\Brand' 
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
                                            No brands found
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

                    <!-- Pagination (JS same as Users) -->
                    <div class="d-flex justify-content-end" id="paginationWrapper">
                        <div class="gap-2 pagination-wrap hstack">
                            <a href="#" class="page-item pagination-prev disabled" id="prevBrandPage">Previous</a>
                            <ul class="mb-0 pagination" id="brandPagination"></ul>
                            <a href="#" class="page-item pagination-next" id="nextBrandPage">Next</a>
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
        searchInputId: "searchBrands",
        tableId: "brandTable",
        paginationWrapperId: "paginationWrapper",
        paginationListId: "brandPagination",
        prevBtnId: "prevBrandPage",
        nextBtnId: "nextBrandPage",
        noResultClass: "noresult"
    });

    // Use event delegation for toggle button clicks
    document.getElementById('brandTable').addEventListener('click', function(e) {
        if (e.target.closest('.toggle-home-btn')) {
            const button = e.target.closest('.toggle-home-btn');
            button.disabled = true;

            fetch("{{ route('brands.toggle-home') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        brand_id: button.dataset.id
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    if (data.status) {
                        button.classList.toggle('btn-success', data.show_on_home);
                        button.classList.toggle('btn-outline-secondary', !data.show_on_home);
                        button.textContent = data.show_on_home ? 'Enabled' : 'Disabled';
                    } else {
                        console.error('Toggle failed:', data);
                        alert('Failed to update brand status');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Error: ' + error.message);
                })
                .finally(() => button.disabled = false);
        }
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

                const rows = document.querySelectorAll('#brandTable tbody tr');

                rows.forEach((tr, index) => {
                    const indexCell = tr.querySelector('td:first-child');
                    if (indexCell) {
                        indexCell.textContent = index + 1;
                    }
                });
            }

        });

    });
</script>


<x-admin.footer />

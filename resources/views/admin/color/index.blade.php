<x-admin.header :title="'color'" />

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
                <h4 class="mb-sm-0">Color</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Color</li>
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
                            <a href="{{ route('colors.create') }}" class="btn btn-success add-btn">
                                <i class="ri-add-line align-bottom me-1"></i> Add
                            </a>
                        </div>

                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end gap-2">
                                <div class="search-box ms-2">
                                    <input type="text" class="form-control" id="searchColors"
                                        placeholder="Search...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>

                                <a href="{{ request()->has('enabled') ? route('colors.index') : route('colors.index', ['enabled' => 1]) }}"
                                    class="btn btn-outline-info">
                                    {{ request()->has('enabled') ? 'Show All' : 'Show Enabled' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        <table class="table align-middle table-nowrap" id="colorTable">
                            <thead class="table-light">
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Home</th>
                                    <th>Published</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($colors as $color)
                                    <tr id="row-color-{{ $color->id }}">
                                        {{-- <td>{{ $loop->iteration }}</td> --}}

                                        <td>{{ $color->name }}</td>

                                        <td>
                                            <span class="badge bg-info">{{ $color->slug }}</span>
                                        </td>
                                        <td>
                                            <button
                                                class="btn btn-sm toggle-home-btn {{ $color->show_on_home ? 'btn-success' : 'btn-outline-secondary' }}"
                                                data-id="{{ $color->id }}">
                                                {{ $color->show_on_home ? 'Enabled' : 'Disabled' }}
                                            </button>
                                        </td>
                                            <td>
                                                {{ $color->created_at->format('d M, Y') }}
                                            </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-subtle-secondary btn-icon"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('colors.edit', $color->id) }}"
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
                                                                            id: {{ $color->id }}, 
                                                                            model: 'App\\Models\\Color' 
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
                                            No Color found
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
                            <a href="#" class="page-item pagination-prev disabled" id="prevColorPage">Previous</a>
                            <ul class="mb-0 pagination" id="colorPagination"></ul>
                            <a href="#" class="page-item pagination-next" id="nextColorPage">Next</a>
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
        searchInputId: "searchColors",
        tableId: "colorTable",
        paginationWrapperId: "paginationWrapper",
        paginationListId: "colorPagination",
        prevBtnId: "prevColorPage",
        nextBtnId: "nextColorPage",
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

                // Recalculate index column
                const rows = document.querySelectorAll('#colorTable tbody tr');

                rows.forEach((tr, index) => {
                    const indexCell = tr.querySelector('td:first-child');
                    if (indexCell) {
                        indexCell.textContent = index + 1;
                    }
                });
            }

        });

    });

   $('#colorTable').on('click', '.toggle-home-btn', function () {

    const button = $(this);
    button.prop('disabled', true);

    $.ajax({
        url: "{{ route('colors.toggle-home') }}", 
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            color_id: button.data('id')
        },
        success: function (data) {

            if (data.status) {

                button.toggleClass('btn-success', data.show_on_home);
                button.toggleClass('btn-outline-secondary', !data.show_on_home);
                button.text(data.show_on_home ? 'Enabled' : 'Disabled');

            } else {
                alert('Toggle failed');
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('AJAX error');
        },
        complete: function () {
            button.prop('disabled', false);
        }
    });
});

</script>
<x-admin.footer />
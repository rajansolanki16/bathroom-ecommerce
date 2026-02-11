<x-admin.header :title="'Users'" />
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
                <h4 class="mb-sm-0">Users</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Title -->

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                            <a href="{{ route('users.create') }}" class="btn btn-success add-btn">
                                <i class="ri-add-line align-bottom me-1"></i> Add
                            </a>
                        </div>

                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end">
                                <div class="search-box ms-2">
                                    <input type="text" class="form-control" id="searchUsers" placeholder="Search...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        <table class="table align-middle table-nowrap" id="userTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Index</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>OTP Code</th>
                                    <th>Username</th>
                                    {{-- <th>Mobile</th> --}}
                                    <th>Status</th>
                                    <th>Approval</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr id="row-user-{{ $user->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                        
                                            @if($user->otp)
                                                <form action="{{ route('users.send_otp', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary d-flex align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at-fill me-2" viewBox="0 0 16 16">
                                                        <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm15.98 4.87a.5.5 0 0 1 .302.625l-2 6a.5.5 0 0 1-.453.35H2.173a.5.5 0 0 1-.454-.35l-2-6a.5.5 0 0 1 .302-.625.5.5 0 0 1 .625.302l1.83 5.492h11.048l1.83-5.492a.5.5 0 0 1 .625-.302m-4.32 1.348l-4.16 2.544a.5.5 0 0 1-.5 0L4.5 8.218v3.657l3.5 2.1 3.5-2.1z"/>
                                                        </svg>
                                                        Email OTP
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary disabled" title="No OTP generated yet">
                                                    No OTP to Send
                                                </button>
                                            @endif
                                       
                                    </td>
                                        <td><span class="badge bg-info">{{ $user->username }}</span></td>
                                        {{-- <td>{{ $user->mobile }}</td> --}}
                                        <td>
                                            @if ($user->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->is_approved)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
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
                                                        <a href="{{ route('users.show', $user) }}" class="dropdown-item">
                                                            <i class="ph-eye me-1"></i> View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('users.edit', $user) }}" class="dropdown-item">
                                                            <i class="ph-pencil me-1"></i> Edit
                                                        </a>
                                                    </li>

                                                    <li><hr class="dropdown-divider"></li>

                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('users.toggle-active', $user) }}">
                                                            @csrf
                                                            <button class="dropdown-item" type="submit">
                                                                @if ($user->is_active)
                                                                    <i class="bi-lock me-1"></i> Deactivate
                                                                @else
                                                                    <i class="bi-unlock me-1"></i> Activate
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>

                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('users.toggle-approval', $user) }}">
                                                            @csrf
                                                            <button class="dropdown-item" type="submit">
                                                                @if ($user->is_approved)
                                                                    <i class="bi-x-circle me-1"></i> Block Access
                                                                @else
                                                                    <i class="bi-check-circle me-1"></i> Approve Access
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>

                                                    <li><hr class="dropdown-divider"></li>

                                                    <li>
                                                        <a href="javascript:void(0);"
                                                            class="dropdown-item text-danger"
                                                            onclick="Livewire.dispatch('confirmDelete', {
                                                                id: {{ $user->id }},
                                                                model: 'App\\Models\\User'
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
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No users found
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
                            <a href="#" class="page-item pagination-prev disabled" id="prevUserPage">
                                Previous
                            </a>

                            <ul class="mb-0 pagination" id="userPagination"></ul>

                            <a href="#" class="page-item pagination-next" id="nextUserPage">
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
        searchInputId: "searchUsers",
        tableId: "userTable",
        paginationWrapperId: "paginationWrapper",
        paginationListId: "userPagination",
        prevBtnId: "prevUserPage",
        nextBtnId: "nextUserPage",
        noResultClass: "noresult"
    });
</script>
<x-admin.footer />
<script>
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


<x-admin.header :title="'Users Management'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-sm-0">Users Management</h4>
        <div class="page-title-right">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Mobile</th>
                        <th>WhatsApp</th>
                        <th>Area</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-info">{{ $user->username }}</span></td>
                            <td>{{ $user->mobile }}</td>
                            <td>{{ $user->whatsapp_number ?? '-' }}</td>
                            <td>{{ $user->area ?? '-' }}</td>
                            <td>
                                <div class="dropdown position-static">
                                    <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="{{ route('users.show', $user) }}" class="dropdown-item edit-item-btn"><i class="align-middle ph-eye me-1"></i>View</a></li>
                                        <li><a href="{{ route('users.edit', $user) }}" class="dropdown-item edit-item-btn"><i class="align-middle ph-pencil me-1"></i>Edit</a></li>
                                        <li>
                                            <a class="dropdown-item remove-item-btn" href="javascript:void(0);"
                                                data-delete-url="{{ route('users.destroy', $user->id) }}"
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
                            <td colspan="8" class="text-center py-4 text-muted">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($users->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    @endif
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
                            Are you sure you want to remove this user <b>permanently</b>?
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

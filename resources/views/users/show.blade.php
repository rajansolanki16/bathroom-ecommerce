<x-admin.header :title="'User Details'" />
<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-sm-0">User Details</h4>
        <div class="page-title-right">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Full Name</h6>
                            <p class="h5">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Username</h6>
                            <p class="h5"><span class="badge bg-info">{{ $user->username }}</span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Email</h6>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Mobile Number</h6>
                            <p>{{ $user->mobile }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">WhatsApp Number</h6>
                            <p>{{ $user->whatsapp_number ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Country</h6>
                            <p>{{ $user->country ?? 'Not provided' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">State/Province</h6>
                            <p>{{ $user->state ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Area</h6>
                            <p>{{ $user->area ?? 'Not provided' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="text-muted">Address</h6>
                            <p>{{ $user->address ?? 'Not provided' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Account Status</h6>
                            <p>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Access Status</h6>
                            <p>
                                @if($user->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending Approval</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Email Verified</h6>
                            <p>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Verified on {{ $user->email_verified_at->format('M d, Y H:i') }}</span>
                                @else
                                    <span class="badge bg-warning">Not verified</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Joined Date</h6>
                            <p>{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="mb-3">
                            <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn @if($user->is_active) btn-warning @else btn-success @endif">
                                    @if($user->is_active)
                                        <i class="fas fa-lock"></i> Deactivate User
                                    @else
                                        <i class="fas fa-unlock"></i> Activate User
                                    @endif
                                </button>
                            </form>
                        </div>
                        <div class="mb-3">
                            <form action="{{ route('users.toggle-approval', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn @if($user->is_approved) btn-danger @else btn-primary @endif">
                                    @if($user->is_approved)
                                        <i class="fas fa-ban"></i> Block User Access
                                    @else
                                        <i class="fas fa-check"></i> Approve User Access
                                    @endif
                                </button>
                            </form>
                        </div>
                        <div>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />
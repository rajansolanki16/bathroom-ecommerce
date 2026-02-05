<x-admin.header :title="'User Details'" />
<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">User Details: {{ $user->name }}</h4>
        <div class="page-title-right">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-label waves-effect waves-light">
                <i class="ri-edit-2-line label-icon align-middle fs-16 me-2"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-label waves-effect waves-light">
                <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-24 fw-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">Member since {{ $user->created_at->format('d M, Y') }}</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <h6 class="text-muted fs-12 text-uppercase">Username</h6>
                            <p class="fw-medium"><span class="badge bg-info-subtle text-info">{{ $user->username }}</span></p>
                        </div>
                        <div class="col-6 col-md-4">
                            <h6 class="text-muted fs-12 text-uppercase">Email</h6>
                            <p class="fw-medium">{{ $user->email }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <h6 class="text-muted fs-12 text-uppercase">Mobile</h6>
                            <p class="fw-medium">{{ $user->mobile }}</p>
                        </div>
                        <div class="col-12"><hr class="text-muted opacity-25"></div>
                        <div class="col-md-4">
                            <h6 class="text-muted fs-12 text-uppercase">Location</h6>
                            <p class="fw-medium">{{ $user->area ?? 'N/A' }}, {{ $user->state ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted fs-12 text-uppercase">Address</h6>
                            <p class="fw-medium">{{ $user->address ?? 'Not provided' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded-3">
                        <div class="row text-center">
                            <div class="col-4 border-end">
                                <h6 class="text-muted fs-11 text-uppercase">Account</h6>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="col-4 border-end">
                                <h6 class="text-muted fs-11 text-uppercase">Access</h6>
                                <span class="badge {{ $user->is_approved ? 'bg-primary' : 'bg-warning' }}">
                                    {{ $user->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted fs-11 text-uppercase">Verification</h6>
                                <i class="ri-checkbox-circle-fill {{ $user->email_verified_at ? 'text-success' : 'text-muted' }} fs-18"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <form action="{{ route('users.toggle-active', $user) }}" method="POST">
                            @csrf
                            <button class="btn btn-soft-dark btn-sm">@if($user->is_active) Deactivate @else Activate @endif</button>
                        </form>
                        <form action="{{ route('users.toggle-approval', $user) }}" method="POST">
                            @csrf
                            <button class="btn btn-soft-primary btn-sm">@if($user->is_approved) Block Access @else Approve @endif</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-4">
            <div class="card card-animate border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted text-uppercase fs-12">Current Availability</h6>
                            <h4 class="mb-0" id="user-status-text">Detecting...</h4>
                        </div>
                        <div id="status-indicator" class="avatar-xs flex-shrink-0">
                            <span class="avatar-title bg-light text-muted rounded-circle fs-3">
                                <i class="ri-user-search-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

          <div class="card border-0 shadow-sm">
    <div class="card-header border-bottom-dashed">
        <h5 class="card-title mb-0">
            <i class="ri-radar-line me-1 align-bottom text-primary"></i> Recent Visits
        </h5>
    </div>
    <div class="card-body">
        <div class="acitivity-timeline acitivity-main">
            @forelse($activities as $log)
            <div class="acitivity-item d-flex mb-4">
                <div class="flex-shrink-0">
                    <div class="avatar-xs acitivity-avatar">
                        @if(is_null($log->logout_at))
                            <div class="avatar-title rounded-circle bg-success text-white shadow">
                                <i class="ri-door-open-line"></i>
                            </div>
                        @else
                            <div class="avatar-title rounded-circle bg-light text-secondary border">
                                <i class="ri-door-lock-line"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1 fs-14 fw-semibold">
                        @if(is_null($log->logout_at))
                            <span class="text-success">Currently Active</span>
                        @else
                            Visited the Platform
                        @endif
                    </h6>
                    
                    <p class="text-muted mb-2 small">
                        @if(is_null($log->logout_at))
                            Started browsing <strong>{{ $log->login_at->diffForHumans() }}</strong>
                        @else
                            Stayed for <strong>{{ $log->login_at->diffInMinutes($log->last_activity_at) }} minutes</strong>
                        @endif
                    </p>

                    <div class="p-2 bg-light rounded-2 border border-dashed">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted fs-11">
                                <i class="ri-time-line"></i> {{ $log->login_at->format('h:i A') }}
                            </span>
                            <span class="text-muted fs-11">—</span>
                            <span class="text-muted fs-11">
                                <i class="ri-logout-box-r-line"></i> 
                                {{ $log->logout_at ? $log->logout_at->format('h:i A') : 'Active Now' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($log->logout_reason && $log->logout_reason !== 'active')
                        <p class="text-muted fs-11 mt-1 mb-0">
                             <i class="ri-information-line"></i> 
                             Left by {{ $log->logout_reason == 'manual' ? 'clicking Logout' : 'closing the browser tab' }}
                        </p>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                        <i class="ri-user-unfollow-line"></i>
                    </div>
                </div>
                <p class="text-muted">No visits recorded yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
        </div> --}}
    </div>
</div>

<x-admin.footer />

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = "{{ $user->id }}";
        const statusText = document.getElementById('user-status-text');
        const indicator = document.getElementById('status-indicator');

        if (window.Echo) {
            window.Echo.join("presence-online")
                .here((users) => {
                    const isOnline = users.some(u => u.id == userId);
                    updateUserStatus(isOnline);
                })
                .joining((user) => {
                    if (user.id == userId) updateUserStatus(true);
                })
                .leaving((user) => {
                    if (user.id == userId) updateUserStatus(false);
                });
        }

        function updateUserStatus(isOnline) {
            if (isOnline) {
                statusText.innerText = "Online Now";
                statusText.className = "mb-0 text-success";
                indicator.innerHTML = '<span class="avatar-title bg-success text-white rounded-circle fs-3 pulse-animation"><i class="ri-user-follow-line"></i></span>';
            } else {
                statusText.innerText = "Offline";
                statusText.className = "mb-0 text-muted";
                indicator.innerHTML = '<span class="avatar-title bg-light text-muted rounded-circle fs-3"><i class="ri-user-unfollow-line"></i></span>';
            }
        }
    });
</script> --}}

<style>
    .pulse-animation {
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(52, 199, 89, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(52, 199, 89, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(52, 199, 89, 0); }
    }
</style>
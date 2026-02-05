<x-admin.header :title="'System Activity Monitor'" />

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Live Activity Monitor</h4>
                <div class="page-title-right">
                    <div class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-12 pulse-animation">
                        <i class="ri-broadcast-line me-1"></i> LIVE SYSTEM FEED
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card card-animate border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Currently Online</p>
                            <h4 class="fs-22 fw-semibold mb-0" id="live-count-card">0</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                <i class="ri-user-voice-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Today's Sessions</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $stats['total_sessions'] }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle text-info rounded fs-3">
                                <i class="ri-history-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Avg. Session Time</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $stats['avg_duration'] }} <small class="fs-13 text-muted">mins</small></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                <i class="ri-timer-2-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">   
                <div class="card-header border-0 pb-0">
                    <div class="row align-items-center g-3">
                        <div class="col-md-3">
                            <h5 class="card-title mb-0">User Session Logs</h5>
                        </div>
                        <div class="col-md-auto ms-auto">
                            <form action="{{ route('admin.activity.index') }}" method="GET" id="filterForm" class="d-flex gap-2">
                                <div class="search-box">
                                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search User or Email...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                                
                                <select name="status" class="form-select auto-submit" style="width: 140px;">
                                    <option value="">All Status</option>
                                    <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline</option>
                                </select>

                                <select name="reason" class="form-select auto-submit" style="width: 160px;">
                                    <option value="">All Reasons</option>
                                    <option value="tab_closed" {{ request('reason') == 'tab_closed' ? 'selected' : '' }}>Tab Closed</option>
                                    <option value="manual" {{ request('reason') == 'manual' ? 'selected' : '' }}>Manual Logout</option>
                                </select>

                                <button type="submit" class="btn btn-primary"><i class="ri-equalizer-fill"></i></button>
                                <a href="{{ route('admin.activity.index') }}" class="btn btn-soft-danger"><i class="ri-refresh-line"></i></a>
                            </form>
                        </div>
                    </div>
                </div> 
                <br>

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap table-hover mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th>User Information</th>
                                    <th>Current Status</th>
                                    <th>Login Time</th>
                                    <th>Last Activity</th>
                                    <th>Duration</th>
                                    {{-- <th>Exit Method</th> --}}
                                </tr>
                            </thead>
                            <tbody id="activity-table-body">
                                @forelse($activities as $log)
                                <tr id="row-{{ $log->user_id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0 me-3">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold">
                                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="fs-14 mb-0">{{ $log->user->name }}</h6>
                                                <small class="text-muted">{{ $log->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="status-container-{{ $log->user_id }}">
                                            @if(is_null($log->logout_at))
                                                <span class="badge bg-success-subtle text-success text-uppercase fs-11">Online</span>
                                            @else
                                                <span class="badge bg-light text-muted text-uppercase fs-11">Offline</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-muted small">{{ $log->login_at->format('d M, Y h:i A') }}</td>
                                    <td id="last-ping-{{ $log->user_id }}" class="small">
                                        {{ $log->last_activity_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $log->login_at->diffInMinutes($log->last_activity_at) }} mins</span>
                                    </td>
                                    {{-- <td>
                                        @if($log->logout_reason == 'manual')
                                            <span class="text-danger"><i class="ri-logout-circle-line me-1"></i>Manual</span>
                                        @elseif($log->logout_reason == 'tab_closed')
                                            <span class="text-warning"><i class="ri-window-line me-1"></i>Tab Close</span>
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td> --}}
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="ri-search-line fs-2 text-muted"></i>
                                        <p class="mt-2 text-muted">No activity records found matching your filters.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Showing <b>{{ $activities->firstItem() ?? 0 }}</b> to <b>{{ $activities->lastItem() ?? 0 }}</b> of <b>{{ $activities->total() }}</b> results
                        </div>
                        <div>
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-submit filters on change
        document.querySelectorAll('.auto-submit').forEach(select => {
            select.addEventListener('change', () => document.getElementById('filterForm').submit());
        });

        // Real-time Echo Join
        if (window.Echo) {
            window.Echo.join("presence-online")
                .here((users) => {
                    document.getElementById('live-count-card').innerText = users.length;
                    users.forEach(user => updateUI(user, true));
                })
                .joining((user) => {
                    updateUI(user, true);
                    let count = parseInt(document.getElementById('live-count-card').innerText);
                    document.getElementById('live-count-card').innerText = count + 1;
                })
                .leaving((user) => {
                    updateUI(user, false);
                    let count = parseInt(document.getElementById('live-count-card').innerText);
                    document.getElementById('live-count-card').innerText = count - 1;
                });
        }
    });

    function updateUI(user, isOnline) {
        const container = document.getElementById(`status-container-${user.id}`);
        const ping = document.getElementById(`last-ping-${user.id}`);
        
        if (container) {
            container.innerHTML = isOnline 
                ? '<span class="badge bg-success-subtle text-success text-uppercase fs-11">Online</span>' 
                : '<span class="badge bg-light text-muted text-uppercase fs-11">Offline</span>';
        }
        
        if (isOnline && ping) {
            ping.innerText = 'Active now';
            ping.classList.add('text-success', 'fw-bold');
        } else if(ping) {
            ping.classList.remove('text-success', 'fw-bold');
        }
    }
</script>

<style>
    .pulse-animation {
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.98); box-shadow: 0 0 0 0 rgba(52, 199, 89, 0.4); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(52, 199, 89, 0); }
        100% { transform: scale(0.98); box-shadow: 0 0 0 0 rgba(52, 199, 89, 0); }
    }
    .card-animate:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }
</style>    
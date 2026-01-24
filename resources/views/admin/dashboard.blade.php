<x-admin.header :title="'Dashboard'" />

<div class="row">
    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-primary-subtle text-primary fs-3xl rounded p-3">
                        <img src="{{ publicPath('assets/images/total-b.png') }}" class="w-100" />
                    </div>
                </div>
                <h4>{{ $bookingCount }}</h4>
                <p class="text-muted mb-4">Total Bookings</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-secondary-subtle text-secondary fs-3xl rounded p-3">
                        <img src="{{ publicPath('assets/images/total-r.png') }}" class="w-100" />
                    </div>
                </div>
                <h4>₹<span>{{ $totalAmount }}</span></h4>
                <p class="text-muted mb-4">Total Revenue</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-secondary" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-danger-subtle text-danger fs-3xl rounded p-3">
                        <img src="{{ publicPath('assets/images/monthly-b.png') }}" class="w-100" />
                    </div>
                </div>
                <h4>{{ $month_bookingCount }}</h4>
                <p class="text-muted mb-4">Last Month Bookings</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-danger" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="col-xxl col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="avatar-sm float-end">
                    <div class="avatar-title bg-success-subtle text-success fs-3xl rounded p-3">
                        <img src="{{ publicPath('assets/images/total-r.png') }}" class="w-100" />
                    </div>
                </div>
                <h4>₹<span>{{ $month_totalAmount }}</span></h4>
                <p class="text-muted mb-4">Last Month Revenue</p>
            </div>
            <div class="progress progress-sm rounded-0">
                <div class="progress-bar bg-success" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Current Check-in</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Room</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($current_checkins as $booking )
                                @php
                                    $guest['name'] = '--';  
                                    $guest['phone'] = '--';
                                    $g = json_decode($booking->customer_details);
                                    $room = App\Models\Room::find($booking->room_id);

                                    if (isset($booking->user_id)) {
                                        $g = App\Models\User::find($booking->user_id);
                                        $guest['name'] = $g->name;  
                                        $guest['phone'] = $g->mobile;
                                    } elseif (strlen($g->name > 0)) {
                                        $guest['name'] = $g->name;  
                                        $guest['phone'] = $g->phone;
                                    }
                                @endphp

                                <tr>
                                    <th scope="row">{{ $booking->id }}</th>
                                    <td>{{ $guest['name'] }}</td>
                                    <td>{{ $guest['phone'] }}</td>
                                    <td>{{ $room->name }}</td>
                                    <td><a href="{{ route('view.booking' , $booking->id) }}" class="link-success">View More <i
                                                class="ri-arrow-right-line align-middle"></i></a></td>
                                </tr>
                                
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No bookings for today.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Today's Check-in</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Room</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($today_checkins as $booking )
                                @php
                                    $guest['name'] = '--';  
                                    $guest['phone'] = '--';
                                    $g = json_decode($booking->customer_details);
                                    $room = App\Models\Room::find($booking->room_id);

                                    if (isset($booking->user_id)) {
                                        $g = App\Models\User::find($booking->user_id);
                                        $guest['name'] = $g->name;  
                                        $guest['phone'] = $g->mobile;
                                    } elseif (strlen($g->name > 0)) {
                                        $guest['name'] = $g->name;  
                                        $guest['phone'] = $g->phone;
                                    }
                                @endphp

                                <tr>
                                    <th scope="row">{{ $booking->id }}</th>
                                    <td>{{ $guest['name'] }}</td>
                                    <td>{{ $guest['phone'] }}</td>
                                    <td>{{ $room->name }}</td>
                                    <td><a href="{{ route('view.booking' , $booking->id) }}" class="link-success">View More <i
                                                class="ri-arrow-right-line align-middle"></i></a></td>
                                </tr>
                                
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No bookings for today.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />
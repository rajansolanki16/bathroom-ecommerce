<x-admin.header :title="'Bookings'" />
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" >
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" >

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">Bookings</h5>
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
                            <th>ID</th>
                            <th>TYPE</th>
                            <th>GUEST NAME</th>
                            <th>CHECK IN</th>
                            <th>CHECK OUT</th>
                            <th>ROOM</th>
                            <th>PAYMENT</th>
                            <th>UPDATED AT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if( isset($bookings) && count( $bookings) > 0)
                            @foreach ( $bookings as $booking)
                                @php
                                    $guest_name = "UNKNOWN";
                                    $r_name = optional(App\Models\Room::find($booking->room_id))->name ?? 'Unknown';
                                    $p_amount = optional(App\Models\Transaction::where("transaction_id", $booking->transaction_id)->first())->amount ?? '0';
                                    $c_detail = json_decode($booking->customer_details);

                                    if(isset($booking->user_id)){
                                        $guest_name = App\Models\User::find($booking->user_id)->name;
                                    }elseif(strlen($c_detail->name > 0)){
                                        $guest_name = $c_detail->name;
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->type }}</td>
                                    <td>{{ $guest_name }}</td>
                                    <td>{{ date("d-m-Y", strtotime($booking->check_in)) }}</td>
                                    <td>{{ date("d-m-Y", strtotime($booking->check_out)) }}</td>
                                    <td>{{ $r_name }}({{ $booking->room_count }})</td>
                                    <td>{{ $p_amount }}</td>
                                    <td>{{ $booking->updated_at }}</td>
                                    <td>
                                        <div class="dropdown position-static">
                                            <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                {{-- <li><a class="dropdown-item edit-item-btn" href="{{ route('view.edit_booking' , $booking->id) }}" ><i class="align-middle ph-pencil me-1"></i>Edit</a></li> --}}
                                                <li><a class="dropdown-item remove-item-btn" href="{{ route('view.booking' , $booking->id) }}"><i class="align-middle ri-pages-line me-1"></i> View</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />

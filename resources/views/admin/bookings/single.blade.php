<x-admin.header :title="'Booking'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">Booking</h5>
                </div>
            </div>
        </div>
    </div>
</div>

@php

    if (isset($booking->room_id)) {
        $room = App\Models\Room::find($booking->room_id);
    }

    $total_amount = 0;
    $transection = App\Models\Transaction::where("transaction_id", $booking->transaction_id)->first();
    $g = json_decode($booking->customer_details);

    $check_in = new DateTime($booking->check_in);
    $check_out = new DateTime($booking->check_out);

    $interval = $check_in->diff($check_out);
    $days_of_stay = $interval->days;
    if($days_of_stay <= 0){
        $days_of_stay = 1;
    }
    
    $guest['name'] = '--';  
    $guest['email'] = '--';
    $guest['phone'] = '--';
    $guest['address'] = '--';

    if (isset($booking->user_id)) {
        $g = App\Models\User::find($booking->user_id);
        $guest['name'] = $g->name;  
        $guest['email'] = $g->email;
        $guest['phone'] = $g->mobile;
        $guest['address'] = $g->state.", ".$g->country;
    } elseif (strlen($g->name > 0)) {
        $guest['name'] = $g->name;  
        $guest['email'] = $g->email;
        $guest['phone'] = $g->phone;
        $guest['address'] = $g->address;
    }
@endphp

<div class="row">
    <div class="col-xxl-9">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-3">
                <h6 class="card-title mb-0 flex-grow-1">Booking Items</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-borderless">
                        <thead class="table-active">
                            <tr>
                                <th>Items</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($room->id)
                                @php
                                    $total_amount += $room->price * $booking->room_count;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="product-item d-flex align-items-center gap-2">
                                            <div class="flex-grow-1">
                                                <h6 class="fs-md"><a class="text-reset">{{ $room->name }}</a></h6>
                                                <p class="text-muted mb-0"><a lass="text-reset">Room</a></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>₹{{ $room->price }}</td>
                                    <td>{{ $booking->room_count   }}</td>
                                    <td class="fw-medium text-end">₹{{ $room->price * $booking->room_count }}</td>
                                </tr>
                                @if ($booking->extra_beds > 0)
                                    @php
                                        $total_amount += $room->bed_price * $booking->extra_beds;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="product-item d-flex align-items-center gap-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="fs-md"><a class="text-reset">Extra Beds</a></h6>
                                                    <p class="text-muted mb-0"><a lass="text-reset">Additional</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>₹{{ $room->bed_price }}</td>
                                        <td>{{ $booking->extra_beds   }}</td>
                                        <td class="fw-medium text-end">₹{{ $room->bed_price * $booking->extra_beds }}</td>
                                    </tr>
                                    
                                @endif
                            @endif
                            
                            @if (count(json_decode($booking->services))>0)
                                @foreach (json_decode($booking->services) as $s_id)
                                    @php
                                        $service = App\Models\Service::find($s_id); 
                                    @endphp
                                    @if ($service->id)
                                        @php
                                            $total_amount += $service->price; 
                                        @endphp                                  
                                        <tr>
                                            <td>
                                                <div class="product-item d-flex align-items-center gap-2">
                                                    <div class="flex-grow-1">
                                                        <h6 class="fs-md"><a class="text-reset">{{ $service->name }}</a></h6>
                                                        <p class="text-muted mb-0"><a lass="text-reset">Service</a></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>₹{{ $service->price }}</td>
                                            <td>{{ $service->quantity   }}</td>
                                            <td class="fw-medium text-end">₹{{ $service->price }}</td>
                                        </tr>
                                    @endif                                    
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="row gy-3">
                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="text-center border border-dashed p-3 rounded">
                            <p class="text-muted mb-2">Check-In Date</p>
                            <h6 class="fs-md mb-0">{{ date("d M, Y", strtotime($booking->check_in)) }}</h6>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="text-center border border-dashed p-3 rounded">
                            <p class="text-muted mb-2">Check-Out Date</p>
                            <h6 class="fs-md mb-0">{{ date("d M, Y", strtotime($booking->check_out)) }}</h6>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="text-center border border-dashed p-3 rounded">
                            <p class="text-muted mb-2">Booking Date</p>
                            <h6 class="fs-md mb-0">{{ date("d M, Y", strtotime($booking->created_at)) }}</h6>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="text-center border border-dashed p-3 rounded">
                            <p class="text-muted mb-2">Booking Type</p>
                            <h6 class="fs-md mb-0">{{ $booking->type }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3">
        <div class="row">
            <div class="col-xxl-12 col-md-6">
                <div class="card border-bottom border-2 border-light">
                    <div class="card-body d-flex gap-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-3">Customer Info</h6>
                            <p class="fw-medium fs-md mb-1">Name: <b>{{ $guest['name'] }}</b></p>
                            <p class="text-muted mb-1">Email: <b>{{ $guest['email'] }}</b></p>
                            <p class="text-muted mb-1">Phone: <b>{{ $guest['phone'] }}</b></p>
                            <p class="text-muted mb-0">Address: <b>{{ $guest['address'] }}</b></p>
                        </div>
                        <div class="flex-shrink-0">
                            <img src="{{ publicPath("admin/images/Customer-Detail.webp")}}" class="avatar-sm rounded img-thumbnail">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12 col-md-6">
                <div class="card border-bottom border-2 border-light">
                    <div class="card-body d-flex gap-3">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-3">Payment Info</h6>
                            <p class="fw-medium fs-md mb-1">ID: {{$transection->transaction_id }}</p>
                            <p class="text-muted mb-1">Method: <b>{{ $transection->method }}</b></p>
                            <p class="text-muted mb-1">Amount: <b>₹{{ $transection->amount }}</b></p>
                            <p class="text-muted mb-1">Status: <b>
                                @switch($transection->status)
                                    @case(1)
                                        PAID
                                        @break
                                    @case(2)
                                        @if ($transection->method == "CASH")
                                            PENDING
                                        @else
                                            PROCESSING
                                        @endif
                                        @break
                                    @case(0)
                                        FAILED
                                        @break
                                    @default
                                 @endswitch
                                </b></p>
                            <p class="text-muted mb-1">Time: <b>{{ $transection->created_at }}</b></p>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <img src="{{ publicPath("admin/images/Transection-Detail.png")}}" class="avatar-sm rounded img-thumbnail">
                            <button class="btn btn-primary mt-2" id="booking_update_payment_status" data-bs-toggle="modal" data-bs-target="#myModal">Update Status</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Total order amount</h6>
                    </div>
                    <div class="card-body pt-4">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    @if($booking->type =="OFFLINE")
                                        <tr>
                                            <td>Sub Total:</td>
                                            <td class="text-end">₹{{ $booking->total_cost }}</td>
                                        </tr>
                                    @else()
                                        <tr>
                                            <td>Sub Total:</td>
                                            <td class="text-end">₹{{ $total_amount }}</td>
                                        </tr>
                                        <tr>
                                            <td> Day Total:</td>
                                            <td class="text-end">₹{{ $total_amount*$days_of_stay }}</td>
                                        </tr>
                                        @endif

                                    <tr class="border-top border-top-dashed">
                                        <th scope="row">Total (INR) :</th>
                                        @if($booking->type =="OFFLINE")
                                            <th class="text-end">₹{{ $booking->total_cost }}</th>
                                        @else()
                                            <th class="text-end">₹{{ $booking->total_cost*$days_of_stay }}</th>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PAyment Status Modals -->
<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('booking_payment.change.save' , $booking->id) }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Update Payment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <h5 class="fs-base">
                        Update the payment status of this booking  
                    </h5>
                    <div class="row">
                        <div class="col-xxl-12">
                            <p class="form-label mt-3">Current Status : 
                                <strong>
                                    @if($transaction->status == "1") PAID 
                                    @elseif($transaction->status == "2") PROCESSING 
                                    @elseif($transaction->status == "3") PENDING 
                                    @else CANCELED 
                                    @endif
                                </strong>
                            </p>
                            <label for="pay-method" class="form-label mt-3">Method</label>
                            <select class="form-control" name="pay_method" id="pay-method" disabled>
                                <option value="CASH" @if( $transaction->method == "CASH") selected @endif >CASH</option>
                                <option value="PAYU" @if( $transaction->method == "PAYU") selected @endif >PAYU</option>
                                <option value="CASHFREE" @if( $transaction->method == "CASHFREE") selected @endif >CASHFREE</option>
                            </select>

                            <label for="pay-status" class="form-label mt-3">Change Status</label>
                            <select class="form-control" name="pay_status" id="pay-status">
                                <option value="1" @if( $transaction->status == "1") selected @endif >PAID</option>
                                @if( $transaction->method == "CASH") 
                                    <option value="2" @if( $transaction->status == "2") selected @endif >PENDING</option>
                                @else
                                    <option value="2" @if( $transaction->status == "2") selected @endif >PROCESSING</option>
                                @endif
                                <option value="0" @if( $transaction->status == "0") selected @endif >CANCELED</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-admin.footer />
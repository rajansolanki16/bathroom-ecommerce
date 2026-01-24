<x-admin.header :title="'Edit Booking'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">Edit Booking</h5>
                </div>
            </div>
        </div>
    </div>
</div>

@php

    $g = json_decode($booking->customer_details);

    $guest['name'] = '--';  
    $guest['email'] = '--';
    $guest['phone'] = '--';
    $guest['address'] = '--';

    $is_user = false;

    if (isset($booking->user_id)) {
        $g = App\Models\User::find($booking->user_id);
        $guest['name'] = $g->name;  
        $guest['email'] = $g->email;
        $guest['phone'] = $g->mobile;
        $guest['address'] = $g->state.", ".$g->country;
        $is_user = true;
    } elseif (strlen($g->name > 0)) {
        $guest['name'] = $g->name;  
        $guest['email'] = $g->email;
        $guest['phone'] = $g->phone;
        $guest['address'] = $g->address;
    }

@endphp

<form class="store-blogs" action="{{ route('edit_booking.save' , $booking->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Guest Information</h5>
                            <p class="text-muted">Guest Information refers to the data related to guest wants to book the room.</p>
                        </div>
                        <div class="col-xxl-8">

                            <div class="mb-3">
                                <label for="guest_full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="guest_full_name" id="guest_full_name" class="form-control @error('guest_full_name') is-invalid @enderror" placeholder="Enter full name of guest" value="{{ old('guest_full_name',$guest['name']) }}" required {{ $is_user ? "disabled":''}}>
                                @error('guest_full_name')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="guest_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="guest_email" id="guest_email" class="form-control @error('guest_email') is-invalid @enderror" placeholder="Enter email address of guest" value="{{ old('guest_email', $guest['email']) }}" required {{ $is_user ? "disabled":''}}>
                                @error('guest_email')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="guest_phone" class="form-label">Contact No. <span class="text-danger">*</span></label>
                                <input type="tel" name="guest_phone" id="guest_phone" class="form-control @error('guest_phone') is-invalid @enderror" placeholder="Enter phone number of guest" value="{{ old('guest_phone', $guest['phone']) }}" required {{ $is_user ? "disabled":''}}>
                                @error('guest_phone')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="guest_address" class="form-label">Address</label>
                                <input type="text" name="guest_address" id="guest_address" class="form-control @error('guest_address') is-invalid @enderror" placeholder="Enter address of guest" value="{{ old('guest_address',$guest['address']) }}" {{ $is_user ? "disabled":''}}>
                                @error('guest_address')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">IN/OUT Dates</h5>
                            <p class="text-muted">IN/OUT Dates refers to the dates related to guest wants to book the room.</p>
                        </div>
                        <div class="gap-4 col-xxl-8 d-flex">
                            <div class="mb-3">
                                <label for="check_in" class="form-label">Check In Date<span class="text-danger">*</span></label>
                                <input type="date" name="check_in" id="check_in" class="form-control @error('check_in') is-invalid @enderror" placeholder="Enter date of check-in" value="{{ old('check_in', date('Y-m-d', strtotime($booking->check_in))) }}" required>
                                @error('check_in')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="check_out" class="form-label">Check Out Date<span class="text-danger">*</span></label>
                                <input type="date" name="check_out" id="check_out" class="form-control @error('check_out') is-invalid @enderror" placeholder="Enter date of check-out" value="{{ old('check_out',date('Y-m-d', strtotime($booking->check_out))) }}" required>
                                @error('check_out')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Adults And Clildren</h5>
                            <p class="text-muted">Adults and Clildren refers to the numbers related to guest wants to stay in the room.</p>
                        </div>
                        <div class="gap-4 col-xxl-8 d-flex">

                            <div class="mb-3">
                                <label for="adults" class="form-label">Adults<span class="text-danger">*</span></label>
                                <input type="number" name="adults" id="adults" class="form-control @error('adults') is-invalid @enderror" placeholder="Enter the number of adults" value="{{ old('adults',$booking->adults ) }}" required>
                                @error('adults')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="children" class="form-label">Clildren<span class="text-danger">*</span></label>
                                <input type="number" name="children" id="children" class="form-control @error('children') is-invalid @enderror" placeholder="Enter the number of children" value="{{ old('children',$booking->children) }}" required>
                                @error('children')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Rooms</h5>
                            <p class="text-muted">Rooms refers to the numbers and type related rooms to guest wants to stay.</p>
                        </div>
                        <div class="gap-4 col-xxl-8 d-flex">

                            <div class="mb-3">
                                <label for="room_count" class="form-label">Count<span class="text-danger">*</span></label>
                                <input type="number" name="room_count" id="room_count" class="form-control @error('room_count') is-invalid @enderror" placeholder="Enter the number of room_count" value="{{ old('room_count',$booking->room_count) }}" required>
                                @error('room_count')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="booking_room_type" class="form-label">Type<span class="text-danger">*</span></label>
                                <select class="mb-3 form-select @error('room_type') is-invalid @enderror" name="room_type" id="booking_room_type" required>
                                    @forelse ($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_type',$booking->room_id) == $room->id ? 'selected': ''}} >{{ $room->name }}</option>
                                    @empty
                                        <option selected disabled>No Rooms To Show Here</option>
                                    @endforelse
                                </select>
                                @error('room_type')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Services</h5>
                            <p class="text-muted">Services refers to the facilities you provide to the guest.</p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label class="form-label">Select Services</label>
                                <div class="flex-wrap gap-3">
                                    @forelse ($services as $service)
                                        @if($service->status == 1)
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input booking_form_services"
                                                    type="checkbox"
                                                    name="services[]"
                                                    value="{{ $service->id }}"
                                                    {{ in_array($service->id, old('services', json_decode($booking->services))) ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label" for="services">{{ $service->name }}</label>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="form-check"> No services found </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Customer Note</h5>
                            <p class="text-muted">
                                Customer Note refers to the message or note needs to add with the booking.
                            </p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label class="form-label">Customer Note</label>
                                <textarea name="guest_note" class="myeditor @error('guest_note') is-invalid @enderror form-control" id="guest_note"cols="3" rows="7">{{ old('guest_note', $booking->customer_note) }}</textarea>
                                @error('guest_note')
                                    <span class="form-error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none" id="get_room_services_url" data-url="{{ route('rooms.services') }}"></div>

    <div class="gap-2 mb-4 hstack justify-content-end">
        @error('general')
            <div class="invalid-response" style="display:flex">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-danger">Cancel</button>
    </div>

</form>

<x-admin.footer />
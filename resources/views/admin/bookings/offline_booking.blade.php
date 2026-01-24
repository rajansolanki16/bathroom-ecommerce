<x-admin.header :title="'Offline Booking'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">Offline Booking</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<form class="store-blogs" action="{{ route('offline_booking.save') }}" method="POST" enctype="multipart/form-data">
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
                                <input type="text" name="guest_full_name" id="guest_full_name" class="form-control @error('guest_full_name') is-invalid @enderror" placeholder="Enter full name of guest" value="{{ old('guest_full_name') }}" required>
                                @error('guest_full_name')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="guest_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="guest_email" id="guest_email" class="form-control @error('guest_email') is-invalid @enderror" placeholder="Enter email address of guest" value="{{ old('guest_email') }}" required>
                                @error('guest_email')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="guest_phone" class="form-label">Contact No. <span class="text-danger">*</span></label>
                                <input type="tel" name="guest_phone" id="guest_phone" class="form-control @error('guest_phone') is-invalid @enderror" placeholder="Enter phone number of guest" value="{{ old('guest_phone') }}" required>
                                @error('guest_phone')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="guest_address" class="form-label">Address</label>
                                <input type="text" name="guest_address" id="guest_address" class="form-control @error('guest_address') is-invalid @enderror" placeholder="Enter address of guest" value="{{ old('guest_address') }}">
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
                                <input type="text" name="check_in" id="check_in" class="form-control checkin_date_picker @error('check_in') is-invalid @enderror" placeholder="Enter date of check-in" value="{{ old('check_in', date('Y-m-d')) }}" data-old="{{ old('check_in', date('Y-m-d')) }}" placeholder="Checkin date" required>
                                @error('check_in')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="check_out" class="form-label">Check Out Date<span class="text-danger">*</span></label>
                                <input type="text" name="check_out" id="check_out" class="form-control checkout_date_picker @error('check_out') is-invalid @enderror" placeholder="Enter date of check-out" value="{{ old('check_out', date('Y-m-d')) }}" data-old="{{ old('check_out', date('Y-m-d')) }}" placeholder="Checkout date" required>
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
                                <input type="number" name="adults" id="adults" class="form-control @error('adults') is-invalid @enderror" placeholder="Enter the number of adults" value="{{ old('adults',1) }}" required>
                                @error('adults')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="children" class="form-label">Clildren<span class="text-danger">*</span></label>
                                <input type="number" name="children" id="children" class="form-control @error('children') is-invalid @enderror" placeholder="Enter the number of children" value="{{ old('children',0) }}" required>
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
                                <input type="number" name="room_count" id="room_count" class="form-control @error('room_count') is-invalid @enderror" placeholder="Enter the number of room_count" value="{{ old('room_count',0) }}" required>
                                @error('room_count')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="booking_room_type" class="form-label">Type<span class="text-danger">*</span></label>
                                <select class="mb-3 form-select @error('room_type') is-invalid @enderror" name="room_type" id="booking_room_type" required>
                                    <option selected disabled>Select Room Type</option>
                                    @forelse ($rooms as $room)
                                        <option value="{{ $room->slug }}" {{ old('room_type') == $room->slug ? 'selected': ''}} >{{ $room->name }}</option>
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
                            <h5 class="mb-3 card-title">Extras</h5>
                            <p class="text-muted">Extras refers to the additional numbers of items guest wants while booking.</p>
                        </div>
                        <div class="gap-4 col-xxl-8">
                            <div class="mb-3">
                                <label for="extra_beds" class="form-label">Beds<span class="text-danger">*</span></label>
                                <input type="number" name="extra_beds" id="extra_beds" class="form-control @error('extra_beds') is-invalid @enderror" placeholder="Enter the number of extra beds" value="{{ old('extra_beds',0) }}" required>
                                @error('extra_beds')
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
                                    @php
                                        $counterForId=1;
                                    @endphp
                                    @forelse ($services as $service)
                                        @if($service->status == 1)
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input booking_form_services"
                                                    type="checkbox"
                                                    id="services_{{ $counterForId }}"
                                                    name="services[]"
                                                    value="{{ $service->id }}"
                                                    {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label" for="services_{{ $counterForId }}">{{ $service->name }}</label>
                                            </div>
                                        @endif
                                        @php
                                            $counterForId++
                                        @endphp
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
                            <h5 class="mb-3 card-title">Payments</h5>
                            <p class="text-muted">Payments to the methods and amount related booking.</p>
                        </div>
                        <div class="gap-4 col-xxl-8 d-flex">

                            <div class="mb-3">
                                <label for="pay_method" class="form-label">Method<span class="text-danger">*</span></label>
                                <select class="mb-3 form-select @error('pay_method') is-invalid @enderror" name="pay_method" id="pay_method" required>
                                    <option selected disabled>Select Payment Method</option>
                                    @foreach ($pay_methods as $pay_method)
                                        {{-- <option value="{{ $pay_method }}" {{ old('pay_method') == $pay_method ? 'selected': ''}} >{{ $pay_method }}</option> --}}
                                        <option value="{{ $pay_method }}" selected >{{ $pay_method }}</option>
                                    @endforeach
                                </select>
                                @error('pay_method')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="total_amount" class="form-label">Total Amount<span class="text-danger">*</span></label>
                                <input type="number" name="total_amount" id="total_amount" class="form-control @error('total_amount') is-invalid @enderror" placeholder="Enter the total amount" value="{{ old('total_amount',0) }}" required>
                                @error('total_amount')
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
                            <h5 class="mb-3 card-title">Customer Note</h5>
                            <p class="text-muted">
                                Customer Note refers to the message or note needs to add with the booking.
                            </p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label class="form-label">Customer Note</label>
                                <textarea name="guest_note" class="myeditor @error('guest_note') is-invalid @enderror form-control" id="guest_note"cols="3" rows="7">{{ old('guest_note') }}</textarea>
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
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
    </div>

</form>

<x-admin.footer />
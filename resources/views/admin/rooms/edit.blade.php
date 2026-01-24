<x-admin.header :title="'Add Room'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-4 card-title">Update Room</h3>
                </div>
            </div>
        </div>
    </div>
</div>

@php

    $bed = json_decode($room->beds, true);
    $edit_amenities = json_decode($room->amenities);
    $edit_services = json_decode($room->service);
    if (empty($edit_amenities) || count($edit_amenities) == 0) {
        $edit_amenities[] = 0;
    }
    if (empty($edit_services) || count($edit_services) == 0) {
        $edit_services[] = 0;
    }

    $edit_galleryImages = json_decode($room->gallery_img);

@endphp

<form class="store-blogs" action="{{ route('rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-xxl-4">
                            <label for="title" class="form-label">Room Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror" placeholder="Enter room title"
                                value="{{ old('title', $room->name) }}" required>
                            @error('title')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-xxl-4">
                            <label for="quantity" class="form-label">Room Quantity <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity"
                                class="form-control @error('quantity') is-invalid @enderror"
                                placeholder="Enter number of rooms in group" min="0"
                                value="{{ old('quantity', $room->quantity) }}" required>
                            @error('quantity')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-xxl-4">
                            <label for="size" class="form-label">Room Size<span class="text-danger">*</span></label>
                            <input type="number" name="size" id="size"
                                class="form-control @error('size') is-invalid @enderror"
                                placeholder="Enter the size of the single room in feets" min="0"
                                value="{{ old('size', $room->size) }}" required>
                            @error('size')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-xxl-4">
                            <label for="price" class="form-label">Room Price<span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="price" id="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    placeholder="Enter price of the single room" min="0"
                                    value="{{ old('price', $room->price) }}" required>
                                <span class="input-group-text">.00</span>
                            </div>
                            @error('price')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-xxl-4">
                            <label for=offer_price class="form-label">Room Offer Price<span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name=offer_price id=offer_price
                                    class="form-control @error('offer_price') is-invalid @enderror"
                                    placeholder="Enter offer price of the single room" min="0"
                                    value="{{ old('offer_price', $room->offer_price) }}" required>
                                <span class="input-group-text">.00</span>
                            </div>
                            @error('offer_price')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-xxl-4">
                            <label for="bed_price" class="form-label">Bed Price/Bed<span
                                    class="text-danger">*</span></label>
                            <div class="gap-2">
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="bed_price" id="bed_price"
                                        class="form-control @error('bed_price') is-invalid @enderror"
                                        placeholder="Enter price for extra beds" min="0"
                                        value="{{ old('bed_price', $room->bed_price) }}" required>
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                            @error('bed_price')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-xxl-4">
                            <label for="price" class="form-label">Room Beds<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="bed_quantity" id="bed_quantity"
                                    class="form-control @error('bed_quantity') is-invalid @enderror"
                                    placeholder="Quantity" min="0"
                                    value="{{ old('bed_quantity', $bed['quentity'] ?? '') }}" required>
                            </div>
                            @error('bed_quantity')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-xxl-4">
                            <label for=offer_price class="form-label">Room Bed Name<span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="bed_name" id="bed_name"
                                    class="form-control @error('bed_name') is-invalid @enderror"
                                    placeholder="Name of bed" value="{{ old('bed_name', $bed['name'] ?? '') }}"
                                    required>
                            </div>
                            @error('bed_name')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-xxl-4">
                            <label for="allowd_guests" class="form-label">Room Allow Guest<span
                                    class="text-danger">*</span></label>
                            <input type="number" name="allowd_guests" id="allowd_guests"
                                class="form-control @error('allowd_guests') is-invalid @enderror"
                                placeholder="Enter the number of guest allowd in the single room" min="0"
                                value="{{ old('allowd_guests', $room->allowd_guests) }}" required>
                            @error('allowd_guests')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-xxl-4">
                            <label for="price" class="form-label">Room Allowd Extra Beds<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="extra_bed_quantity" id="extra_bed_quantity" class="form-control @error('extra_bed_quantity') is-invalid @enderror" placeholder="Quantity" min="0" value="{{ old('extra_bed_quantity',$room->extra_beds) }}" required>
                            </div>
                            @error('extra_bed_quantity')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-xxl-6">
                            <label class="form-label">Room Amenities <span class="text-danger">*</span></label>
                            <div class="flex-wrap gap-3">
                                @php
                                    $counterForId=1;
                                @endphp
                                @forelse ($amenities as $amenity)
                                    @if ($amenity->status == 1)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amenities[]"
                                                value="{{ $amenity->id }}" id="amenities_{{ $counterForId }}"
                                                {{ in_array($amenity->id, old('amenities', $edit_amenities)) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                            for="amenities_{{ $counterForId }}">{{ $amenity->name }}</label>
                                        </div>
                                    @endif
                                    @php
                                        $counterForId++
                                    @endphp
                                @empty
                                    <div class="form-check"> No amenities found </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-3 col-xxl-6">
                            <label class="form-label">Room Services <span class="text-danger">*</span></label>
                            <div class="flex-wrap gap-3">
                                @php
                                    $counterForId=1;
                                @endphp
                                @forelse ($services as $service)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="services[]"
                                            value="{{ $service->id }}" id="services{{ $counterForId }}"
                                            {{ in_array($service->id, old('services', $edit_services)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="services{{ $counterForId }}">{{ $service->name }}</label>
                                    </div>
                                    @php
                                        $counterForId++
                                    @endphp
                                @empty
                                    <div class="form-check"> No services found </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Room Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" placeholder="Enter room description" rows="5"
                                class="myeditor form-control @error('description') is-invalid @enderror">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Room Features</label>
                            <textarea name="features" id="features" placeholder="Enter room features" rows="5"
                                class="myeditor form-control @error('features') is-invalid @enderror">{{ old('features', $room->features) }}</textarea>
                            @error('features')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-xxl-4">
                            <label class="form-label">Featured Image <span class="text-danger">*</span></label>
                            <input type="file" name="featured_image" id="featured_image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave blank if you do not want to update the image.</small>
                            @error('featured_image')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror

                            <div class="flex-wrap mt-3 d-flex" data-url="{{ route('rooms.media.remove') }}" data-id="{{ $room->id }}" data-token="{{ csrf_token() }}" data-type="featured">
                                <div class="m-2 position-relative">
                                    @if ($room->feature_img)
                                        <img id="imagePreview" src="{{ publicPath($room->feature_img) }}" alt="Featured Image Preview" class="img-fluid admin-media-preview">
                                    @else
                                        <span> No Featured Found </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 col-xxl-4">
                            <label class="form-label">Tour Video</label>
                            <input type="file" name="tour_video" id="tour_video" class="form-control" accept=".mp4,.avi,.mkv,.flv,.mov">
                            <small class="text-muted">Leave blank if you do not want to update the video.</small>
                            @error('tour_video')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                            <div class="flex-wrap mt-3 d-flex" data-url="{{ route('rooms.media.remove') }}" data-id="{{ $room->id }}" data-token="{{ csrf_token() }}" data-type="tour">
                                <div class="m-2 position-relative">
                                    @if ($room->tour_video)
                                        <video id="videoPreview" controls class="img-fluid admin-media-preview">
                                            <source id="videoSource" src="{{ publicPath($room->tour_video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <button type="button" class="top-0 btn btn-danger btn-sm position-absolute end-0 remove-room-media" data-media="{{ $room->tour_video }}">X</button>
                                    @else
                                        <span> No Tour Video Found </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 col-xxl-4">
                            <label class="form-label">Gallery Images</label>
                            <input type="file" name="gallery_images[]" id="gallery_images" class="form-control" multiple>
                            <small class="text-muted">Leave blank if you do not want to update the images.</small>
                            @error('gallery_images')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                            @enderror
                            <div class="flex-wrap mt-3 d-flex" data-url="{{ route('rooms.media.remove') }}" data-id="{{ $room->id }}" data-token="{{ csrf_token() }}" data-type="gallery">
                                @if (count($edit_galleryImages) > 0)
                                    @foreach ($edit_galleryImages as $edit_galleryImage)
                                        <div class="m-2 position-relative">
                                            <img id="imagePreview" src="{{ publicPath($edit_galleryImage) }}" alt="Gallery Images Preview" class="img-fluid admin-media-preview">
                                            <button type="button" class="top-0 btn btn-danger btn-sm position-absolute end-0 remove-room-media" data-media="{{ $edit_galleryImage }}">X</button>
                                        </div>
                                    @endforeach
                                @else
                                    <span> No Images Found </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="gap-2 mb-3 hstack justify-content-end">
        @error('general')
            <div class="invalid-response" style="display:flex">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: ".myeditor",
            height: 250,
            menubar: false,
            plugins: "advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help",
            toolbar: "undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help",
            content_style: "body { font-family: Arial, sans-serif; font-size: 14px; }",
        });
    });
</script>

<x-admin.footer />

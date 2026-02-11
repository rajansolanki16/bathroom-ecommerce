<x-admin.header :title="'Product'" />
<div class="container-fluid">
    <form id="productForm" action="{{ route('products.update', $product->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                   <div class="card-body">
                        <div class="row">

                            <!-- Left Info -->
                            <div class="col-xxl-4">
                                <h5 class="card-title mb-3">Product Information</h5>
                                <p class="text-muted">
                                    Product Information refers to any information held by an organisation
                                    about the products it produces, buys, sells or distributes.
                                </p>
                            </div>

                            <!-- Right Form -->
                            <div class="col-xxl-8">

                                <!-- Product Title -->
                                <div class="mb-3">
                                    <label class="form-label">Product Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ old('title', $product->product_title) }}">
                                    @error('title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Categories -->
                                <div class="mb-3">
                                    <label class="form-label">Categories</label>
                                    <select class="form-control choices" name="categories[]" multiple>
                                        @foreach ($categories as $parent)
                                            <optgroup label="{{ $parent->name }}">
                                                <option value="{{ $parent->id }}"
                                                    {{ in_array($parent->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                                @foreach ($parent->children as $child)
                                                    <option value="{{ $child->id }}"
                                                        {{ in_array($child->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                        — {{ $child->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Short Description -->
                                <div class="mb-3">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Brand & Color -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Brand</label>
                                            <select name="brand_id" class="form-control choices">
                                                <option value="">Select Brand</option>
                                                @foreach ($allbrands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Color</label>
                                            <select name="color_id" class="form-control choices">
                                                <option value="">Select Color</option>
                                                @foreach ($allcolors as $color)
                                                    <option value="{{ $color->id }}"
                                                        {{ $product->color_id == $color->id ? 'selected' : '' }}>
                                                        {{ $color->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tags & SKU -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tags</label>
                                            <select class="form-control choices" name="tags[]" multiple>
                                                @foreach ($allTags as $tag)
                                                    <option value="{{ $tag->id }}"
                                                        {{ $product->tags->contains($tag->id) ? 'selected' : '' }}>
                                                        {{ $tag->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">SKU Number</label>
                                            <input type="text" name="sku_number" class="form-control"
                                                value="{{ old('sku_number', $product->sku_number) }}"
                                                placeholder="SKU-ABC-001">
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Visibility -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control choices">
                                                @foreach ($productStatuses as $status)
                                                    <option value="{{ $status->value }}"
                                                        {{ $product->status == $status->value ? 'selected' : '' }}>
                                                        {{ $status->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Visibility</label>
                                            <select name="visibility" class="form-control choices">
                                                @foreach ($productVisibilities as $visibility)
                                                    <option value="{{ $visibility->value }}"
                                                        {{ $product->visibility == $visibility->value ? 'selected' : '' }}>
                                                        {{ $visibility->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div><!--end col-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4">
                                <h5 class="card-title mb-3">Description</h5>
                                <p class="text-muted">Product Information refers to any information held by an organization
                                    about the products it produces, buys, sells or distributes.</p>
                            </div><!--end col-->
                            <div class="col-xxl-8">
                                <div>
                                    <label class="form-label">Product Description <span class="text-danger">*</span></label>
                                    <textarea class="ckeditor-classic" name="product_decscription" id="productDescription" rows="5">
                                                    {!! old('product_decscription', $product->product_decscription) !!}
                                                </textarea>
                                </div>
                            </div>
                        </div><!--end row-->
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4">
                                <h5 class="card-title mb-3">Images</h5>
                                <p class="text-muted">Product Information refers to any information held by an organization
                                    about the products it produces, buys, sells or distributes.</p>
                            </div><!--end col-->
                            <div class="col-xxl-8">
                                <div class="mb-4">
                                    <label class="form-label">Product Image <span class="text-danger">*</span></label>

                                    <div class="d-flex gap-2 mb-2">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#mediaPickerModalMain">Choose from Media Library</button>
                                    </div>
                                    <input type="hidden" name="media_library_main_image_id" id="media_library_main_image_id"
                                        value="{{ old('media_library_main_image_id', $product->media_library_main_image_id ?? optional($product->getFirstMedia('main_image'))->id) }}">
                                    <div id="selected-main-image-preview" class="mt-2 mb-2"></div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        Gallery Images
                                    </label>

                                    <div class="d-flex gap-2 mb-2">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#mediaPickerModalGallery">Choose from Media Library</button>
                                    </div>
                                    {{-- Use CSV-formatted IDs so picker can correctly preselect and highlight existing images --}}
                                    <input type="hidden" name="media_library_gallery_image_ids"
                                        id="media_library_gallery_image_ids"
                                        value="{{ old('media_library_gallery_image_ids', $galleryIds) }}">
                                    <div id="selected-gallery-images-preview" class="mt-2 d-flex flex-wrap"
                                        style="gap:10px;"></div>

                                </div>

                            </div>
                        </div><!--end row-->
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4">
                                <h5 class="card-title mb-3">SEO Settings</h5>
                                <p class="text-muted">Add SEO meta tags to help search engines understand your product better.
                                </p>
                            </div><!--end col-->
                            <div class="col-xxl-8">
                                <div class="mb-3">
                                    <label for="metaTitle" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                        name="meta_title" id="metaTitle"
                                        value="{{ old('meta_title', $product->meta_title) }}"
                                        placeholder="Enter meta title (max 160 characters)" maxlength="160">
                                    <small class="text-muted">Recommended: 50-60 characters</small>
                                    @error('meta_title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="metaDescription" class="form-label">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description"
                                        id="metaDescription" rows="3" placeholder="Enter meta description (max 160 characters)" maxlength="160">{{ old('meta_description', $product->meta_description) }}</textarea>
                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                    @error('meta_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="metaKeywords" class="form-label">Meta Keywords</label>
                                    <textarea class="form-control @error('meta_keywords') is-invalid @enderror" name="meta_keywords" id="metaKeywords"
                                        rows="3" placeholder="Enter meta keywords separated by comma">{{ old('meta_keywords', $product->meta_keywords) }}</textarea>
                                    <small class="text-muted">Example: bathroom fixtures, tiles, faucets</small>
                                    @error('meta_keywords')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div><!--end row-->
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row" id="vec_general_Info_Section">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4">
                                <h5 class="card-title mb-3">General Info</h5>
                                <p class="text-muted mb-0">An informational product can be a digital book (or e-book), a
                                    digital report, a white paper, a piece of software, audio or video files, a website, an
                                    e-zine or a newsletter.</p>
                            </div><!--end col-->
                            <div class="col-xxl-8">
                                <div class="row gy-3">
                                    <div class="col-lg-4">
                                        <label class="form-label">
                                            Stock <span class="text-danger">*</span>
                                        </label>

                                        <select name="stock" class="form-control choices">
                                            <option value="">Select Stock</option>
                                            <option value="1" {{ old('stock', $product->stock) == 1 ? 'selected' : '' }}>
                                                In Stock
                                            </option>
                                            <option value="0" {{ old('stock', $product->stock) == 0 ? 'selected' : '' }}>
                                                Out of Stock
                                            </option>
                                        </select>

                                        @error('stock')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label class="form-label" for="product-price-input">Price</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="product-price-addon">$</span>
                                                <input type="number" step="0.01" name="price" class="form-control"
                                                    value="{{ old('price', $product->price) }}">
                                                <div class="invalid-feedback">Please Enter a product price.</div>
                                            </div>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div><!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label class="form-label" for="product-discount-input">Discount</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="product-discount-addon">%</span>
                                                <input type="number" step="0.01" name="discount" class="form-control"
                                                    value="{{ old('discount', $product->discount) }}">
                                            </div>
                                        </div>
                                        @error('discount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div><!--end col-->
                                    <!-- Sell Price -->
                                    <div class="col-lg-4">
                                        <label class="form-label">Sell Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" name="sell_price" class="form-control"
                                                value="{{ old('sell_price', $product->sell_price) }}">
                                        </div>
                                        @error('sell_price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label">Sell Price Start Date</label>
                                        <input type="date" name="sell_price_start_date" class="form-control"
                                            value="{{ old('sell_price_start_date', optional($product->sell_price_start_date)->format('Y-m-d')) }}">
                                        @error('sell_price_start_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label">Sell Price End Date</label>
                                        <input type="date" name="sell_price_end_date" class="form-control"
                                            value="{{ old('sell_price_end_date', optional($product->sell_price_end_date)->format('Y-m-d')) }}">
                                        @error('sell_price_end_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="row gy-2">
                                        <div class="col-lg-6">
                                            <div class="form-check form-switch mb-3">
                                                <input type="checkbox" name="exchangeable" value="1"
                                                    class="form-check-input"
                                                    {{ old('exchangeable', $product->exchangeable) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="exchangeableInput">Exchangeable</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check form-switch mb-3">
                                                <input type="checkbox" name="refundable" value="1"
                                                    class="form-check-input"
                                                    {{ old('refundable', $product->refundable) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="refundableInput">Refundable</label>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end row-->
                            </div>
                        </div><!--end row-->
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        {{-- <div class="row" class="vec_shipping_section" id="vec_shipping_section">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4">
                                <h5 class="card-title mb-3">Shipping</h5>
                                <p class="text-muted">
                                    Define product shipping details like weight, dimensions and free shipping option.
                                </p>
                            </div><!--end col-->

                            <div class="col-xxl-8">
                                <div class="row gy-3">

                                    <!-- Weight -->
                                    <div class="col-lg-4">
                                        <div>
                                            <label class="form-label">Weight</label>
                                            <input type="number" step="0.01" name="weight" value="{{ old('weight', $product->weight) }}" class="form-control">
                                            @error('weight')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Length -->
                                    <div class="col-lg-4">
                                        <div>
                                            <label class="form-label">Length</label>
                                            <input type="number" step="0.01" name="length" value="{{ old('length', $product->length) }}" class="form-control">
                                            @error('length')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Width -->
                                    <div class="col-lg-4">
                                        <div>
                                            <label class="form-label">Width</label>
                                            <input type="number" step="0.01" name="width" value="{{ old('width', $product->width) }}" class="form-control">
                                            @error('width')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Height -->
                                    <div class="col-lg-4">
                                        <div>
                                            <label class="form-label">Height</label>
                                            <input type="number" step="0.01" name="height" value="{{ old('height', $product->height) }}" class="form-control">
                                            @error('height')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Free Shipping -->
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" name="free_shipping" value="1"
                                                {{ old('free_shipping', $product->free_shipping) ? 'checked' : '0' }}>
                                            <label class="form-check-label">
                                                Free Shipping
                                            </label>
                                        </div>
                                    </div>

                                </div><!--end row-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </div>
            </div><!--end col-->
        </div> --}}

        <div class="hstack gap-2 justify-content-end mb-3">
            <a href="{{ route('products.index') }}" class="btn btn-danger">Back</a>
            <button class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script src="{{ asset('admin/js/pages/ecommerce-create-product.init.js') }}"></script>
@stack('scripts')
<script>
    $(document).on('click', '.delete-gallery-image', function() {
        const btn = $(this);
        const url = btn.data('url');
        const id = btn.data('id');
        const card = btn.closest('.col-md-3');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                media_id: id
            },
            success: function() {
                card.fadeOut(300, function() {
                    $(this).remove();
                });
            },
            error: function() {
                alert('Failed to delete image');
            }
        });
    });
</script>

<!-- Media Picker Modals -->
<div class="modal fade" id="mediaPickerModalMain" tabindex="-1" aria-labelledby="mediaPickerModalMainLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaPickerModalMainLabel">Select Main Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="mediaPickerModalMainBody">
                <!-- Media grid will be loaded here -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mediaPickerModalGallery" tabindex="-1" aria-labelledby="mediaPickerModalGalleryLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaPickerModalGalleryLabel">Select Gallery Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="mediaPickerModalGalleryBody">
                <!-- Media grid will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Main image picker
        window.initMediaPicker({
            pickerBtnSelector: '[data-bs-target="#mediaPickerModalMain"]',
            modalBodySelector: '#mediaPickerModalMainBody',
            modalSelector: '#mediaPickerModalMain',
            hiddenInputSelector: '#media_library_main_image_id',
            previewSelector: '#selected-main-image-preview',
            pickerUrl: "{{ route('media-library.picker') }}",
            formSelector: 'form[action*="products.update"]'
        });

        // Gallery picker (multi-select)
        window.initMediaPicker({
            pickerBtnSelector: '[data-bs-target="#mediaPickerModalGallery"]',
            modalBodySelector: '#mediaPickerModalGalleryBody',
            modalSelector: '#mediaPickerModalGallery',
            hiddenInputSelector: '#media_library_gallery_image_ids',
            previewSelector: '#selected-gallery-images-preview',
            pickerUrl: "{{ route('media-library.picker') }}?multi=1",
            formSelector: 'form[action*="products.update"]',
            multi: true,
            onMediaSelected: function(selectedIds) {
                // Handle custom selection logic
                console.log('Media selected from picker:', selectedIds);

                selectedIds.forEach(function(mediaData) {
                    if (mediaData && mediaData.id && mediaData.url) {
                        appendGalleryMedia(String(mediaData.id), mediaData.url);
                    }
                });
            }
        });

        // Helper functions for gallery
        function getGalleryIds() {
            var val = $('#media_library_gallery_image_ids').val() || '';
            if (!val) return [];

            var ids = [];

            // Try parsing as JSON first
            try {
                var parsed = JSON.parse(val);
                if (Array.isArray(parsed)) {
                    ids = parsed.map(String);
                } else {
                    ids = [String(parsed)];
                }
            } catch (e) {
                // Not JSON, parse as CSV
                ids = String(val).split(',');
            }

            return ids.map(function(id) {
                return String(id).replace(/[\[\]"]/g, '').trim();
            }).filter(function(id) {
                return id !== '';
            });

        }

        function setGalleryIds(arr) {
            // Save as CSV format for media picker compatibility
            var csvValue = arr.join(',');
            $('#media_library_gallery_image_ids').val(csvValue);
            console.log('Updated hidden input with IDs (CSV):', csvValue);
        }

        // Display gallery media thumbnail
        function displayGalleryMedia(id, url) {
            if ($('#selected-gallery-images-preview').find('[data-id="' + id + '"]').length > 0) {
                console.log('Image ' + id + ' already displayed, skipping');
                return;
            }
            var $card = $(
                '<div class="position-relative me-2 mb-2" style="width:100px;height:100px;border:1px solid #e9e9e9;border-radius:6px;overflow:hidden" data-id="' +
                id + '">');
            var $img = $('<img>').attr('src', url).css({
                width: '100%',
                height: '100%',
                objectFit: 'cover'
            });
            var $btn = $(
                '<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-gallery-image" data-id="' +
                id + '">✕</button>');
            $card.append($img).append($btn);
            $('#selected-gallery-images-preview').append($card);
            console.log('✓ Displayed gallery image:', id);
        }

        // Add gallery media when selected from picker
        function appendGalleryMedia(id, url) {
            var ids = getGalleryIds();
            // Always attempt to display; UI deduplicates based on DOM,
            // and we only append ID to the hidden field if it's actually new.
            if (ids.indexOf(String(id)) === -1) {
                ids.push(String(id));
                setGalleryIds(ids);
            }
            displayGalleryMedia(id, url);
        }

        // Remove gallery image handler
        $(document).on('click', '.remove-gallery-image', function() {
            var id = $(this).data('id');
            var ids = getGalleryIds().filter(function(i) {
                return i !== String(id);
            });
            setGalleryIds(ids);
            $(this).closest('.position-relative').remove();
        });


        // Load existing gallery images on page load
        function loadExistingGalleryImages() {
            var ids = getGalleryIds();
            console.log('Gallery IDs from hidden input:', ids);

            if (ids && ids.length) {
                ids.forEach(function(id) {
                    // Build URL manually - /admin/media-library/{id}
                    var mediaUrl = '/admin/media-library/' + id;
                    console.log('Loading gallery image from:', mediaUrl);
                    
                    $.ajax({
                        url: mediaUrl,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('✓ Successfully loaded gallery image:', id, response);
                            if (response && response.url) {
                                displayGalleryMedia(id, response.url);
                            }
                        },
                        error: function(err) {
                            console.error('✗ Failed to load gallery image:', id, err);
                        }
                    });
                });
            } else {
                console.log('No gallery IDs found');
            }
        }

        // Load existing main image on page load
        function loadExistingMainImage() {
            var mainId = $('#media_library_main_image_id').val();
            console.log('Main image ID from hidden input:', mainId);

            if (mainId && mainId.trim()) {
                // Build URL manually - /admin/media-library/{id}
                var mediaUrl = '/admin/media-library/' + mainId;
                console.log('Loading main image from:', mediaUrl);
                $.ajax({
                    url: mediaUrl,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('✓ Successfully loaded main image:', mainId, response);
                        if (response && response.url) {
                            $('#selected-main-image-preview').html(
                                `<img src="${response.url}" style="height:100px;width:100px;object-fit:cover;border-radius:4px;">`
                            );
                        }
                    },
                    error: function(err) {
                        console.error('✗ Failed to load main image:', mainId, err);
                    }
                });
            }
        }
        
        // Initialize on page load
        loadExistingGalleryImages();
        loadExistingMainImage();
    });

    document.addEventListener('DOMContentLoaded', function () {

    const elements = document.querySelectorAll('.choices');

    elements.forEach(function(el) {
        new Choices(el, {
            removeItemButton: true,
            searchEnabled: true,
            shouldSort: false,
        });
    });

});
</script>

<x-admin.footer />

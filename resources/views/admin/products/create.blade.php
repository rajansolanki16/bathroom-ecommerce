<x-admin.header :title="'Product Create'" />
<div class="container-fluid">
    <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4">
                                <h5 class="card-title mb-3">Product Information</h5>
                                <p class="text-muted">Product Information refers to any information held by an organisation about the products it produces, buys, sells or distributes.</p>
                            </div>
                            <div class="col-xxl-8">
                                <div class="mb-3">
                                    <label for="productTitle" class="form-label">Product Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" id="productTitle"
                                        value="{{ old('title') }}" placeholder="Enter product title">
                                    @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="productType" class="form-label">Product Type <span class="text-danger">*</span></label>
                                    <select class="form-control"
                                        name="product_type"
                                        id="productType">
                                        <option value="">Select Type</option>
                                        @foreach ($productTypes as $type)
                                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="productCategories" class="form-label">
                                        Categories
                                    </label>

                                    <select class="form-control"
                                        name="categories[]"
                                        id="productCategories"
                                        multiple>
                                        @forelse ($categories as $parent)
                                            <optgroup label="{{ $parent->name }}">
                                                <option value="{{ $parent->id }}" {{ collect(old('categories'))->contains($parent->id) ? 'selected' : '' }}>{{ $parent->name }}</option>
                                                @foreach($parent->children as $child)
                                                    <option value="{{ $child->id }}" {{ collect(old('categories'))->contains($child->id) ? 'selected' : '' }}>— {{ $child->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @empty
                                        <option disabled>No categories found</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="shortDecs" class="form-label">Short Description <span class="text-danger">*</span> </label>
                                    <textarea class="form-control" name="short_description" id="shortDecs" rows="3">
                                    {{ old('short_description') }}
                                    </textarea>
                                    @error('short_description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <!-- SKU -->
                                    <div class="col-lg-6">
                                        <div>
                                            <label class="form-label">SKU Number</label>
                                            <input type="text"
                                                name="sku_number"
                                                value="{{ old('sku_number') }}"
                                                class="form-control"
                                                placeholder="SKU-ABC-001">
                                            @error('sku_number')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div>
                                            <label class="form-label" for="product-price-input">Price <span class="text-danger">*</span></label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="product-price-addon">$</span>
                                                <input type="number" step="0.01" name="price" class="form-control">
                                                <div class="invalid-feedback">Please Enter a product price.</div>
                                            </div>
                                        </div>
                                        @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div><!--end col-->

                                    <div class="col-lg-6">
                                        <label class="form-label">Tags</label>
                                        <select id="productTags" name="tags[]" class="form-control" multiple>
                                            @foreach ($allTags as $tag)
                                            <option value="{{ $tag->id }}" {{ (collect(old('tags'))->contains($tag->id) || (isset($product) && $product->tags->contains($tag->id))) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-lg-6">
                                        <label class="form-label">Brands</label>
                                        <select id="productBrands" name="brand_id" class="form-control" >
                                          @foreach( $allbrands as $brand)
                                            <option value="{{ $brand->id }}" {{ (collect(old('brands'))->contains($brand->id) || (isset($product) && $product->brands->contains($brand->id))) ? 'selected' : '' }}>{{ $brand->name }}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="productStatus" class="form-label">Status</label>
                                            <select class="form-control"
                                                name="status"
                                                id="productStatus">
                                                <option value="">Select Status</option>

                                                @foreach ($productStatuses as $status)
                                                <option value="{{ $status->value }}"
                                                    {{ old('status') === $status->value ? 'selected' : '' }}>
                                                    {{ $status->label() }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6">
                                            <div>
                                                <label for="productVisibility" class="form-label">Visibility</label>
                                                <select class="form-control" name="visibility" id="productVisibility">
                                                    <option value="">Select Visibility</option>

                                                    @foreach ($productVisibilities as $visibility)
                                                    <option value="{{ $visibility->value }}"
                                                        {{ old('visibility') === $visibility->value ? 'selected' : '' }}>
                                                        {{ $visibility->label() }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('visibility')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div><!--end col-->
                                    </div>

                                </div><!--end col-->
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
                                    <h5 class="card-title mb-3">Description</h5>
                                    <p class="text-muted">Product Information refers to any information held by an organization about the products it produces, buys, sells or distributes.</p>
                                </div><!--end col-->
                                <div class="col-xxl-8">
                                    <div>
                                        <label class="form-label">Product Description <span class="text-danger">*</span></label>
                                        <textarea class="ckeditor-classic" name="product_decscription" id="productDescription" rows="5">{{ old('product_decscription') }}</textarea>
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
                                    <p class="text-muted">Add SEO meta tags to help search engines understand your product better.</p>
                                </div><!--end col-->
                                <div class="col-xxl-8">
                                    <div class="mb-3">
                                        <label for="metaTitle" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                            name="meta_title" id="metaTitle"
                                            value="{{ old('meta_title') }}"
                                            placeholder="Enter meta title (max 160 characters)"
                                            maxlength="160">
                                        <small class="text-muted">Recommended: 50-60 characters</small>
                                        @error('meta_title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="metaDescription" class="form-label">Meta Description</label>
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                            name="meta_description" id="metaDescription"
                                            rows="3"
                                            placeholder="Enter meta description (max 160 characters)"
                                            maxlength="160">{{ old('meta_description') }}</textarea>
                                        <small class="text-muted">Recommended: 150-160 characters</small>
                                        @error('meta_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="metaKeywords" class="form-label">Meta Keywords</label>
                                        <textarea class="form-control @error('meta_keywords') is-invalid @enderror"
                                            name="meta_keywords" id="metaKeywords"
                                            rows="3"
                                            placeholder="Enter meta keywords separated by comma">{{ old('meta_keywords') }}</textarea>
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-4">
                                    <h5 class="card-title mb-3">Images</h5>
                                    <p class="text-muted">Product Information refers to any information held by an organization about the products it produces, buys, sells or distributes.</p>
                                </div><!--end col-->
                                <div class="col-xxl-8">
                                    <div class="mb-4">
                                        <label class="form-label">Product Image <span class="text-danger">*</span></label>

                                        <div class="border rounded p-3 text-center">
                                            <img id="productImagePreview"
                                                src="{{ asset('admin/images/new-document.png') }}"
                                                class="img-thumbnail mb-3"
                                                style="max-height: 180px">

                                            <input type="file"
                                                name="product_image"
                                                class="form-control"
                                                accept="image/*"
                                                onchange="previewSingleImage(event)">
                                        </div>

                                        @error('product_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Gallery Images </label>

                                        <input type="file"
                                            name="gallery_images[]"
                                            class="form-control"
                                            multiple
                                            accept="image/*"
                                            onchange="previewMultipleImages(event)">

                                        <div class="row mt-3" id="galleryPreview"></div>

                                        @error('gallery_images')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div><!--end row-->
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row" id="vec_general_Info_Section" style="display: none">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-4">
                                    <h5 class="card-title mb-3">General Info</h5>
                                    <p class="text-muted mb-0">An informational product can be a digital book (or e-book), a digital report, a white paper, a piece of software, audio or video files, a website, an e-zine or a newsletter.</p>
                                </div><!--end col-->
                                <div class="col-xxl-8">
                                    <div class="row gy-3">
                                        <div class="col-lg-4">
                                            <div>
                                                <label class="form-label">Stock Status <span class="text-danger">*</span></label>
                                                <select name="stock_status"
                                                    class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="1" {{ old('stock_status') === 'instock' ? 'selected' : '' }}>
                                                        In Stock
                                                    </option>
                                                    <option value="0" {{ old('stock_status') === 'outstock' ? 'selected' : '' }}>
                                                        Out of Stock
                                                    </option>
                                                </select>

                                                @error('stock_status')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div>
                                                <label class="form-label" for="product-discount-input">Discount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="product-discount-addon">%</span>
                                                    <input type="number" step="0.01" name="discount" class="form-control">
                                                </div>
                                            </div>
                                            @error('discount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div><!--end col-->

                                        <div class="col-lg-4">
                                            <div>
                                                <label class="form-label">Sell Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number"
                                                        step="0.01"
                                                        name="sell_price"
                                                        value="{{ old('sell_price') }}"
                                                        class="form-control"
                                                        placeholder="Special price">
                                                </div>
                                                @error('sell_price')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-lg-4">
                                            <div>
                                                <label class="form-label">Sell Price Start Date</label>
                                                <input type="date"
                                                    name="sell_price_start_date"
                                                    value="{{ old('sell_price_start_date') }}"
                                                    class="form-control">
                                                @error('sell_price_start_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div>
                                                <label class="form-label">Sell Price End Date</label>
                                                <input type="date"
                                                    name="sell_price_end_date"
                                                    value="{{ old('sell_price_end_date') }}"
                                                    class="form-control">
                                                @error('sell_price_end_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Info Note -->
                                        <div class="col-lg-12">
                                            <div class="alert alert-info mb-0">
                                                <i class="ph-info me-1"></i>
                                                Sell price will override the regular price during the selected date range.
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check form-switch mb-3">
                                                <input type="checkbox" name="exchangeable" value="1" class="form-check-input">
                                                <label class="form-check-label" for="exchangeableInput">Exchangeable</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check form-switch mb-3">
                                                <input type="checkbox" name="refundable" value="1" class="form-check-input">
                                                <label class="form-check-label" for="refundableInput">Refundable</label>
                                            </div>
                                        </div>

                                    </div><!--end row-->
                                </div>
                            </div><!--end row-->
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <!-- Shipping Info -->
            <div class="row" id="vec_shipping_section">
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
                                                <input type="number"
                                                    step="0.01"
                                                    name="weight"
                                                    value="{{ old('weight') }}"
                                                    class="form-control"
                                                    placeholder="e.g. 1.5">
                                                @error('weight')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Length -->
                                        <div class="col-lg-4">
                                            <div>
                                                <label class="form-label">Length</label>
                                                <input type="number"
                                                    step="0.01"
                                                    name="length"
                                                    value="{{ old('length') }}"
                                                    class="form-control"
                                                    placeholder="e.g. 30">
                                                @error('length')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Width -->
                                        <div class="col-lg-4">
                                            <div>
                                                <label class="form-label">Width</label>
                                                <input type="number"
                                                    step="0.01"
                                                    name="width"
                                                    value="{{ old('width') }}"
                                                    class="form-control"
                                                    placeholder="e.g. 20">
                                                @error('width')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Height -->
                                        <div class="col-lg-4">
                                            <div>
                                                <label class="form-label">Height</label>
                                                <input type="number"
                                                    step="0.01"
                                                    name="height"
                                                    value="{{ old('height') }}"
                                                    class="form-control"
                                                    placeholder="e.g. 10">
                                                @error('height')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Free Shipping -->
                                        <div class="col-lg-8 d-flex align-items-center">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="free_shipping"
                                                    value="1"
                                                    {{ old('free_shipping') ? 'checked' : '' }}>
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
            </div><!--end row-->

            {{-- Variant Section --}}
            <div class="row" id="vec_variantSection" style="display:none;">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-xxl-4">
                                    <h5 class="card-title mb-3">Product Variants</h5>
                                    <p class="text-muted">
                                        Select one or more attributes and create variants (combinations of chosen values).
                                    </p>
                                </div>

                                <div class="col-xxl-8">
                                    {{-- Attribute Type --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Variant Attributes</label>
                                        <select class="form-control" id="variantAttributesSelect" name="product_attributes[]" multiple>
                                            @forelse($attributes as $attribute)
                                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                            @empty
                                            <option disabled>No attributes found</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <div id="attributeValuesContainers" class="mb-3"></div>

                                    <button type="button" class="btn btn-outline-primary mb-4" id="generateVariants">
                                        <i class="ph-plus"></i> Generate Variants
                                    </button>

                                    <div id="variantsList" class="mt-4">
                                        <!-- Variants will be rendered here -->
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- End Variant Section --}}

            <div class="hstack gap-2 justify-content-end mb-3">
                <a href="{{ route('products.index') }}" class="btn btn-danger">Back</a>
                <button class="btn btn-primary">Submit</button>
            </div>

    </form>
</div>
<script>
    window.attributesData = {
        !!$attributesJson!!
    };
    $(document).ready(function() {

        const $attrSelect = $('#variantAttributesSelect');
        const $containers = $('#attributeValuesContainers');
        const $generateBtn = $('#generateVariants');
        const $variantsList = $('#variantsList');

        /* --------------------------------------------
         * Create multi-select for attribute values
         * -------------------------------------------- */
        function createValuesMultiSelect(attribute) {
            const $wrapper = $('<div class="mb-3">');

            $('<label class="form-label fw-semibold">')
                .text(attribute.name + ' values')
                .appendTo($wrapper);

            const $select = $('<select class="form-control" multiple>')
                .attr('data-attribute-id', attribute.id);

            attribute.values.forEach(v => {
                $('<option>', {
                    value: v.id,
                    text: v.value
                }).appendTo($select);
            });

            $wrapper.append($select);

            // Choices.js (safe)
            if (window.Choices) {
                setTimeout(() => {
                    try {
                        new Choices($select[0], {
                            searchEnabled: true,
                            removeItemButton: true,
                            shouldSort: false,
                            placeholderValue: 'Select values',
                            itemSelectText: ''
                        });
                    } catch (e) {}
                }, 30);
            }

            return $wrapper;
        }

        /* --------------------------------------------
         * When attributes change
         * -------------------------------------------- */
        function onAttributesChange() {
            $containers.empty();

            const selected = $attrSelect.val() || [];

            selected.forEach(attrId => {
                const attribute = window.attributesData.find(
                    a => String(a.id) === String(attrId)
                );

                if (attribute) {
                    $containers.append(createValuesMultiSelect(attribute));
                }
            });
        }

        /* --------------------------------------------
         * Generate variants (AJAX)
         * -------------------------------------------- */
        function generateVariants() {
            const attributes = [];
            let hasError = false;

            $containers.find('select').each(function() {
                const $select = $(this);
                const attributeId = $select.data('attribute-id');
                const selectedValues = $select.val();

                if (!selectedValues || !selectedValues.length) {
                    alert('Select at least one value for each attribute');
                    hasError = true;
                    return false;
                }

                const valuesMap = {};
                $select.find('option').each(function() {
                    valuesMap[this.value] = this.text;
                });

                attributes.push({
                    attribute_id: attributeId,
                    values: selectedValues,
                    values_map: valuesMap
                });
            });

            if (hasError || !attributes.length) return;

            $.ajax({
                url: "{{ route('products.generate.variants') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    attributes: attributes
                },
                success: function(res) {
                    $('#variantsList').html(res.html);
                    $('#vec_variantSection').show();

                },
                error: function() {
                    alert('Failed to generate variants');
                }
            });
        }

        /* --------------------------------------------
         * Events
         * -------------------------------------------- */
        $attrSelect.on('change', onAttributesChange);
        $generateBtn.on('click', generateVariants);


        /* --------------------------------------------
         * Variant UI handlers (create page only)
         * -------------------------------------------- */
        // Generate SKU
        $(document).on('click', '.generate-sku', function() {
            const $card = $(this).closest('.variant-card');
            const $sku = $card.find('.variant-sku');
            const base = $('input[name="title"]').val() || 'PRD';
            const rand = Math.random().toString(36).substring(2, 7).toUpperCase();
            const sku = base.replace(/\s+/g, '-').toUpperCase().substring(0, 6) + '-' + rand;
            $sku.val(sku);
            $card.find('.meta-sku').text(sku);
        });

        // Remove variant card
        $(document).on('click', '.btn-remove-variant', function() {
            if (!confirm('Remove this variant?')) return;
            $(this).closest('.variant-card').remove();
        });

        // Image preview per variant
        $(document).on('change', '.variant-image-input', function(e) {
            const $input = $(this);
            const $card = $input.closest('.variant-card');
            const $preview = $card.find('.variant-image-preview');
            const file = this.files && this.files[0];
            if (!file) {
                $preview.hide();
                return;
            }
            const reader = new FileReader();
            reader.onload = function(ev) {
                $preview.attr('src', ev.target.result).show();
            };
            reader.readAsDataURL(file);
        });

        // Live update header meta
        $(document).on('input change', '.variant-price, .variant-stock, .variant-sku', function() {
            const $card = $(this).closest('.variant-card');
            const price = $card.find('.variant-price').val() || '0';
            const stock = $card.find('.variant-stock').val() || '0';
            const sku = $card.find('.variant-sku').val() || '';
            $card.find('.meta-price').text(price);
            $card.find('.meta-stock').text(stock);
            $card.find('.meta-sku').text(sku);
        });

        $(document).on('click', '.variant-card .card-header', function() {
            const $card = $(this).closest('.variant-card');
            $card.toggleClass('open');
            $card.find('.card-body').slideToggle();
        });

    });
</script>
<script src="{{ asset('admin/js/pages/ecommerce-create-product.init.js') }}"></script>
<x-admin.footer />
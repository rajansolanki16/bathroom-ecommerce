<x-admin.header :title="'Product'" />
<div class="container-fluid">
    <form id="productForm" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $product->product_title) }}">
                                    @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="productType" class="form-label">Product Type <span class="text-danger">*</span></label>
                                    <select name="product_type" id="productType" class="form-control">
                                        @foreach ($productTypes as $type)
                                        <option value="{{ $type->value }}"
                                            {{ old('product_type', $product->product_type) == $type->value ? 'selected' : '' }}>
                                            {{ $type->label() }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="productCategories" class="form-label">
                                        Categories
                                    </label>

                                    <select class="form-control" name="categories[]" id="productCategories" multiple>
                                        @foreach ($categories as $parent)
                                            <optgroup label="{{ $parent->name }}">
                                                <option value="{{ $parent->id }}" {{ in_array($parent->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $parent->name }}</option>
                                                @foreach($parent->children as $child)
                                                    <option value="{{ $child->id }}" {{ in_array($child->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>— {{ $child->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <label for="shortDecs" class="form-label">Short Description
                                        <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                        @error('short_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                </div>
                                <div class="row">
                                    <!-- <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="productBrand" class="form-label">Brand <span class="text-danger">*</span></label>
                                                    <input type="text" name="brand" class="form-control" id="productBrand" value="{{ old('brand', $product->brand) }}">
                                                    @error('short_description')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div> -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Brand</label>
                                            <select id="productBrands" name="brand_id" class="form-control">
                                                <option value="">Select Brand</option>

                                                @foreach($allbrands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ isset($product) && $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('tags')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tags</label>
                                        <select class="form-control"
                                            name="tags[]"
                                            id="productTags"
                                            multiple>
                                            @foreach ($allTags as $tag)
                                            <option value="{{ $tag->id }}"
                                                {{ isset($product) && $product->tags->contains($tag->id) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('tags')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">SKU Number</label>
                                    <input type="text"
                                        name="sku_number"
                                        class="form-control"
                                        value="{{ old('sku_number', $product->sku_number) }}"
                                        placeholder="SKU-ABC-001">
                                    @error('sku_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="productStatus" class="form-label">Status</label>
                                    <select name="status" class="form-control" id="vec_productStatus">
                                        @foreach ($productStatuses as $status)
                                        <option value="{{ $status->value }}"
                                            {{ old('status', $product->status) == $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="productVisibility" class="form-label">Visibility</label>
                                    <select name="visibility" class="form-control" id="vec_visibility">
                                        @foreach ($productVisibilities as $visibility)
                                        <option value="{{ $visibility->value }}"
                                            {{ old('visibility', $product->visibility) == $visibility->value ? 'selected' : '' }}>
                                            {{ $visibility->label() }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('visibility')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
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
                        <p class="text-muted">Product Information refers to any information held by an organization about the products it produces, buys, sells or distributes.</p>
                    </div><!--end col-->
                    <div class="col-xxl-8">
                        <div class="mb-4">
                            <label class="form-label">Product Image <span class="text-danger">*</span></label>

                            <div class="border rounded p-3 text-center">
                                <img id="productImagePreview"
                                    src="{{ 
                                        $product->getFirstMediaUrl('main_image') 
                                            ?: asset('admin/images/new-document.png') 
                                    }}"
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
                            <label class="form-label fw-semibold">
                                Gallery Images
                            </label>

                            <input type="file"
                                name="gallery_images[]"
                                class="form-control"
                                multiple
                                accept="image/*"
                                onchange="previewMultipleImages(event)">

                            <div class="row mt-3" id="galleryPreview">
                                @foreach ($product->getMedia('gallery') as $media)
                                    <div class="col-md-3 mb-3">
                                        <div class="card shadow-sm position-relative">
                                            <img src="{{ $media->getUrl() }}"
                                                class="card-img-top"
                                                style="height:150px; object-fit:cover">

                                            <!-- Optional delete button -->
                                            <button type="button"
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-gallery-image" 
                                                  data-url="{{ route('media.delete', $media->id) }}">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

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
                                value="{{ old('meta_title', $product->meta_title) }}"
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
                                maxlength="160">{{ old('meta_description', $product->meta_description) }}</textarea>
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
                                placeholder="Enter meta keywords separated by comma">{{ old('meta_keywords', $product->meta_keywords) }}</textarea>
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
                        <p class="text-muted mb-0">An informational product can be a digital book (or e-book), a digital report, a white paper, a piece of software, audio or video files, a website, an e-zine or a newsletter.</p>
                    </div><!--end col-->
                    <div class="col-xxl-8">
                        <div class="row gy-3">
                            <div class="col-lg-4">
                                <label class="form-label">
                                    Stock <span class="text-danger">*</span>
                                </label>

                                <select name="stock" class="form-control">
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
                                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}">
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
                                        <input type="number" step="0.01" name="discount" class="form-control" value="{{ old('discount', $product->discount) }}">
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
                                    <input type="number"
                                        step="0.01"
                                        name="sell_price"
                                        class="form-control"
                                        value="{{ old('sell_price', $product->sell_price) }}">
                                </div>
                                @error('sell_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Sell Price Start Date</label>
                                <input type="date"
                                    name="sell_price_start_date"
                                    class="form-control"
                                    value="{{ old('sell_price_start_date', optional($product->sell_price_start_date)->format('Y-m-d')) }}">
                                @error('sell_price_start_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Sell Price End Date</label>
                                <input type="date"
                                    name="sell_price_end_date"
                                    class="form-control"
                                    value="{{ old('sell_price_end_date', optional($product->sell_price_end_date)->format('Y-m-d')) }}">
                                @error('sell_price_end_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row gy-2">
                                <div class="col-lg-6">
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" name="exchangeable" value="1" class="form-check-input" {{ old('exchangeable', $product->exchangeable) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="exchangeableInput">Exchangeable</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" name="refundable" value="1" class="form-check-input" {{ old('refundable', $product->refundable) ? 'checked' : '' }}>
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

<div class="row" class="vec_shipping_section" id="vec_shipping_section">
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
</div><!--end row-->

<!-- Variants Section (Only for product_type=VARIANTS) -->
<div id="variantsSection" class="row" style="display: none;">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xxl-4">
                        <h5 class="card-title mb-3">Product Variants</h5>
                        <p class="text-muted">Manage product variants with different attribute values.</p>
                    </div>
                    <div class="col-xxl-8">
                        <!-- Attribute Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Select Attributes</label>
                            <select id="variantAttributesSelect" class="form-control" multiple>
                                @foreach ($attributes as $attr)
                                <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Attribute Values Selection -->
                        <div id="attributeValuesContainers" class="mb-4"></div>

                        <!-- Generate Button -->
                        <button type="button" id="generateVariants" class="btn btn-secondary mb-4">
                            <i class="ph-plus me-2"></i>Generate Variants
                        </button>

                        <!-- Variants List -->
                        <div id="variantsList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->

<div class="hstack gap-2 justify-content-end mb-3">
    <a href="{{ route('products.index') }}" class="btn btn-danger">Back</a>
    <button class="btn btn-primary">Submit</button>
</div>
</form>
</div>
<script>
    window.attributesData = {
        !!$attributesJson ?? '[]'!!
    };
    window.errors = {
        !!json_encode($errors - > getMessages() ?? []) !!
    };
    window.variantsStore = {
        !!$variantsJson ?? '[]'!!
    };

    $(document).ready(function() {
        const $attrSelect = $('#variantAttributesSelect');
        const $containers = $('#attributeValuesContainers');
        const $generateBtn = $('#generateVariants');
        const $variantsList = $('#variantsList');
        const $variantsSection = $('#variantsSection');
        const $productTypeSelect = $('#productType');

        function createValuesMultiSelect(attribute) {
            const $wrapper = $('<div>').addClass('mb-3');
            const $label = $('<label>').addClass('form-label fw-semibold').text(attribute.name + ' values');
            $wrapper.append($label);

            const $select = $('<select>')
                .addClass('form-control')
                .attr('multiple', true)
                .data('attributeId', attribute.id);

            attribute.values.forEach(v => {
                $('<option>').val(v.id).text(v.value).appendTo($select);
            });

            $wrapper.append($select);

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
                    } catch (e) {
                        /* ignore */
                    }
                }, 30);
            }
            return $wrapper;
        }

        function getExistingAttributesFromVariants() {
            const attrs = new Set();
            window.variantsStore.forEach(v => {
                (v.values || []).forEach(val => attrs.add(val));
            });
            return Array.from(attrs);
        }

        function onAttributesChange() {
            $containers.empty();
            const selected = $attrSelect.val() || [];
            selected.forEach(id => {
                const attribute = window.attributesData.find(a => String(a.id) === String(id));
                if (attribute) {
                    $containers.append(createValuesMultiSelect(attribute));
                }
            });
        }

        function cartesian(arrays) {
            return arrays.reduce((a, b) => a.flatMap(d => b.map(e => d.concat([e]))), [
                []
            ]);
        }

        function getFieldError(idx, fieldName) {
            const errorKey = `variants.${idx}.${fieldName}`;
            return window.errors[errorKey] ? window.errors[errorKey][0] : null;
        }

        function renderFieldGroup(label, fieldClass, fieldName, inputType = 'text', colClass = 'col-md-6') {
            let html = `<div class="${colClass} mb-3"><label class="form-label">${label}</label>`;
            if (inputType === 'textarea') {
                html += `<textarea class="form-control ${fieldClass}" rows="3" data-field="${fieldName}"></textarea>`;
            } else {
                const step = inputType === 'number' ? '0.01' : 'any';
                const accept = inputType === 'file' ? 'accept="image/*"' : '';
                html += `<input type="${inputType}" step="${step}" class="form-control ${fieldClass}" value="" data-field="${fieldName}" ${accept}>`;
            }
            html += '<div class="invalid-feedback d-block" style="display: none;"></div></div>';
            return html;
        }

        function renderTable() {
            $variantsList.empty();

            window.variantsStore.forEach((variant, idx) => {
                const $card = $('<div>').addClass('card mb-3 border-start border-start-3 border-primary');

                const $header = $('<div>')
                    .addClass('card-header bg-light d-flex justify-content-between align-items-center')
                    .css('cursor', 'pointer')
                    .html(`
                            <div>
                                <h6 class="mb-0"><strong>${variant.name}</strong></h6>
                                <small class="text-muted">SKU: ${variant.sku || '-'} | Price: $${variant.price || '-'} | Stock: ${variant.stock || 0}</small>
                            </div>
                            <div><i class="ph-caret-down" style="transition: transform 0.3s;"></i></div>
                        `);

                const $body = $('<div>').addClass('card-body').hide();

                // Basic Info
                const basicHtml = `
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="mb-3 fw-semibold">Basic Info</h6>
                            <div class="row">
                                ${renderFieldGroup('SKU', 'variant-sku', 'sku', 'text', 'col-md-6')}
                                ${renderFieldGroup('Price', 'variant-price', 'price', 'number', 'col-md-6')}
                            </div>
                            <div class="row">
                                ${renderFieldGroup('Stock', 'variant-stock', 'stock', 'number', 'col-md-6')}
                                ${renderFieldGroup('Sell Price', 'variant-sell-price', 'sell_price', 'number', 'col-md-6')}
                            </div>
                        </div>
                    `;

                // Shipping Info
                const shippingHtml = `
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="mb-3 fw-semibold">Shipping Info</h6>
                            <div class="row">
                                ${renderFieldGroup('Shipping Cost', 'variant-shipping', 'shipping', 'text', 'col-md-6')}
                                ${renderFieldGroup('Shipping Address', 'variant-shipping-addr', 'shipping_address', 'text', 'col-md-6')}
                            </div>
                            <div class="row">
                                ${renderFieldGroup('Weight', 'variant-weight', 'weight', 'number', 'col-md-3')}
                                ${renderFieldGroup('Length', 'variant-length', 'length', 'number', 'col-md-3')}
                                ${renderFieldGroup('Width', 'variant-width', 'width', 'number', 'col-md-3')}
                                ${renderFieldGroup('Height', 'variant-height', 'height', 'number', 'col-md-3')}
                            </div>
                           
                        </div>
                    `;

                // General Info
                const generalHtml = `
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="mb-3 fw-semibold">General Info</h6>
                            ${renderFieldGroup('', 'variant-general-info', 'general_info', 'textarea', 'col-12')}
                        </div>
                    `;

                // Image
                const imageHtml = `
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="mb-3 fw-semibold">Image</h6>
                            ${renderFieldGroup('', 'variant-image', 'image', 'file', 'col-12')}
                        </div>
                    `;

                // Flags
                const flagsHtml = `
                        <div class="mb-3">
                            <h6 class="mb-3 fw-semibold">Options</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input variant-exchangeable" ${variant.exchangeable ? 'checked' : ''} data-idx="${idx}">
                                        <label class="form-check-label">Exchangeable</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input variant-refundable" ${variant.refundable ? 'checked' : ''} data-idx="${idx}">
                                        <label class="form-check-label">Refundable</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input variant-free-shipping" ${variant.free_shipping ? 'checked' : ''} data-idx="${idx}">
                                        <label class="form-check-label">Free Shipping</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                $body.html(basicHtml + shippingHtml + generalHtml + imageHtml + flagsHtml);

                const $footer = $('<div>')
                    .addClass('card-footer bg-light')
                    .html(`
                            <button type="button" class="btn btn-sm btn-success me-2 save-variants-btn">
                                Save Variants
                            </button>

                            <button type="button" class="btn btn-sm btn-danger delete-variant-btn"  data-product-id="{{ $product->id }}" data-id="${variant.id ?? ''}" data-idx="${idx}">
                                <i class="ph-trash me-1"></i> Delete Variant
                            </button>
                        `);

                $card.append($header, $body, $footer);
                $variantsList.append($card);

                // Populate field values and display errors
                const fieldMap = {
                    'variant-sku': 'sku',
                    'variant-price': 'price',
                    'variant-stock': 'stock',
                    'variant-sell-price': 'sell_price',
                    'variant-shipping': 'shipping',
                    'variant-shipping-addr': 'shipping_address',
                    'variant-weight': 'weight',
                    'variant-length': 'length',
                    'variant-width': 'width',
                    'variant-height': 'height',
                    'variant-general-info': 'general_info'
                };

                Object.entries(fieldMap).forEach(([className, fieldName]) => {
                    const $input = $body.find(`.${className}`);
                    if ($input.length) {
                        $input.val(variant[fieldName] || '').data('idx', idx).on('change', updateVariant);

                        const error = getFieldError(idx, fieldName);
                        if (error) {
                            $input.addClass('is-invalid');
                            $input.closest('div').find('.invalid-feedback').text(error).show();
                        }
                    }
                });

                // File input
                const $imageInput = $body.find('.variant-image');
                if ($imageInput.length) {
                    $imageInput.data('idx', idx).on('change', updateVariant);
                    const imageError = getFieldError(idx, 'image');
                    if (imageError) {
                        $imageInput.addClass('is-invalid');
                        $imageInput.closest('div').find('.invalid-feedback').text(imageError).show();
                    }
                }

                // Toggle expand/collapse
                $header.on('click', function() {
                    const isVisible = $body.is(':visible');
                    $body.toggle();
                    $(this).find('i').css('transform', isVisible ? 'rotate(0deg)' : 'rotate(180deg)');
                });
            });

            // Attach event listeners
            $variantsList.on('change', '.variant-exchangeable, .variant-refundable, .variant-free-shipping', updateVariantCheckbox);
            $variantsList.on('click', '.delete-variant-btn', deleteVariant);
        }

        function updateVariant(e) {
            const idx = $(this).data('idx');
            const variant = window.variantsStore[idx];
            const $this = $(this);

            if ($this.hasClass('variant-sku')) variant.sku = $this.val();
            else if ($this.hasClass('variant-price')) variant.price = $this.val();
            else if ($this.hasClass('variant-stock')) variant.stock = $this.val();
            else if ($this.hasClass('variant-sell-price')) variant.sell_price = $this.val();
            else if ($this.hasClass('variant-shipping')) variant.shipping = $this.val();
            else if ($this.hasClass('variant-shipping-addr')) variant.shipping_address = $this.val();
            else if ($this.hasClass('variant-weight')) variant.weight = $this.val();
            else if ($this.hasClass('variant-length')) variant.length = $this.val();
            else if ($this.hasClass('variant-width')) variant.width = $this.val();
            else if ($this.hasClass('variant-height')) variant.height = $this.val();
            else if ($this.hasClass('variant-general-info')) variant.general_info = $this.val();
            else if ($this.hasClass('variant-image')) variant.image = $this[0].files[0] || null;

            // Clear error styling
            $this.removeClass('is-invalid').closest('div').find('.invalid-feedback').hide();
        }

        function updateVariantCheckbox(e) {
            const idx = $(this).data('idx');
            const variant = window.variantsStore[idx];
            const $this = $(this);

            if ($this.hasClass('variant-exchangeable')) variant.exchangeable = $this.is(':checked') ? 1 : 0;
            else if ($this.hasClass('variant-refundable')) variant.refundable = $this.is(':checked') ? 1 : 0;
            else if ($this.hasClass('variant-free-shipping')) variant.free_shipping = $this.is(':checked') ? 1 : 0;
        }

        function deleteVariant() {
            const $btn = $(this);
            const idx = $btn.data('idx');
            const variantId = $btn.data('id');
            const productId = $btn.data('product-id');

            // Variant exists in DB
            if (variantId) {
                $.ajax({
                    url: "{{ route('products.variants.remove', $product->id) }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        variant_id: variantId
                    },
                    beforeSend() {
                        $btn.prop('disabled', true);
                    },
                    success() {
                        window.variantsStore.splice(idx, 1);
                        renderTable();

                        // optional toast (no alert)
                        if (window.Toastify) {
                            Toastify({
                                text: "Variant deleted",
                                duration: 2000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#dc3545"
                            }).showToast();
                        }
                    },
                    error() {
                        $btn.prop('disabled', false);
                    }
                });
            }
            // Not saved yet → frontend only
            else {
                window.variantsStore.splice(idx, 1);
                renderTable();
            }
        }

        function generateTable() {
            const $selects = $containers.find('select');
            if (!$selects.length) return alert('Select attributes and values first');

            const valueLists = $selects.toArray().map(s => {
                return Array.from($(s).find('option:selected')).map(o => ({
                    id: o.value,
                    text: o.textContent
                }));
            });

            if (valueLists.some(l => l.length === 0)) {
                return alert('Choose at least one value for each attribute');
            }

            const combos = cartesian(valueLists);

            const existingVariants = window.variantsStore;
            const newVariants = [];

            combos.forEach(combo => {
                const comboIds = combo.map(c => c.id).sort().join('-');

                const found = existingVariants.find(v =>
                    v.values.slice().sort().join('-') === comboIds
                );

                if (found) {
                    newVariants.push(found); // keep existing
                } else {
                    newVariants.push({
                        name: combo.map(c => c.text).join(' / '),
                        values: combo.map(c => c.id),
                        sku: '',
                        price: '',
                        stock: 0,
                        sell_price: '',
                        shipping: '',
                        shipping_address: '',
                        general_info: '',
                        weight: '',
                        length: '',
                        width: '',
                        height: '',
                        exchangeable: 0,
                        refundable: 0,
                        free_shipping: 0,
                        image: null
                    });
                }
            });

            window.variantsStore = newVariants;
            renderTable();
        }
        // Show/hide variants section based on product type
        if ($productTypeSelect.length) {
            function toggleVariantsSection() {
                const isVariants = $productTypeSelect.val() === '1' || $productTypeSelect.val() == 1;
                $variantsSection.toggle(isVariants);

                // If editing a variants product and variantsStore has data, render existing variants
                if (isVariants && window.variantsStore && window.variantsStore.length > 0 && $variantsList.is(':empty')) {
                    renderTable();
                }
            }

            $productTypeSelect.on('change', toggleVariantsSection);
            toggleVariantsSection();
        } else if (window.variantsStore && window.variantsStore.length > 0) {
            renderTable();
        }

        $attrSelect.on('change', onAttributesChange);
        $generateBtn.on('click', generateTable);
        $('#productForm').on('submit', function(e) {
            if (window.variantsStore && window.variantsStore.length > 0) {
                const $variantsContainer = $('<div>').hide();

                window.variantsStore.forEach((variant, idx) => {
                    Object.keys(variant).forEach(key => {
                        if (key === 'name' || key === 'image') return;

                        if (key === 'values') {
                            variant.values.forEach(val => {
                                $('<input>')
                                    .attr({
                                        type: 'hidden',
                                        name: `variants[${idx}][values][]`,
                                        value: val
                                    })
                                    .appendTo($variantsContainer);
                            });
                        } else {
                            $('<input>')
                                .attr({
                                    type: 'hidden',
                                    name: `variants[${idx}][${key}]`,
                                    value: variant[key]
                                })
                                .appendTo($variantsContainer);
                        }
                    });
                });

                $(this).append($variantsContainer);
            }
        });
    });

    $(document).on('click', '.save-variants-btn', function() {

        let formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");

        window.variantsStore.forEach((variant, idx) => {
            Object.keys(variant).forEach(key => {
                if (key === 'image' && variant.image) {
                    formData.append(`variants[${idx}][image]`, variant.image);
                } else if (Array.isArray(variant[key])) {
                    variant[key].forEach(v =>
                        formData.append(`variants[${idx}][${key}][]`, v)
                    );
                } else {
                    formData.append(`variants[${idx}][${key}]`, variant[key]);
                }
            });
        });

        $.ajax({
            url: "{{ route('products.variants.update', $product->id) }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(res) {
                Toastify({
                    text: res.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: res.success ? "#16a34a" : "#dc2626",
                }).showToast();
            },

            error: function() {
                Toastify({
                    text: "Failed to save variants",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#dc2626",
                }).showToast();
            }
        });
    });
</script>
</form>
</div>

<script src="{{ asset('admin/js/pages/ecommerce-create-product.init.js') }}"></script>
@stack('scripts')
<script>
$(document).on('click', '.delete-gallery-image', function () {
    const btn   = $(this);
    const url   = btn.data('url');
    const id    = btn.data('id');
    const card  = btn.closest('.col-md-3');

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            media_id: id
        },
        success: function () {
            card.fadeOut(300, function () {
                $(this).remove();
            });
        },
        error: function () {
            alert('Failed to delete image');
        }
    });
});
</script>
<x-admin.footer />
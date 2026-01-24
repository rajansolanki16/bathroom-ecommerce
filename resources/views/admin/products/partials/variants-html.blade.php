@foreach($variants as $index => $variant)
@php
    $name = collect($variant)->pluck('value_name')->join(' / ');
@endphp

<div class="card mb-3 variant-card">

    {{-- HEADER --}}
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <div>
            <strong>{{ $name }}</strong><br>
            <small class="text-muted">
                SKU: <span class="meta-sku">—</span> |
                Price: <span class="meta-price">—</span> |
                Stock: <span class="meta-stock">0</span>
            </small>
        </div>
        <i class="ph-caret-down"></i>
    </div>

    {{-- BODY --}}
    <div class="card-body" style="display:none;">

        {{-- Varia  nt Name --}}
        <input type="hidden" name="variants[{{ $index }}][name]" value="{{ $name }}">

        {{-- Variant Values --}}
        @foreach($variant as $v)
            <input type="hidden"
                   name="variants[{{ $index }}][values][]"
                   value="{{ $v['value_id'] }}">
        @endforeach

        {{-- BASIC INFO --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">SKU</label>
                <input type="text"
                       name="variants[{{ $index }}][sku]"
                       class="form-control variant-sku">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Price</label>
                <input type="number"
                       step="0.01"
                       name="variants[{{ $index }}][price]"
                       class="form-control variant-price">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Stock</label>
                <input type="number"
                       name="variants[{{ $index }}][stock]"
                       class="form-control variant-stock">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Sell Price</label>
                <input type="number"
                       step="0.01"
                       name="variants[{{ $index }}][sell_price]"
                       class="form-control">
            </div>
        </div>

        {{-- SHIPPING --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Shipping Method</label>
                <input type="text"
                       name="variants[{{ $index }}][shipping]"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Shipping Address</label>
                <input type="text"
                       name="variants[{{ $index }}][shipping_address]"
                       class="form-control">
            </div>
        </div>

        {{-- DIMENSIONS --}}
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Weight</label>
                <input type="number" step="0.01"
                       name="variants[{{ $index }}][weight]"
                       class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Length</label>
                <input type="number" step="0.01"
                       name="variants[{{ $index }}][length]"
                       class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Width</label>
                <input type="number" step="0.01"
                       name="variants[{{ $index }}][width]"
                       class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Height</label>
                <input type="number" step="0.01"
                       name="variants[{{ $index }}][height]"
                       class="form-control">
            </div>
        </div>

        {{-- GENERAL INFO --}}
        <div class="mb-3">
            <label class="form-label">General Info</label>
            <textarea name="variants[{{ $index }}][general_info]"
                      rows="3"
                      class="form-control"></textarea>
        </div>

        {{-- IMAGE --}}
        <div class="mb-3">
            <label class="form-label">Variant Image</label>
            <input type="file"
                   name="variants[{{ $index }}][image]"
                   class="form-control variant-image-input"
                   accept="image/*">
            <img class="variant-image-preview mt-2"
                 style="display:none;max-height:80px;">
        </div>

        {{-- FLAGS --}}
        <div class="row">
            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           name="variants[{{ $index }}][exchangeable]"
                           value="1">
                    <label class="form-check-label">Exchangeable</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           name="variants[{{ $index }}][refundable]"
                           value="1">
                    <label class="form-check-label">Refundable</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           name="variants[{{ $index }}][free_shipping]"
                           value="1">
                    <label class="form-check-label">Free Shipping</label>
                </div>
            </div>
        </div>

    </div>
</div>
@endforeach

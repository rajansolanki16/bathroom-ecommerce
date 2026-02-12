<x-admin.header :title="'Create stock'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Create Stock</h4>
        <div class="page-title-right">
            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('stocks.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="stockRows">

                        @php
                        $oldProducts = old('products', [[]]); 
                        @endphp

                        @foreach($oldProducts as $i => $oldProduct)
                        <div class="stock-row mb-4">
                            <div class="row align-items-end">

                                {{-- PRODUCT --}}
                                <div class="col-md-4">
                                    <label class="form-label">
                                        Product <span class="text-danger">*</span>
                                    </label>
                                    <select id="productStock"
                                        class="form-control @error('products.0.product_id') is-invalid @enderror"
                                        name="products[0][product_id]">
                                        <option value="">Select product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ old('products.0.product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_title }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error("products.$i.product_id")
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- QUANTITY --}}
                                <div class="col-md-2">
                                    <label class="form-label">
                                        Quantity <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                        class="form-control @error('products.0.quantity') is-invalid @enderror"
                                        name="products[0][quantity]" value="{{ old('products.0.quantity') }}"
                                        placeholder="Enter quantity">

                                    @error("products.$i.quantity")
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- NOTES --}}
                                <div class="col-md-4">
                                    <label class="form-label">Notes</label>
                                    <input type="text"
                                        class="form-control @error('products.0.notes') is-invalid @enderror"
                                        name="products[0][notes]" value="{{ old('products.0.notes') }}"
                                        placeholder="Enter notes (optional)">
                                    @error('products.0.notes')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success w-100 addRow">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                        @endforeach

                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <a href="{{ route('stocks.index') }}" class="btn btn-danger"> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Create Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // let index = 1;
        let index = {{ count(old('products', [[]])) }};


        // Initialize Choices.js for any select element
        function initializeChoices(element) {

            if (!element) return;

            // prevent duplicate initialization
            if (element.dataset.choiceInit === "1") return;

            new Choices(element, {
                searchEnabled: true,
                removeItemButton: true,
                shouldSort: false,
                placeholder: true,
                placeholderValue: 'Select product',
                searchPlaceholderValue: 'Search product...',
                itemSelectText: '',
            });

            element.dataset.choiceInit = "1";
        }


        // Initialize first dropdown
        // initializeChoices('#productStock');
        $('select[id^="productStock"]').each(function() {
            initializeChoices(this);
        });

        // ADD ROW 
        $(document).on('click', '.addRow', function() {

            let row = `
        <div class="stock-row mb-4">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">
                        Product <span class="text-danger">*</span>
                    </label>
                    <select id="productStock${index}" class="form-control" name="products[${index}][product_id]">
                        <option value="">Select product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->product_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">
                        Quantity <span class="text-danger">*</span>
                    </label>
                    <input type="number"
                           class="form-control"
                           name="products[${index}][quantity]"
                           placeholder="Enter quantity"
                           >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Notes</label>
                    <input type="text"
                           class="form-control"
                           name="products[${index}][notes]"
                           placeholder="Enter notes">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger w-100 removeRow">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success w-100 addRow">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
        </div>
        `;

            $('#stockRows').append(row);

            // Initialize Choices.js for the newly added dropdown
           // initializeChoices(`#productStock${index}`);
           initializeChoices($(`#productStock${index}`)[0]);


            index++;
        });

        // REMOVE ROW 
        $(document).on('click', '.removeRow', function() {
            const stockRow = $(this).closest('.stock-row');

            const selectElement = stockRow.find('select')[0];
            if (selectElement && selectElement.choices) {
                selectElement.choices.destroy();
            }
            stockRow.remove();
        });

        //take only number
        $(document).on('keydown', 'input[type=number]', function(e) {
            if (['e', 'E', '+', '-', '.'].includes(e.key)) {
                e.preventDefault();
            }
        });
    });
</script>

<x-admin.footer />

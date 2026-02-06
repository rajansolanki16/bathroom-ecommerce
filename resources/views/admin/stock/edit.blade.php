<x-admin.header :title="'Edit stock'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Edit Stock</h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('stocks.update', $stock->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="product_name" class="form-label">
                            Product Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            class="form-control @error('product_name') is-invalid @enderror"
                            id="product_name"
                            name="product_name"
                            value="{{ old('product_name', $stock->product_name) }}"
                            placeholder="Enter product name">

                        @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">
                            Quantity <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                            class="form-control @error('quantity') is-invalid @enderror"
                            id="quantity"
                            name="quantity"
                            value="{{ old('quantity', $stock->quantity) }}"
                            placeholder="Enter quantity">

                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="unit" class="form-label">
                            Unit <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-control @error('unit') is-invalid @enderror"
                            id="unit"
                            name="unit">
                            <option value="">Select unit</option>
                            <option value="pcs" {{ old('unit', $stock->unit) == 'pcs' ? 'selected' : '' }}>PCS</option>
                            <option value="box" {{ old('unit', $stock->unit) == 'box' ? 'selected' : '' }}>BOX</option>
                            <option value="set" {{ old('unit', $stock->unit) == 'set' ? 'selected' : '' }}>SET</option>
                        </select>

                        @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">
                            Notes
                        </label>

                        <textarea
                            class="form-control @error('notes') is-invalid @enderror"
                            id="notes"
                            name="notes"
                            placeholder="Enter notes">{{ old('notes', $stock->notes) }}</textarea>

                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>




                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('stocks.index') }}" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<x-admin.footer />
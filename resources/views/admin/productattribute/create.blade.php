<x-admin.header :title="'Product Attribute'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Product Attribute Create</h4>
            <div class="page-title-right">
                <ol class="m-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Product Attribute</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">{{ __('attribute.Create_Product_Attribute') }}</h4>
            </div>

            <div class="card-body">
                <p class="text-muted">{{ __('attribute.Create_Product_Attribute_Description') }}</p>
                <form action="{{ route('product_attributes.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="attribute_name" class="form-label">{{ __('attribute.Product_Attribute_Name') }}<span class="text-danger">*</span></label>

                        <input type="text" name="name" id="attribute_name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter product attribute name">

                        @error('name')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-1 text-end">
                        <button type="submit" class="btn btn-primary">{{ __('attribute.Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
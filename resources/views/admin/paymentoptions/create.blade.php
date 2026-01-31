<x-admin.header :title="'Payment Options'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Payment Options Create</h4>

            <div class="page-title-right">
                <ol class="m-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Payment Options</a></li>
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
                <h4 class="mb-0 card-title">Payment Options Create</h4>
            </div>

            <div class="card-body">
                <p class="text-muted"></p>
                <form action="{{ route('paymentoptions.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="payment_type" class="form-label">Payment Type<span class="text-danger">*</span></label>

                        <input type="text" name="payment_type" id="payment_type" class="form-control @error('payment_type') is-invalid @enderror" placeholder="Enter payment type">

                        @error('payment_type')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-check form-switch mb-3">
                                <label class="form-label" for="is_active">Is Active</label>
                                <input type="checkbox" name="is_active" value="1" class="form-check-input">  
                            </div>
                        </div>
                    </div>



                    <div class="mb-1 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ route('paymentoptions.index') }}" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
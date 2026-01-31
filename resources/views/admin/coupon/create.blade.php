<x-admin.header :title="'Coupon create'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">{{ __('coupon.Add_coupon') }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>


<form class="store-blogs" action="{{ route('coupons.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title"> {{ __('coupon.Coupon_code') }}</h5>
                            <p class="text-muted">{{ __('coupon.coupon_desc') }}</p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label for="coupon_code" class="form-label">{{ __('coupon.Coupon_code') }} <span
                                        class="text-danger">{{ __('coupon.required_mark') }}</span></label>
                                <input type="text" name="code" id="vec_coupon_code" class="form-control"
                                    placeholder="Enter coupon code" value="">
                                @error('code')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Generate Coupon Code -->
                            <div class="gap-2 mb-3 hstack justify-content">
                                <button type="button" onclick="vec_generate_coupon_code()" class="btn btn-secondary">{{ __('coupon.Generate_Code_Button') }} </button>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('coupon.description') }} </label>
                                <input type="text" name="description" id="description" class="form-control"
                                    placeholder="Enter description">
                            </div>

                            <div class="mb-3">
                                <label for="discount_type" class="form-label">{{ __('coupon.discount_type') }} <span
                                        class="text-danger">{{ __('coupon.required_mark') }}</span></label>
                                <select name="type" class="form-control">
                                    <option value="">Select Discount Type</option>
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                                @error('type')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label"> {{ __('coupon.amount') }} <span
                                        class="text-danger">{{ __('coupon.required_mark') }}</span></label>
                                <input type="text" name="amount" id="amount" class="form-control"
                                    placeholder="Enter discount amount">
                                @error('amount')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="discount_amount" class="form-label">{{ __('coupon.discount_amount') }}</label>
                                <input type="text" name="discount_amount" id="discount_amount" class="form-control"
                                    placeholder="Enter discount amount">

                            </div>

                            <div class="mb-3">
                                <label for="start_date" class="form-label"> {{ __('coupon.start_date') }}</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    placeholder="Enter start date">
                            </div>

                            <div class="mb-3">
                                <label for="expiry_date" class="form-label"> {{ __('coupon.end_date') }}</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                    placeholder="Enter expiry date">
                            </div>


                            <div class="mb-3">
                                <label for="max_usage" class="form-label"> {{ __('coupon.maximum_usage') }}</label>
                                <input type="number" name="max_usage" id="max_usage" class="form-control"
                                    placeholder="Enter usage limit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gap-2 mb-3 hstack justify-content-end">
        <a href="{{ route('coupons.index') }}" class="btn btn-danger">{{ __('coupon.Cancel_Button') ?? 'Back' }}</a>
        <button type="submit" name="action" value="store" class="btn btn-primary">{{ __('coupon.Create_Button') }}</button>
    </div>
</form>
<x-admin.footer />
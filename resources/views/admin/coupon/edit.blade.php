<x-admin.header :title="'Coupon Edit'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ __('coupon.Edit_Coupon') }}</h4>

            <div class="page-title-right">
                <ol class="m-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Coupon</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">{{ __('coupon.Coupon_code') }}</h5>
                            <p class="text-muted">{{ __('coupon.coupon_desc') }}.</p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label for="coupon_code" class="form-label">{{ __('coupon.Coupon_code') }} <span
                                        class="text-danger">{{ __('coupon.required_mark') }}</span></label>
                                <input type="text" name="code" id="coupon_code" class="form-control"
                                    placeholder="Enter coupon code" value="{{ old('code', $coupon->code) }}">
                                @error('code')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('coupon.description') }} </label>
                                <input type="text" name="description" id="description" class="form-control"
                                    placeholder="Enter description" value="{{ old('description', $coupon->description) }}">
                            </div>
                            <div class="mb-3">
                                <label for="discount_type" class="form-label">{{ __('coupon.discount_type') }} <span
                                        class="text-danger">{{ __('coupon.required_mark') }}</span></label>
                                <select name="type" class="form-control">
                                    <option value="">Select Discount Type</option>
                                    <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                </select>
                                @error('type')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">{{ __('coupon.amount') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control"
                                    placeholder="Enter amount" value="{{ old('amount', $coupon->amount) }}">
                                @error('amount')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="discount_amount" class="form-label"> {{ __('coupon.discount_amount') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="discount_amount" id="discount_amount" class="form-control"
                                    placeholder="Enter discount amount" value="{{ old('discount_amount', $coupon->discount_amount) }}">
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ __('coupon.start_date') }} <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ old('start_date', $coupon->start_date) }}">
                            </div>
                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">{{ __('coupon.end_date') }} <span
                                        class="text-danger">{{ __('coupon.required_mark') }}</span></label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                    value="{{ old('expiry_date', $coupon->expiry_date) }}">

                            </div>
                            <div class="mb-3">
                                <label for="max_usage" class="form-label">{{ __('coupon.maximum_usage') }} <span
                                        class="text-danger">{{ __('coupon.required_mark') }}</span></label>
                                <input type="number" name="max_usage" id="max_usage" class="form-control"
                                    placeholder="Enter maximum usage" value="{{ old('max_usage', $coupon->max_usage) }}">
                            </div>
                        </div>
                    </div>

                    <div class="gap-2 mb-3 hstack justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ __('coupon.Update_Button') }}</button>
                        <a href="{{ route('coupons.index') }}" class="btn btn-danger">{{ __('coupon.Cancel_Button') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<x-admin.footer />
<x-admin.header :title="'Ecommerce Settings'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ __('ecommerce.ecommerce_settings') }}</h4>

            <div class="page-title-right">
                <ol class="m-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<form class="store-blogs" action="{{ route('settings.ecommerce.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label for="currency_symbol" class="form-label">{{ __('ecommerce.currency_symbol') }}</label>
                                <input type="text" name="currency_symbol" id="currency_symbol" class="form-control"
                                    placeholder="Enter currency symbol">
                            </div>

                            <div class="mb-3">
                                <label for="currency_word" class="form-label">{{ __('ecommerce.currency_word') }}</label>
                                <input type="text" name="currency_word" id="currency_word" class="form-control"
                                    placeholder="Enter currency word">

                            </div>



                            <div class="mb-3">
                                <label for="store_address" class="form-label">{{ __('ecommerce.store_address') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="store_address" id="store_address" class="form-control"
                                    placeholder="Enter store address">
                                @error('store_address')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="mb-3">
                                <label for="store_city" class="form-label">{{ __('ecommerce.store_city') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="store_city" id="store_city" class="form-control"
                                    placeholder="Enter store city">
                                @error('store_city')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="mb-3">
                                <label for="store_country" class="form-label">{{ __('ecommerce.store_country') }}<span
                                        class="text-danger">*</span></label>
                                <select name="store_country" id="vec_store_country" class="form-control">
                                    <option value="">Select Country</option>

                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $country->name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('store_country')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="store_postal_code" class="form-label">{{ __('ecommerce.store_postal_code') }}</label>
                                <input type="text" name="store_postal_code" id="store_postal_code" class="form-control"
                                    placeholder="Enter store postal code">

                            </div>

                            <div class="mb-3">
                                <label for="weight_unit" class="form-label">{{ __('ecommerce.weight_unit') }}<span
                                        class="text-danger">*</span></label>
                                <select name="weight_unit" id="weight_unit" class="form-control">
                                    <option value="">Select</option>
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                    <option value="lbs">lbs</option>
                                    <option value="oz">oz</option>
                                </select>
                                @error('weight_unit')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="dimension_unit" class="form-label">{{ __('ecommerce.dimension_unit') }}<span
                                        class="text-danger">*</span></label>
                                <select name="dimension_unit" id="dimension_unit" class="form-control">
                                    <option value="">Select</option>
                                    <option value="m">m</option>
                                    <option value="cm">cm</option>
                                    <option value="mm">mm</option>
                                    <option value="in">in</option>
                                    <option value="yd">yd</option>
                                </select>
                                @error('dimension_unit')
                                <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">{{ __('ecommerce.save_settings') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>



<x-admin.footer />
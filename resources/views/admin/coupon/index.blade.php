<x-admin.header :title="'Coupon Listings'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<div class="col-xl-12">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-nowrap">
            <h4 class="mb-0 card-title">{{ __('coupon.Coupon_Listing') }}</h4>

            <a href="{{ route('coupons.create') }}" class="btn btn-primary add-btn">
                <i class="align-baseline bi bi-plus-circle me-1"></i>
                {{ __('coupon.Add_Coupon_Button') }}
            </a>
        </div>
        <div class="card-body">
            <p class="text-muted"> {{ __('coupon.coupon_list_desc') }} </p>
            <div class="table-responsive">
                <table id="fixed-header" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Code</th>
                            <th scope="col">Type</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Description</th>
                            <th scope="col">Discount Amount</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Maxium usage</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($coupons as $coupon)
                        <tr>
                            <td class="fw-medium">{{ $coupon->id }}</td>
                            <td class="fw-medium">{{ $coupon->code }}</td>
                            <td class="fw-medium">{{ $coupon->type }}</td>
                            <td class="fw-medium">{{ $coupon->amount }}</td>
                            <td class="fw-medium">{{ $coupon->description }}</td>
                            <td class="fw-medium">{{ $coupon->discount_amount }}</td>
                            <td class="fw-medium">{{ $coupon->start_date }}</td>
                            <td class="fw-medium">{{ $coupon->expiry_date }}</td>
                            <td class="fw-medium">{{ $coupon->max_usage }}</td>
                            <td>
                                <div class="dropdown position-static">
                                    <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="{{ route('coupons.edit', $coupon->id) }}" class="dropdown-item edit-item-btn"><i class="align-middle ph-pencil me-1"></i>Edit</a></li>
                                        <li>
                                            <a class="dropdown-item remove-item-btn" href="javascript:void(0);"
                                                data-delete-url="{{ route('coupons.destroy', $coupon->id) }}"
                                                onclick="setDeleteFormAction(this)">
                                                <i class="align-middle ph-trash me-1"></i> Remove
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteRecordModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-md-5">
                <div class="text-center">
                    <div class="text-danger">
                        <i class="bi bi-trash display-4"></i>
                    </div>
                    <div class="mt-4">
                        <h3 class="mb-2">Are you sure?</h3>
                        <p class="mx-3 mb-0 text-muted fs-lg">Are you sure you want to remove this Coupon <b>permanently</b>?</p>
                    </div>
                </div>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="gap-2 mt-4 mb-2 d-flex justify-content-center">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn w-sm btn-danger">Yes!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
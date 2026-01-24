<x-admin.header :title="'Payment Options Listing'" />
<!--datatable css-->

<div class="col-xl-12">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-nowrap">
            <h4 class="mb-0 card-title">payment option list</h4>

            <a href="{{ route('paymentoptions.create') }}" class="btn btn-primary add-btn">
                <i class="align-baseline bi bi-plus-circle me-1"></i>
                Add Payment Option
            </a>
        </div>
        <div class="card-body">
            <p class="text-muted"> this is the list of all payment options </p>
            <div class="table-responsive">
                <table id="fixed-header" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">slug</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($paymentOptions as $paymentOption)
                        <tr>
                            <td>{{ $paymentOption->id }}</td>
                            <td>{{ $paymentOption->payment_type }}</td>
                            <td>{{ $paymentOption->is_active }}</td>

                            <td>
                                <div class="dropdown position-static">
                                    <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="" class="dropdown-item edit-item-btn"><i class="align-middle ph-pencil me-1"></i>Edit</a></li>
                                        <li>
                                            <a class="dropdown-item remove-item-btn" href="javascript:void(0);"
                                                data-delete-url=""
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


<x-admin.footer />
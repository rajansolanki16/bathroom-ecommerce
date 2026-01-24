<x-admin.header :title="'Room Amenities'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">Room Amenities</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">Create/Edit</h4>
            </div>

            <div class="card-body">
                <p class="text-muted">Create the new amenity for rooms or edit the existing one here.</p>
                <form action="{{ route('amenities.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="amenity-icon" class="form-label">Amenity Icon <span
                                class="text-danger">*</span></label>
                        <input type="file" name="icon" id="amenity-icon"
                            class="form-control @error('icon') is-invalid @enderror">
                        <small class="text-muted">Leave blank if you do not want to update the icon while
                            editing.</small>

                        @error('icon')
                            <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Amenity Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Enter amenity name"
                            value="{{ $edit_amenity->name ?? '' }}" required>

                        @error('name')
                            <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Amenity Status <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <label class="input-group-text" for="inputGroupSelect01">Status</label>
                            <select class="form-select" name="status" id="inputGroupSelect01" required>
                                <option value="0">Disabled</option>
                                @if(isset($edit_amenity))
                                    <option value="1" {{ $edit_amenity->status == '1' ? 'selected' : '' }}>Enabled</option>
                                @else
                                    <option value="1">Enabled</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 text-end">
                        @error('general')
                            <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @if(isset($edit_amenity))
                            <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>

                            <input type="hidden" name="edit_amenity_id" value="{{ $edit_amenity->id }}" />
                        @else
                            <input type="hidden" name="edit_amenity_id" value="0" />
                        @endif

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">Amenity list</h4>
            </div>

            <div class="card-body">
                <p class="text-muted"> This is the list of all room amenities </p>
                <div class="table-responsive">
                    <table id="fixed-header"
                        class="table align-middle table-bordered dt-responsive nowrap table-striped"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">ICON</th>
                                <th scope="col">Name</th>
                                <th scope="col">status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($amenities) > 0)
                                @foreach ($amenities as $amenity)
                                    <tr>
                                        <td class="fw-medium">{{ $amenity->id }}</td>
                                        <td><img src="{{ publicPath($amenity->icon) }}" alt="icon" width="30" height="30"></td>
                                        <td>{{ $amenity->name }}</td>
                                        <td>
                                            @if($amenity->status == 1)
                                                <span class="badge bg-success">enabled</span>
                                            @else
                                                <span class="badge bg-danger">disabled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown position-static">
                                                <button class="btn btn-subtle-secondary btn-sm btn-icon" role="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{ route('amenities.edit', $amenity->id) }}"
                                                            class="dropdown-item edit-item-btn"><i
                                                                class="align-middle ph-pencil me-1"></i>Edit</a></li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="dropdown-item remove-item-btn"
                                                            data-delete-url="{{ route('amenities.destroy', $amenity->id) }}"
                                                            onclick="setDeleteFormAction(this)"><i
                                                                class="align-middle ph-trash me-1">
                                                            </i> Remove</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
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
                        <p class="mx-3 mb-0 text-muted fs-lg">Are you sure you want to remove this room amenity
                            <b>permanently</b> ?</p>
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

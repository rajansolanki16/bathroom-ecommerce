<x-admin.header :title="'Brand Details'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Brand Details</h4>
        <div class="page-title-right">
            <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <h6 class="text-muted">Logo</h6>
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="height: 150px; width: 150px; object-fit: cover; border-radius: 4px;">
                        @else
                            <span class="badge bg-secondary">No Logo</span>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h6 class="text-muted">Brand Name</h6>
                        <p class="h5">{{ $brand->name }}</p>

                        <h6 class="text-muted">Slug</h6>
                        <p>{{ $brand->slug }}</p>

                        <h6 class="text-muted">Status</h6>
                        <p>
                            @if($brand->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted">Description</h6>
                        <p>{{ $brand->description ?? 'No description provided' }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Created</h6>
                        <p>{{ $brand->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Updated</h6>
                        <p>{{ $brand->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />

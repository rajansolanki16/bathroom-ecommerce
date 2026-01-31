<x-admin.header :title="'Edit Brand'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Edit Brand</h4>
        <div class="page-title-right">
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
                <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name', $brand->name) }}" placeholder="Enter brand name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Brand Logo</label>
                        @if($brand->hasMedia('brand_logo'))
                            @php
                                $media = $brand->getFirstMedia('brand_logo');
                            @endphp

                            <div class="mb-2 position-relative d-inline-block brand-logo-wrapper">
                                <img src="{{ $media->getUrl() }}"
                                    alt="{{ $brand->name }}"
                                    style="height: 100px; width: 100px; object-fit: cover; border-radius: 4px;">

                                <button type="button"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-brand-logo"
                                    data-url="{{ route('media.delete', $media->id) }}"
                                    title="Delete logo">
                                    ✕
                                </button>

                                <p class="text-muted small mt-1">Current logo</p>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('logo') is-invalid @enderror"
                            id="logo" name="logo" accept="image/*">
                        <small class="text-muted">Accepted formats: jpeg, png, jpg, gif (Max: 2MB)</small>
                        @error('logo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="4" placeholder="Enter brand description">{{ old('description', $brand->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ $brand->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('brands.index') }}" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Brand
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).on('click', '.delete-brand-logo', function () {
    const mediaId = $(this).data('id');

    let url = "{{ route('media.delete', ':id') }}";
    url = url.replace(':id', mediaId);

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            location.reload(); 
        }
    });
});
</script>

<x-admin.footer />

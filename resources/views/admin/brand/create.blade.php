<x-admin.header :title="'Create Brand'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Create New Brand</h4>
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
                <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" placeholder="Enter brand name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Brand Logo</label>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mediaPickerModal">Choose from Media Library</button>
                        <input type="hidden" name="media_library_logo_id" id="media_library_logo_id" value="{{ old('media_library_logo_id', '') }}">
                        <div id="selected-media-preview" class="mt-2"></div>
                        @error('media_library_logo_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="4" placeholder="Enter brand description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('brands.index') }}" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Create Brand
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />
<!-- Media Picker Modal -->
<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-labelledby="mediaPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaPickerModalLabel">Select Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="mediaPickerModalBody">
                <!-- Media grid will be loaded here -->
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    window.initMediaPicker({
        pickerBtnSelector: '[data-bs-target="#mediaPickerModal"]',
        modalBodySelector: '#mediaPickerModalBody',
        modalSelector: '#mediaPickerModal',
        hiddenInputSelector: '#media_library_logo_id',
        previewSelector: '#selected-media-preview',
        pickerUrl: "{{ route('media-library.picker') }}",
        formSelector: 'form[action*="brands.store"]'
    });
});
</script>

<?php use Illuminate\Support\Facades\Storage; ?>
<x-admin.header :title="'product Categories edit'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Category Edit</h4>

            <div class="page-title-right">
                <ol class="m-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Category</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">{{ __('category.Edit_Category') }}</h4>
            </div>

            <div class="card-body">
                <p class="text-muted">{{ __('category.Edit_Description') }}</p>

                <form action="{{ route('categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="category" class="form-label">{{ __('category.Category_Title') }} <span class="text-danger">{{ __('category.required_mark') }}</span></label>

                        <input type="text" name="name" id="category" class="form-control @error('name') is-invalid @enderror" placeholder="Enter category title" value="{{ old('name', isset($category) ? $category->name : '') }}">

                        @error('name')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category Image</label>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mediaPickerModal">Choose from Media Library</button>
                        <input type="hidden" name="media_library_logo_id" id="media_library_logo_id" value="{{ old('media_library_logo_id', $category->media_library_logo_id ?? '') }}">
                        <div id="selected-media-preview" class="mt-2">
                            @php
                                $logoUrl = null;
                                if (!empty($category->media_library_logo_id)) {
                                    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($category->media_library_logo_id);
                                    if ($media && file_exists(storage_path('app/public/' . $media->id . '/' . $media->file_name))) {
                                        $logoUrl = asset('storage/' . $media->id . '/' . $media->file_name);
                                    }
                                }
                            @endphp
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" style="height:100px;width:100px;object-fit:cover;border-radius:4px;">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </div>
                        @error('media_library_logo_id')
                            <div class="invalid-response">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subcategory" class="form-label">{{ __('category.Parent_Category') }} <span class="text-danger">{{ __('category.required_mark') }}</span></label>


                        <select name="parent_id" id="subcategory" class="form-control">
                            <option value="">Select parent Category</option>
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', isset($category) ? $category->parent_id : '') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                            @endforeach
                        </select> <br>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" name="is_visible" value="1" class="form-check-input" {{ old('is_visible', $category->is_visible) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isvisibleInput">Is Visible</label>
                            </div>
                        </div>

                    </div>

                    <div class="gap-2 mb-3 hstack justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ __('category.Update_Button') }}</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-danger">{{ __('category.Cancel_Button') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
        formSelector: 'form[action*="categories.update"]'
    });
});
</script>
<x-admin.footer />
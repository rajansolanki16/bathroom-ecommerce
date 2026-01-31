<x-admin.header :title="'Product Categories'" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<x-page-title title="Category Create" :breadcrumbs="['Category', 'Create']" />

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">{{ __('category.Create_Category') }}</h4>
            </div>

            <div class="card-body">
                <p class="text-muted">{{ __('category.Category_Description') }}</p>
                <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="category" class="form-label">{{ __('category.Category_Title') }}<span class="text-danger">{{ __('category.required_mark') }}</span></label> 

                        <input type="text" name="name" id="category" class="form-control @error('name') is-invalid @enderror" placeholder="Enter category title">

                        @error('name')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Category Image</label>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mediaPickerModal">Choose from Media Library</button>
                        <input type="hidden" name="media_library_logo_id" id="media_library_logo_id" value="{{ old('media_library_logo_id', '') }}">
                        <div id="selected-media-preview" class="mt-2"></div>
                        @error('media_library_logo_id')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subcategory" class="form-label">{{ __('category.Parent_Category') }} <span class="text-danger">{{ __('category.required_mark') }}</span></label>

                        <select name="parent_id" id="subcategory" class="form-control">
                            <option value="">Select parent Category</option>
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">
                                {{ $parent->name }}
                            </option>
                            @endforeach
                        </select> <br>  

                        <div class="mb-3">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" name="is_visible" id="isvisibleInput" value="1" class="form-check-input" checked>
                                <label class="form-check-label" for="isvisibleInput">
                                    Is Visible
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="mb-1 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ route('categories.index') }}" class="btn btn-danger">{{ __('category.Cancel_Button') ?? 'Back' }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('category.Create_Button') }}</button>
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
let selectedMediaId = document.getElementById('media_library_logo_id').value || null;
function openMediaPicker() {
    fetch("{{ route('media-library.picker') }}")
        .then(res => res.text())
        .then(html => {
            document.getElementById('mediaPickerModalBody').innerHTML = html;
            document.querySelectorAll('#mediaPickerModalBody .media-thumb').forEach(item => {
                item.onclick = function() {
                    selectedMediaId = item.getAttribute('data-id');
                    document.getElementById('media_library_logo_id').value = selectedMediaId;
                    const imgUrl = item.querySelector('img').src;
                    document.getElementById('selected-media-preview').innerHTML = `<img src='${imgUrl}' style='height:100px;width:100px;object-fit:cover;border-radius:4px;'>`;
                    document.getElementById('mediaPickerModal').querySelector('.btn-close').click();
                };
            });
        });
}
document.querySelector('[data-bs-target="#mediaPickerModal"]').addEventListener('click', openMediaPicker);
document.querySelector('form[action*="categories.store"]').addEventListener('submit', function(e) {
    document.getElementById('media_library_logo_id').value = selectedMediaId || '';
});
</script>
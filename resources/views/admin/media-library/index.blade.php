<x-admin.header :title="'Media Library'" />
<div class="container-fluid" style="background: #fff; padding: 32px 24px; border-radius: 8px; min-height: 80vh;">
    <div class="d-flex align-items-center mb-4" style="gap: 16px;">
        <h2 style="font-size: 1.5rem; font-weight: 500; margin-bottom: 0;">Media Library</h2>
        <button class="btn btn-primary ms-3">Add New</button>
        <div class="ms-auto d-flex gap-2 align-items-center">
            <select id="sort-by" class="form-select form-select-sm" style="width: 140px;">
                <option value="date">Sort by Date</option>
                <option value="author">Sort by Author</option>
                <option value="type">Sort by Type</option>
            </select>
            <input type="text" id="media-search" class="form-control form-control-sm" style="width: 200px;"
                placeholder="Search by file name...">
            <button id="grid-view" class="btn btn-outline-secondary btn-sm active" title="Grid view"><i
                    class="bi bi-grid"></i></button>
            <button id="list-view" class="btn btn-outline-secondary btn-sm" title="List view"><i
                    class="bi bi-list"></i></button>
        </div>
    </div>
    <form id="media-upload-form" enctype="multipart/form-data" class="mb-4" style="max-width: 420px;">
        @csrf
        <div class="mb-3">
            <label for="file-upload" class="form-label">Upload files (images, videos, audio, PDFs, docs)</label>
            <div id="drop-area"
                style="border: 2px dashed #e2e2e2; border-radius: 8px; padding: 32px; text-align: center; background: #fafafa; cursor: pointer;">
                <span id="drop-text">Drag & drop files here or click to select</span>
                <input type="file" id="file-upload" name="file[]" class="form-control mt-2" multiple
                    accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                    style="display:none;">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <div id="upload-preview"
        style="display:grid;grid-template-columns:repeat(auto-fill,150px);gap:16px;margin-top:16px;">
    </div>
    <div id="media-container">
        <div class="media-grid" id="media-grid"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 24px; margin-top: 16px;">
            @foreach ($media as $item)
                <div class="media-thumb position-relative" data-name="{{ $item->file_name }}"
                    data-type="{{ $item->mime_type }}" data-date="{{ $item->created_at }}"
                    data-author="{{ $item->model_id }}"
                    style="background: #fff; border-radius: 6px; overflow: hidden; height: 150px; width: 150px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e2e2e2; margin: auto; transition: box-shadow 0.2s;"
                    onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.12)';"
                    onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.07)';">
                    <img src="{{ $item->getUrl() }}" alt="Media"
                        style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 0;">
                    <form class="delete-media-form position-absolute top-0 end-0 m-1" data-id="{{ $item->id }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"
                            style="padding: 2px 6px; font-size: 12px;">&times;</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="media-list d-none" id="media-list" style="margin-top: 16px;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>File Name</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Author</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($media as $item)
                        <tr data-name="{{ $item->file_name }}" data-type="{{ $item->mime_type }}"
                            data-date="{{ $item->created_at }}" data-author="{{ $item->model_id }}">
                            <td><img src="{{ $item->getUrl() }}" alt="Media"
                                    style="height: 40px; width: 40px; object-fit: cover;"></td>
                            <td>{{ $item->file_name }}</td>
                            <td>{{ $item->mime_type }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->model_id }}</td>
                            <td>
                                <form class="delete-media-form" data-id="{{ $item->id }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $media->links() }}</div>
</div>
<div id="media-edit-modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <img id="edit-media-preview" src=""
                        style="max-width: 200px; max-height: 200px; object-fit: contain;">
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>File Name:</strong> <span id="edit-file-name"></span><br>
                        <strong>File Size:</strong> <span id="edit-file-size"></span><br>
                        <strong>Type:</strong> <span id="edit-file-type"></span><br>
                        <strong>Upload Date:</strong> <span id="edit-upload-date"></span><br>
                        <strong>URL:</strong> <a id="edit-file-url" href="#" target="_blank">Open</a>
                    </div>
                </div>
                <form id="media-edit-form">
                    <input type="hidden" id="edit-media-id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" id="edit-title" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alt Text <span class="text-muted">(SEO)</span></label>
                            <input type="text" id="edit-alt" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="edit-description" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div> <br>

<script>
    $(function() {

        const $dropArea = $('#drop-area');
        const $fileInput = $('#file-upload');
        const $dropText = $('#drop-text');

        // Click → open file picker
        $dropArea.on('click', function(e) {
            if ($(e.target).is('input, button')) return;
            e.stopPropagation();
            $fileInput.trigger('click');
        });

        // Drag enter / over
        $dropArea.on('dragenter dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).css('background', '#e9f5ff');
            $dropText.text('Drop files to upload');
        });

        // Drag leave / drop
        $dropArea.on('dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).css('background', '#fafafa');
            $dropText.text('Drag & drop files here or click to select');
        });

        // Drop files
        $dropArea.on('drop', function(e) {
            const files = e.originalEvent.dataTransfer.files;
            $fileInput[0].files = files;
        });
        $('#media-upload-form').on('submit', function (e) {
    e.preventDefault();

    const files = $('#file-upload')[0].files;

    if (!files.length) {
        alert('Please select at least one file.');
        return;
    }

    const formData = new FormData(this); // ✅ includes ALL files automatically

    $.ajax({
        url: "{{ route('media-library.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            location.reload();
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Upload failed');
        }
    });
});
        $(document).on('submit', '.delete-media-form', function(e) {
            e.preventDefault();

            const id = $(this).data('id');
            const url = "{{ route('media-library.destroy', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $(this).find('input[name=_token]').val()
                },
                success: function() {
                    location.reload();
                }
            });
        });


        $('#grid-view').on('click', function() {
            $('#media-grid').removeClass('d-none');
            $('#media-list').addClass('d-none');
            $(this).addClass('active');
            $('#list-view').removeClass('active');
        });

        $('#list-view').on('click', function() {
            $('#media-grid').addClass('d-none');
            $('#media-list').removeClass('d-none');
            $(this).addClass('active');
            $('#grid-view').removeClass('active');
        });

        $('#media-search').on('input', function() {
            const val = $(this).val().toLowerCase();

            $('#media-grid .media-thumb, #media-list tbody tr').each(function() {
                const name = ($(this).data('name') || '').toLowerCase();
                $(this).toggle(name.includes(val));
            });
        });

        $('#sort-by').on('change', function() {
            const sortBy = $(this).val();

            function sortItems(items) {
                return items.sort(function(a, b) {
                    let av = $(a).data(sortBy) || '';
                    let bv = $(b).data(sortBy) || '';

                    if (sortBy === 'date') {
                        return new Date(bv) - new Date(av);
                    }
                    return av.toString().localeCompare(bv.toString());
                });
            }

            const gridItems = sortItems($('#media-grid .media-thumb').toArray());
            $('#media-grid').append(gridItems);

            const listRows = sortItems($('#media-list tbody tr').toArray());
            $('#media-list tbody').append(listRows);
        });

        $(document).on('click', '.media-thumb', function(e) {
            if ($(e.target).closest('.btn-danger').length) return;

            const mediaId = $(this).find('.delete-media-form').data('id');
            const url = "{{ route('media-library.show', ':id') }}".replace(':id', mediaId);

            $.get(url, function(meta) {
                $('#edit-media-id').val(mediaId);
                $('#edit-media-preview').attr('src', meta.url);
                $('#edit-title').val(meta.file_name).focus().select();
                $('#edit-alt').val(meta.alt || '');
                $('#edit-description').val(meta.description || '');
                $('#edit-file-name').text(meta.file_name);
                $('#edit-file-size').text((meta.size / 1024).toFixed(2) + ' KB');
                $('#edit-file-type').text(meta.mime_type);
                $('#edit-upload-date').text(meta.created_at);
                $('#edit-file-url').attr('href', meta.url);

                new bootstrap.Modal('#media-edit-modal').show();
            });
        });

        $('#media-edit-form').on('submit', function(e) {
            e.preventDefault();

            const id = $('#edit-media-id').val();
            const formData = new FormData(this);
            formData.append('_token', $('input[name=_token]').val());

            $.ajax({
                url: "{{ route('media-library.update', ':id') }}".replace(':id', id),
                type: "POST",
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert('Failed to update media.');
                }
            });
        });

    });
</script>
<x-admin.footer />

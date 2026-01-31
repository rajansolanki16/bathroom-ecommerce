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
            <input type="text" id="media-search" class="form-control form-control-sm" style="width: 200px;" placeholder="Search by file name...">
            <button id="grid-view" class="btn btn-outline-secondary btn-sm active" title="Grid view"><i class="bi bi-grid"></i></button>
            <button id="list-view" class="btn btn-outline-secondary btn-sm" title="List view"><i class="bi bi-list"></i></button>
        </div>
    </div>
    <form id="media-upload-form" enctype="multipart/form-data" class="mb-4" style="max-width: 420px;">
        @csrf
        <div class="mb-3">
            <label for="file-upload" class="form-label">Upload files (images, videos, audio, PDFs, docs)</label>
            <div id="drop-area" style="border: 2px dashed #e2e2e2; border-radius: 8px; padding: 32px; text-align: center; background: #fafafa; cursor: pointer;">
                <span id="drop-text">Drag & drop files here or click to select</span>
                <input type="file" id="file-upload" name="file[]" class="form-control mt-2" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar" style="display:none;">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <div id="media-container">
        <div class="media-grid" id="media-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 24px; margin-top: 16px;">
            @foreach($media as $item)
                <div class="media-thumb position-relative" data-name="{{ $item->file_name }}" data-type="{{ $item->mime_type }}" data-date="{{ $item->created_at }}" data-author="{{ $item->model_id }}" style="background: #fff; border-radius: 6px; overflow: hidden; height: 150px; width: 150px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e2e2e2; margin: auto; transition: box-shadow 0.2s;"
                    onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.12)';" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.07)';">
                    <img src="{{ $item->getUrl() }}" alt="Media" style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 0;">
                    <form class="delete-media-form position-absolute top-0 end-0 m-1" data-id="{{ $item->id }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 2px 6px; font-size: 12px;">&times;</button>
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
                    @foreach($media as $item)
                    <tr data-name="{{ $item->file_name }}" data-type="{{ $item->mime_type }}" data-date="{{ $item->created_at }}" data-author="{{ $item->model_id }}">
                        <td><img src="{{ $item->getUrl() }}" alt="Media" style="height: 40px; width: 40px; object-fit: cover;"></td>
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
                    <img id="edit-media-preview" src="" style="max-width: 200px; max-height: 200px; object-fit: contain;">
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
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('file-upload');
const dropText = document.getElementById('drop-text');

// Click to open file dialog
 dropArea.addEventListener('click', () => fileInput.click());

// Drag & drop events
['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, e => {
        e.preventDefault();
        e.stopPropagation();
        dropArea.style.background = '#e9f5ff';
        dropText.textContent = 'Drop files to upload';
    });
});
['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, e => {
        e.preventDefault();
        e.stopPropagation();
        dropArea.style.background = '#fafafa';
        dropText.textContent = 'Drag & drop files here or click to select';
    });
});
dropArea.addEventListener('drop', e => {
    fileInput.files = e.dataTransfer.files;
});

document.getElementById('media-upload-form').onsubmit = async function(e) {
    e.preventDefault();
    const formData = new FormData();
    for (const file of fileInput.files) {
        formData.append('file[]', file);
    }
    formData.append('_token', document.querySelector('input[name=_token]').value);
    const res = await fetch("{{ route('media-library.store') }}", {
        method: 'POST',
        body: formData
    });
    if (res.ok) location.reload();
};
document.querySelectorAll('.delete-media-form').forEach(form => {
    form.onsubmit = async function(e) {
        e.preventDefault();
        const id = form.getAttribute('data-id');
        const url = "{{ route('media-library.destroy', ':id') }}".replace(':id', id);
        const res = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
            }
        });
        if (res.ok) location.reload();
    };
});

// Grid/List toggle
const gridBtn = document.getElementById('grid-view');
const listBtn = document.getElementById('list-view');
const grid = document.getElementById('media-grid');
const list = document.getElementById('media-list');
gridBtn.onclick = function() {
    grid.classList.remove('d-none');
    list.classList.add('d-none');
    gridBtn.classList.add('active');
    listBtn.classList.remove('active');
};
listBtn.onclick = function() {
    grid.classList.add('d-none');
    list.classList.remove('d-none');
    listBtn.classList.add('active');
    gridBtn.classList.remove('active');
};

// Search
const searchInput = document.getElementById('media-search');
searchInput.oninput = function() {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#media-grid .media-thumb, #media-list tbody tr').forEach(el => {
        const name = el.getAttribute('data-name')?.toLowerCase() || '';
        el.style.display = name.includes(val) ? '' : 'none';
    });
};

// Sort
const sortSelect = document.getElementById('sort-by');
sortSelect.onchange = function() {
    const sortBy = this.value;
    let items = Array.from(document.querySelectorAll('#media-grid .media-thumb'));
    items.sort((a, b) => {
        let av = a.getAttribute('data-' + sortBy) || '';
        let bv = b.getAttribute('data-' + sortBy) || '';
        if (sortBy === 'date') {
            av = new Date(av); bv = new Date(bv);
            return bv - av;
        }
        return av.localeCompare(bv);
    });
    items.forEach(el => grid.appendChild(el));
    // List view sort
    let rows = Array.from(document.querySelectorAll('#media-list tbody tr'));
    rows.sort((a, b) => {
        let av = a.getAttribute('data-' + sortBy) || '';
        let bv = b.getAttribute('data-' + sortBy) || '';
        if (sortBy === 'date') {
            av = new Date(av); bv = new Date(bv);
            return bv - av;
        }
        return av.localeCompare(bv);
    });
    rows.forEach(el => list.querySelector('tbody').appendChild(el));
};

let cropper;
function openEditModal(mediaId, url, meta) {
    document.getElementById('edit-media-id').value = mediaId;
    const img = document.getElementById('edit-media-preview');
    img.src = url;
    document.getElementById('edit-title').value = meta.file_name || '';
    document.getElementById('edit-title').focus();
    document.getElementById('edit-title').select();
    document.getElementById('edit-alt').value = meta.alt || '';
    document.getElementById('edit-description').value = meta.description || '';
    document.getElementById('edit-file-name').textContent = meta.file_name || '';
    document.getElementById('edit-file-size').textContent = meta.size ? (meta.size/1024).toFixed(2) + ' KB' : '';
    document.getElementById('edit-file-type').textContent = meta.mime_type || '';
    document.getElementById('edit-upload-date').textContent = meta.created_at || '';
    document.getElementById('edit-file-url').href = url;
    new bootstrap.Modal(document.getElementById('media-edit-modal')).show();
}
// Save metadata and manipulated image
const editForm = document.getElementById('media-edit-form');
if (editForm) {
    editForm.onsubmit = async function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-media-id').value;
        const formData = new FormData();
        formData.append('title', document.getElementById('edit-title').value);
        formData.append('alt', document.getElementById('edit-alt').value);
        formData.append('description', document.getElementById('edit-description').value);
        sendEditForm(id, formData);
    };
}
async function sendEditForm(id, formData) {
    formData.append('_token', document.querySelector('input[name=_token]').value);
    const url = "{{ route('media-library.update', ':id') }}".replace(':id', id);
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-HTTP-Method-Override': 'PUT' },
        body: formData
    });
    if (res.ok) {
        location.reload();
    } else {
        alert('Failed to update media.');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    Array.from(document.querySelectorAll('.media-thumb')).forEach(function(thumb) {
        thumb.onclick = async function(e) {
            if (e.target.closest('.btn-danger')) return; // skip delete
            const mediaId = thumb.querySelector('.delete-media-form').getAttribute('data-id');
            // Use Laravel route helper for AJAX metadata fetch
            const url = "{{ route('media-library.show', ':id') }}".replace(':id', mediaId);
            const res = await fetch(url);
            if (res.ok) {
                const meta = await res.json();
                openEditModal(mediaId, meta.url, meta);
            }
        };
    });
});
</script>
<x-admin.footer />

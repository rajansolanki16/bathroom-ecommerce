<div class="d-flex justify-content-between align-items-center mb-3">
    {{-- <h5 class="mb-0">Select Media</h5> --}}
    <button type="button" class="btn btn-sm btn-outline-primary" id="picker-upload-toggle"><i class="bi bi-upload"></i> Upload</button>
</div>
<form id="picker-upload-form" enctype="multipart/form-data" style="display:none;" class="mb-3">
    @csrf
    <div class="input-group">
        <input type="file" name="file[]" id="picker-upload-input" class="form-control" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
        <button type="submit" class="btn btn-primary">Upload</button>
    </div>
    <div id="picker-upload-progress" class="small text-muted mt-1"></div>
</form>
<div id="picker-media-grid">
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 16px;">
@foreach($media as $item)
    @php
        $mime = $item->mime_type;
        $icon = null;
        if(Str::startsWith($mime, 'image/')) {
            $icon = null;
        } elseif(Str::startsWith($mime, 'video/')) {
            $icon = 'bi bi-file-earmark-play text-primary';
        } elseif(Str::startsWith($mime, 'audio/')) {
            $icon = 'bi bi-file-earmark-music text-success';
        } elseif(Str::contains($mime, 'pdf')) {
            $icon = 'bi bi-file-earmark-pdf text-danger';
        } elseif(Str::contains($mime, ['excel', 'spreadsheet', 'xls', 'xlsx'])) {
            $icon = 'bi bi-file-earmark-excel text-success';
        } elseif(Str::contains($mime, ['word', 'doc', 'docx'])) {
            $icon = 'bi bi-file-earmark-word text-primary';
        } elseif(Str::contains($mime, ['powerpoint', 'ppt', 'pptx'])) {
            $icon = 'bi bi-file-earmark-ppt text-warning';
        } elseif(Str::contains($mime, ['zip', 'rar'])) {
            $icon = 'bi bi-file-earmark-zip text-secondary';
        } else {
            $icon = 'bi bi-file-earmark text-muted';
        }
    @endphp
    <div class="media-thumb position-relative" style="background: #fff; border-radius: 6px; overflow: hidden; height: 120px; width: 120px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e2e2e2; margin: auto; transition: box-shadow 0.2s;"
        data-id="{{ $item->id }}">
        @if(Str::startsWith($mime, 'image/'))
            <img src="{{ $item->getUrl() }}" alt="Media" style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 0;">
        @else
            <div class="d-flex flex-column align-items-center justify-content-center w-100 h-100">
                <i class="{{ $icon }}" style="font-size: 2rem;"></i>
                <span class="small mt-1">{{ strtoupper(pathinfo($item->file_name, PATHINFO_EXTENSION)) }}</span>
                <span class="text-truncate small mt-1" style="max-width: 90px;" title="{{ $item->file_name }}">{{ \Illuminate\Support\Str::limit($item->file_name, 14) }}</span>
            </div>
        @endif
    </div>
@endforeach
</div>

</div>
<script>

$(document).off('click', '#picker-upload-toggle').on('click', '#picker-upload-toggle', function() {
    $('#picker-upload-form').toggle();
});


$(document).off('click', '#picker-media-grid .media-thumb').on('click', '#picker-media-grid .media-thumb', function () {
    var selectedMediaId = $(this).data('id');
    var imgUrl = $(this).find('img').attr('src');
    var modal = $(this).closest('.modal');
    var hiddenInput = $(window.pickerHiddenInputSelector || '#media_library_logo_id');
    var previewDiv = $(window.pickerPreviewSelector || '#selected-media-preview');
    hiddenInput.val(selectedMediaId);
    if(imgUrl) {
        previewDiv.html(`<img src="${imgUrl}" style="height:100px;width:100px;object-fit:cover;border-radius:4px;">`);
    } else {
        previewDiv.html('<span class="badge bg-secondary">No Image</span>');
    }
    modal.find('.btn-close').trigger('click');
});

$(document).off('submit', '#picker-upload-form').on('submit', '#picker-upload-form', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $('#picker-upload-progress').text('Uploading...');
    $.ajax({
        url: '{{ route('media-library.store') }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(res) {
            $('#picker-upload-progress').text('Upload successful!');
            if(res.media && Array.isArray(res.media)) {
                res.media.forEach(function(item) {
                    var mime = item.mime_type;
                    var icon = null;
                    if(mime.startsWith('image/')) {
                        icon = null;
                    } else if(mime.startsWith('video/')) {
                        icon = 'bi bi-file-earmark-play text-primary';
                    } else if(mime.startsWith('audio/')) {
                        icon = 'bi bi-file-earmark-music text-success';
                    } else if(mime.includes('pdf')) {
                        icon = 'bi bi-file-earmark-pdf text-danger';
                    } else if(/[excel|spreadsheet|xls|xlsx]/i.test(mime)) {
                        icon = 'bi bi-file-earmark-excel text-success';
                    } else if(/[word|doc|docx]/i.test(mime)) {
                        icon = 'bi bi-file-earmark-word text-primary';
                    } else if(/[powerpoint|ppt|pptx]/i.test(mime)) {
                        icon = 'bi bi-file-earmark-ppt text-warning';
                    } else if(/[zip|rar]/i.test(mime)) {
                        icon = 'bi bi-file-earmark-zip text-secondary';
                    } else {
                        icon = 'bi bi-file-earmark text-muted';
                    }
                    var html = '';
                    html += '<div class="media-thumb position-relative" style="background: #fff; border-radius: 6px; overflow: hidden; height: 120px; width: 120px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e2e2e2; margin: auto; transition: box-shadow 0.2s;" data-id="'+item.id+'">';
                    if(mime.startsWith('image/')) {
                        html += '<img src="'+item.original_url+'" alt="Media" style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 0;">';
                    } else {
                        html += '<div class="d-flex flex-column align-items-center justify-content-center w-100 h-100">';
                        html += '<i class="'+icon+'" style="font-size: 2rem;"></i>';
                        var ext = item.file_name.split('.').pop().toUpperCase();
                        html += '<span class="small mt-1">'+ext+'</span>';
                        html += '<span class="text-truncate small mt-1" style="max-width: 90px;" title="'+item.file_name+'">'+item.file_name.substring(0,14)+'</span>';
                        html += '</div>';
                    }
                    html += '</div>';
                    $('#picker-media-grid > div').prepend(html);
                });
            }
            // Optionally clear file input
            $('#picker-upload-input').val('');
        },
        error: function(xhr) {
            $('#picker-upload-progress').text('Upload failed.');
        }
    });
});
</script>
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
<div class="mt-2">{{ $media->links() }}</div>
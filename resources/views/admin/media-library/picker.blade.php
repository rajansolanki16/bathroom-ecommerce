<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 16px;">
@foreach($media as $item)
    <div class="media-thumb position-relative" style="background: #fff; border-radius: 6px; overflow: hidden; height: 120px; width: 120px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.07); border: 1px solid #e2e2e2; margin: auto; transition: box-shadow 0.2s;"
        data-id="{{ $item->id }}">
        <img src="{{ $item->getUrl() }}" alt="Media" style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 0;">
    </div>
@endforeach
</div>
<div class="mt-2">{{ $media->links() }}</div>
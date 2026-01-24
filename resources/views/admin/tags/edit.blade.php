<x-admin.header :title="'Product Edit Tags'" />
<div class="card">
    <div class="card-header d-flex align-items-center">
        <div class="flex-grow-1">
            <h5 class="mb-4 card-title">{{ __('tags.tag_management') }}</h5>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">{{ __('tags.edit_tag') }}</h4>
            </div>

            <div class="card-body">
                <p class="text-muted">{{ __('tags.edit_description') }}</p>
                <form action="{{ route('tags.update', $tag->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="tag" class="form-label">{{ __('tags.tag_title') }} <span class="text-danger">{{ __('tags.required_mark') }}</span></label>
                        <input type="text" name="name" id="tag" class="form-control @error('name') is-invalid @enderror" placeholder="Enter tag title" value="{{ old('name', isset($tag) ? $tag->name : '') }}">

                        @error('name')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="gap-2 mb-3 hstack justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ __('tags.update_button') }}</button>
                        <a href="{{ route('tags.index') }}" class="btn btn-danger">{{ __('tags.cancel_button') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
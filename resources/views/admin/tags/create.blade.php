<x-admin.header :title="'product Tags create'" />

<x-page-title :title="__('tags.create_tag')"  :breadcrumbs="[__('tags.tags'), __('tags.create_tag')]" />

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">{{ __('tags.create_tag') }}</h4>
            </div>

            <div class="card-body">
                <p class="text-muted">{{ __('tags.tag_description') }}</p>
                <form action="{{ route('tags.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="tags" class="form-label">{{ __('tags.tag_title') }}<span class="text-danger">{{ __('tags.required_mark') }}</span></label>
                        <input type="text" name="name" id="tags" class="form-control @error('name') is-invalid @enderror" placeholder="Enter tag title">

                        @error('name')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-1 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ route('tags.index') }}" class="btn btn-danger">{{ __('tags.cancel_button') ?? 'Back' }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('tags.create_button') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />
<x-admin.header :title="'Edit Color'" />

<div class="container-fluid">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Edit Color</h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('colors.update', $color->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Color Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $color->name) }}" placeholder="Enter brand name"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Show on Home</label>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_on_home" name="show_on_home"
                                value="1" {{ old('show_on_home', $color->show_on_home) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_on_home">
                                Enabled
                            </label>
                        </div>
                    </div>


                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('colors.index') }}" class="btn btn-danger">Cancel</a>
                        <button type="submit" class="btn btn-primary"> Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<x-admin.footer />

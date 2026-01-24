<x-admin.header :title="'Add Blog'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-4 card-title">Add Blog</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<form class="store-blogs" action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Blog Title</h5>
                            <p class="text-muted">Blog Title Information refers to the data related to the titles of
                                blog posts.</p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Blog Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="Enter blog title" required>
                                @error('title')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Blog Category</h5>
                            <p class="text-muted">Blog Category Information refers to the data related to the categories of blog posts.</p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Blog Category <span
                                        class="text-danger">*</span></label>
                                        <select name="category[]" multiple class="form-control @error('category') is-invalid @enderror">
                                            @foreach ($blog_categories as $blog_category)
                                                <option value="{{ $blog_category->id }}" {{ is_array(old('category')) && in_array($blog_category->id, old('category')) ? 'selected' : '' }}>
                                                    {{ $blog_category->name }}
                                                </option>
                                            @endforeach
                                        </select>  
                                        <small class="text-muted d-flex justify-content-center mt-2">Press and Hold CTRL for Multiple Category</small>                                                                
                                @error('category')
                                    <span class="form-error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Blog Description</h5>
                            <p class="text-muted">Blog Description Information refers to the summary or excerpt of a
                                blog post that provides a brief overview of its content.</p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label class="form-label">Blog Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" placeholder="Enter blog description" rows="5"
                                    class="myeditor form-control @error('description') is-invalid @enderror"></textarea>
                                @error('description')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Blog Media</h5>
                            <p class="text-muted">Blog Media Information refers to the visual and multimedia element
                                associated with a blog post.</p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label class="form-label">Blog Images <span class="text-danger">*</span></label>
                                <input type="file" name="image" id="image" class="form-control" required>
                                @error('image')
                                    <span class="form-error-message text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="gap-2 mb-3 hstack justify-content-end">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-danger">Cancel</button>
    </div>
</form>

<script src="{{ publicPath('admin/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '#description',
            height: 300,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: "body { font-family: Arial, sans-serif; font-size: 14px; }"
        });
    });
</script>

<x-admin.footer />


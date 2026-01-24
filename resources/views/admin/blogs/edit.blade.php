<x-admin.header :title="'Edit Blog'" />

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">Edit Blog</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<form class="store-blogs" action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <h5 class="mb-3 card-title">Blog Title</h5>
                            <p class="text-muted">
                                Blog Title Information refers to the data related to the titles
                                of blog posts.
                            </p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Blog Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('name', $blog->title) }}" />
                                @error('title')
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
                            <h5 class="mb-3 card-title">Blog Category</h5>
                            <p class="text-muted">Blog Category Information refers to the data related to the categories of blog posts.</p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Blog Category <span
                                        class="text-danger">*</span></label>
                                        <select name="category[]" multiple class="form-control @error('category') is-invalid @enderror">
                                            @foreach ($blog_categories as $blog_category)
                                                <option value="{{ $blog_category->id }}"
                                                    @if (in_array($blog_category->id, old('category', $blog->categories->pluck('id')->toArray())))
                                                        selected
                                                    @endif>
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
                            <p class="text-muted">
                                Blog Description Information refers to the summary or excerpt of
                                a blog post that provides a brief overview of its content.
                            </p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label class="form-label">Blog Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="myeditor @error('description') is-invalid @enderror form-control" id="description"
                                    cols="3" rows="5">
                               {{ old('description', $blog->description) }}</textarea>
                                @error('description')
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
                            <h5 class="mb-3 card-title">Blog Media</h5>
                            <p class="text-muted">
                                Blog Media Information refers to the visual and multimedia
                                element associated with a blog post.
                            </p>
                        </div>
                        <div class="col-xxl-8">
                            <div class="mb-3">
                                <label class="form-label">Blog Image <span class="text-danger">*</span></label>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="media-upload main-image-upload form-control @error('image') is-invalid @enderror" />
                                <small class="text-muted d-flex justify-content-center mt-2">please leave it blank if you do not wants to change.</small>
                                @error('image')
                                    <span class="form-error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border rounded">
        <div class="flex-wrap gap-2 p-2 d-flex">
            <div class="flex-shrink-0 me-3">
                <div class="p-2 rounded avatar-sm bg-light">
                    @if ($blog->image)
                        <img data-dz-thumbnail="" class="rounded img-fluid d-block" src="{{ publicPath($blog->image) }}">
                    @endif
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="pt-1">
                    <h5 class="mb-1 fs-md" data-dz-name=""></h5>
                    <p class="mb-0 fs-sm text-muted" data-dz-size="">
                        <strong></strong>
                    </p>
                    <strong class="error text-danger" data-dz-errormessage=""></strong>
                </div>
            </div>
            
        </div>
    </div>
    <div class="gap-2 mb-3 hstack justify-content-end">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-danger">Cancel</button>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: "#description",
            height: 300,
            menubar: false,
            plugins: "advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help",
            toolbar: "undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help",
            content_style: "body { font-family: Arial, sans-serif; font-size: 14px; }",
        });
    });
</script>

<x-admin.footer />

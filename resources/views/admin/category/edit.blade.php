<x-admin.header :title="'product Categories edit'" />
<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Category Edit</h4>

            <div class="page-title-right">
                <ol class="m-0 breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Category</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">{{ __('category.Edit_Category') }}</h4>
            </div>

            <div class="card-body">
                <p class="text-muted">{{ __('category.Edit_Description') }}</p>

                <form action="{{ route('categories.update', $category->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="category" class="form-label">{{ __('category.Category_Title') }} <span class="text-danger">{{ __('category.required_mark') }}</span></label>

                        <input type="text" name="name" id="category" class="form-control @error('name') is-invalid @enderror" placeholder="Enter category title" value="{{ old('name', isset($category) ? $category->name : '') }}">

                        @error('name')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subcategory" class="form-label">{{ __('category.Parent_Category') }} <span class="text-danger">{{ __('category.required_mark') }}</span></label>


                        <select name="parent_id" id="subcategory" class="form-control">
                            <option value="">Select parent Category</option>
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', isset($category) ? $category->parent_id : '') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                            @endforeach
                        </select>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" name="is_visible" value="1" class="form-check-input" {{ old('is_visible', $category->is_visible) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isvisibleInput">Is Visible</label>
                            </div>
                        </div>

                    </div>

                    <div class="gap-2 mb-3 hstack justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ __('category.Update_Button') }}</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-danger">{{ __('category.Cancel_Button') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
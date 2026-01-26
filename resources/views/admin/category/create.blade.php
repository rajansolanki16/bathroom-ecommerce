<x-admin.header :title="'Product Categories'" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

<x-page-title title="Category Create" :breadcrumbs="['Category', 'Create']" />

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 card-title">{{ __('category.Create_Category') }}</h4>
            </div>

            <div class="card-body">
                <p class="text-muted">{{ __('category.Category_Description') }}</p>
                <form action="{{ route('categories.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="category" class="form-label">{{ __('category.Category_Title') }}<span class="text-danger">{{ __('category.required_mark') }}</span></label>

                        <input type="text" name="name" id="category" class="form-control @error('name') is-invalid @enderror" placeholder="Enter category title">

                        @error('name')
                        <div class="invalid-response" style="display:flex">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subcategory" class="form-label">{{ __('category.Parent_Category') }} <span class="text-danger">{{ __('category.required_mark') }}</span></label>

                        <select name="parent_id" id="subcategory" class="form-control">
                            <option value="">Select parent Category</option>
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">
                                {{ $parent->name }}
                            </option>
                            @endforeach
                        </select> <br>  

                        <div class="mb-3">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" name="is_visible" id="isvisibleInput" value="1" class="form-check-input" checked>
                                <label class="form-check-label" for="isvisibleInput">
                                    Is Visible
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="mb-1 text-end">
                        <button type="submit" class="btn btn-primary">{{ __('category.Create_Button') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<x-admin.footer />
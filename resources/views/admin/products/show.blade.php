<x-admin.header :title="'Product Details'" />

<div class="container-fluid">

    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ $product->product_title }}</h4>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ph-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- LEFT : IMAGES -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">

                    <h6 class="fw-semibold mb-3">Product Image</h6>

                    <img
                        src="{{ $product->product_image ? asset('storage/'.$product->product_image) : asset('admin/images/no-image.png') }}"
                        class="img-fluid rounded mb-3"
                        style="max-height: 260px; object-fit: cover"
                        alt="Product Image">

                    @if (!empty($product->gallery_images))
                        <hr>
                        <h6 class="fw-semibold mb-2">Gallery Images</h6>
                        <div class="row g-2">
                            @foreach ($product->gallery_images as $image)
                                <div class="col-4">
                                    <img
                                        src="{{ asset('storage/'.$image) }}"
                                        class="img-fluid rounded"
                                        style="height:80px; object-fit:cover">
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- RIGHT : DETAILS -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title mb-3">Product Information</h5>

                    <div class="row gy-3">

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Title</p>
                            <h6>{{ $product->product_title }}</h6>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Product Type</p>
                            <span class="badge bg-info">
                                {{ ucfirst($product->product_type) }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Price</p>
                            <h6>₹{{ number_format($product->price, 2) }}</h6>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Stock Status</p>
                            @if ($product->stock)
                                <span class="badge bg-success">In Stock</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Status</p>
                            <span class="badge bg-primary">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Visibility</p>
                            <span class="badge bg-warning text-dark">
                                {{ ucfirst($product->visibility) }}
                            </span>
                        </div>

                        <div class="col-6">
                            <p class="mb-1 text-muted">Categories</p>
                            <p>
                                {{ $product->categories->pluck('name')->join(', ') ?: '-' }}
                            </p>
                        </div>

                         <div class="col-6">
                            <p class="mb-1 text-muted">Brand</p>
                            <p>
                                {{ $product->brand->name ?? '-' }}
                            </p>
                        </div>

                        <div class="col-12">
                            <p class="mb-1 text-muted">Tags</p>
                            @forelse ($product->tags as $tag)
                                <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                            @empty
                                <span class="text-muted">-</span>
                            @endforelse
                        </div>

                        <div class="col-12">
                            <p class="mb-1 text-muted">Short Description</p>
                            <p>{{ $product->short_description }}</p>
                        </div>

                        <div class="col-12">
                            <p class="mb-1 text-muted">Product Description</p>
                            <div class="border rounded p-3">
                                {!! $product->product_decscription !!}
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="#">
                            <i class="ph-pencil"></i> Edit Product
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-light">
                            Back
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<x-admin.footer />

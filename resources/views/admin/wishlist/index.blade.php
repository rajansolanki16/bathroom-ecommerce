<x-admin.header :title="'User Added product Wishlist Listings'" />

<div class="col-xl-12">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-nowrap">
            <h4 class="mb-0 card-title">Wishlist Items</h4>
        </div>

        <div class="card-body">
            <p class="text-muted">This is a wishlist Items </p>
            <div class="table-responsive">
                <table id="fixed-header" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Added On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wishlists as $item)
                        <tr>
                            <!-- User -->
                            <td>
                                {{ $item->user->name  }} <br>
                                <small>{{ $item->user->email ?? '' }}</small>
                            </td>

                            <!-- Product (Image + Name) -->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img
                                        src="{{ $item->product->product_image
                                        ? asset('storage/'.$item->product->product_image)
                                        : asset('assets/images/no-image.png') }}"
                                        width="60"
                                        class="rounded">
                                    <span>{{ $item->product->product_title }}</span>
                                </div>
                            </td>

                            <!-- Price -->
                            <td>₹{{ $item->product->price }}</td>

                            <!-- Date -->
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                No wishlist data found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />
@extends('layout.app')

@section('title', 'Your Inquiry Cart - Hardware Store')

@section('content')
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Inquiry Cart</h1>
                    <p class="mb-4">Review your selected hardware and bathroom fixtures before submitting your inquiry.</p>
                </div>
            </div>
            <div class="col-lg-7"></div>
        </div>
    </div>
</div>
<div class="untree_co-section before-footer-section">
    <div class="container">
        @if(empty($cart))
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="alert alert-info">No products added for inquiry.</div>
                    <a href="{{ url('/') }}" class="btn btn-black">Back to Shop</a>
                </div>
            </div>
        @else
            <div class="row mb-5">
                <form class="col-md-12" method="post">
                    <div class="site-blocks-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotal = 0; @endphp
                                @foreach($cart as $item)
                                    @php
                                        $total = $item['price'] * $item['quantity'];
                                        $grandTotal += $total;
                                    @endphp
                                    <tr id="cart-row-{{ $item['id'] }}">
                                        <td class="product-thumbnail">
                                            <img src="{{ $item['image'] ?: asset('assets/images/no-image.png') }}" alt="Image" class="img-fluid">
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black">{{ $item['name'] }}</h2>
                                        </td>
                                        <td>₹{{ number_format($item['price'], 2) }}</td>
                                        <td>
                                            <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-black decrease qty-decrease" data-id="{{ $item['id'] }}" type="button">&minus;</button>
                                                </div>
                                                <input type="text" class="form-control text-center quantity-amount update-quantity" 
                                                       value="{{ $item['quantity'] }}" 
                                                       data-id="{{ $item['id'] }}" 
                                                       data-price="{{ $item['price'] }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-black increase qty-increase" data-id="{{ $item['id'] }}" type="button">&plus;</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="item-total">₹{{ number_format($total, 2) }}</td>
                                        <td>
                                            <button type="button"
        class="btn btn-black btn-sm remove-from-cart"
        data-id="{{ $item['id'] }}"
        data-row="cart-row-{{ $item['id'] }}">
    X
</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <button class="btn btn-black btn-sm btn-block" onclick="window.location.reload();">Update Cart</button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('/') }}" class="btn btn-outline-black btn-sm btn-block">Continue Shopping</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pl-5">
                    <div class="row justify-content-end">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12 text-right border-bottom mb-5">
                                    <h3 class="text-black h4 text-uppercase">Inquiry Summary</h3>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <span class="text-black">Subtotal</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black">₹{{ number_format($grandTotal, 2) }}</strong>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <span class="text-black">Total</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black" id="grand-total">₹{{ number_format($grandTotal, 2) }}</strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    {{-- Changed from checkout to inquiry route --}}
                                    <a href="{{ route('checkout') ?? '#' }}" class="btn btn-black btn-lg py-3 btn-block">Proceed To Inquiry</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Remove Item Modal -->
<div class="modal fade" id="removeCartModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h5 class="mb-3">Remove Item?</h5>
      <p class="mb-4">Are you sure you want to remove this product from your inquiry cart?</p>

      <div class="d-flex justify-content-center gap-3">
        <button type="button" class="btn btn-outline-black" data-bs-dismiss="modal">
            Cancel
        </button>
        <button type="button" class="btn btn-black" id="confirmRemoveBtn">
            Yes, Remove
        </button>
      </div>
    </div>
  </div>
</div>


<script>
    window.cartAddUrl = "{{ route('cart.add') }}";
    window.cartRemoveUrl = "{{ route('cart.remove', ':id') }}";
    window.cartUpdateUrl = "{{ route('cart.update', ':id') }}";
    </script>
@push('scripts')
<script>
let removeProductId = null;
let removeRowId     = null;

// 🔹 Open modal on click
$(document).on('click', '.remove-from-cart', function () {
    removeProductId = $(this).data('id');
    removeRowId     = $(this).data('row');

    $('#removeCartModal').modal('show');
});

// 🔹 Confirm remove
$('#confirmRemoveBtn').on('click', function () {

    if (!removeProductId) return;

    let url = window.cartRemoveUrl.replace(':id', removeProductId);

    $.ajax({
        url: url,
        type: "DELETE",
        success: function (response) {

            $('#removeCartModal').modal('hide');

            if (response.status === 'success') {

                $('#' + removeRowId).fadeOut(300, function () {
                    $(this).remove();
                });

                $('#grand-total').text('₹' + Number(response.grandTotal).toFixed(2));

                // if (response.count == 0) {
                //     setTimeout(() => location.reload(), 400);
                // }
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('Error removing item');
        }
    });
});
</script>
@endpush


@endsection

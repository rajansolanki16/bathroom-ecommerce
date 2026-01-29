// Fetch and display products 
$(document).ready(function() {
    fetchProducts();
});

// Function to fetch products
function fetchProducts(page = 1) {
    var $grid = $('#vec_product-grid');
    if (!$grid.length) return;

    var url = $grid.data('fetch-url');
    url = `${url}?page=${page}`; 

    $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {
            if (!response.html.trim()) {
                $grid.html('<div class="ko-col-12">No products found</div>');
                return;
            }
            
            $grid.html(response.html);
            $('.pagination').html(response.pagination);
            window.history.pushState({}, '', `?page=${page}`);
            applyGuestWishlistUI();

        },
        error: function(xhr, status, error) {
            $grid.html('<div class="ko-col-12 text-danger">Error loading products</div>');
        }
    });
}

// Handle pagination link clicks
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();  
    var page = $(this).attr('href').split('page=')[1]; 
    fetchProducts(page);  
});

// Wishlist toggle
$(document).on('click', '.wishlist-btn', function (e) {
    e.preventDefault();

    let $btn = $(this);
    let productId = $btn.data('product-id');
    let wishlistUrl = $('#vec_product-grid').data('wishlist-url');
    let $icon = $btn.find('i');

    $.ajax({
        url: wishlistUrl,
        type: 'POST',
        data: {
            product_id: productId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            if (res.status === 'added') {
                $btn.addClass('added');
                $icon.removeClass('bi-heart').addClass('bi-heart-fill text-danger');
            } else {
                $btn.removeClass('added');
                $icon.removeClass('bi-heart-fill text-danger').addClass('bi-heart');
            }

            $('#wishlist-count').text(res.count);
        },
        error: function (xhr) {
            if (xhr.status === 401) {
                let list = getGuestWishlist();
                productId = parseInt(productId);
                if (list.includes(productId)) {
                    list = list.filter(id => id !== productId);
                    $btn.removeClass('added');
                    $icon.removeClass('bi-heart-fill text-danger').addClass('bi-heart');
                } else {
                    list.push(productId);
                    $btn.addClass('added');
                    $icon.removeClass('bi-heart').addClass('bi-heart-fill text-danger');
                }
                setGuestWishlist(list);
                $('#wishlist-count').text(list.length);
            }
        }
    });
});

// Cookie helpers
function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
}

// Delete cookie
function deleteCookie(name){ document.cookie = name + '=; Max-Age=-99999999; path=/'; }


// Send guest storage to server for merging
function sendGuestStorageToServer() {
    console.log('Attempting guest merge...');

    $.ajax({
        url: window.guestMergeUrl,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            console.log('Merge success:', res); 
        },
        error: function (xhr) {
            console.error('Merge failed:', xhr.responseText);
        }
    });
}

// Attempt to merge guest storage on login
window.tryMergeGuestStorage = sendGuestStorageToServer;
$(document).ready(function(){ sendGuestStorageToServer(); });

// Remove from wishlist
$(document).on('click', '.vec_wishlist_remove', function (e) {
    e.preventDefault();

    let productId = $(this).data('id');
    let row = $(this).closest('tr');
    $.ajax({
        url: wishlistDeleteUrl + '/' + productId,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            if (res.status === 'removed') {
                row.fadeOut(300, function () {
                    $(this).remove();
                });

                $('#wishlist-count').text(res.count);
            }
        },
        error: function (xhr) {
            console.error('Delete failed:', xhr.responseText);
        }
    });
});

// Add to cart
$(document).on('click', '.add-to-cart', function (e) {
    e.preventDefault();

    let productId = $(this).data('id');
    $.ajax({
        url: '/cart/add',
        type: 'POST',
        data: {
            product_id: productId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            $('#cart-count').text(res.count);
        },
        error: function (xhr) {

            if (xhr.status === 422) {
                alert(xhr.responseJSON.message);
            } 
            else {
                console.error(xhr.responseText);
                alert('Unable to add to cart');
            }
        }
    });
});

// Remove from cart
$(document).on('click', '.remove-from-cart', function(e) {
    e.preventDefault();
    
    var button = $(this);
    var productId = button.data('id');
    var rowId = button.data('row');
    
    $.ajax({
        url: '/cart/remove/' + productId,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#' + rowId).fadeOut(400, function() {
                    $(this).remove();
                    
                    $('#cart-count').text(response.count);
                    $('#grand-total').text('₹' + response.grandTotal);
                });
                
                console.log('Product removed from cart');
            }
        },
        error: function(xhr) {
            button.prop('disabled', false).text('Remove');
            alert(xhr.responseJSON?.message || 'Failed to remove product');
        }
    });
});


var updateTimeout;
// Update cart quantity with debounce
$(document).on('input', '.update-quantity', function() {
    var input = $(this);
    var productId = input.data('id');
    var quantity = parseInt(input.val()) || 1;
    
    if (quantity < 1) {
        quantity = 1;
        input.val(1);
    }
    
    clearTimeout(updateTimeout);
    
    updateTimeout = setTimeout(function() {
        updateCartQuantity(productId, quantity, input);
    }, 500);
});

// Immediate update on change event
$(document).on('change', '.update-quantity', function() {
    var input = $(this);
    var productId = input.data('id');
    var quantity = parseInt(input.val()) || 1;
    
    if (quantity < 1) {
        quantity = 1;
        input.val(1);
    }
    
    clearTimeout(updateTimeout);
    updateCartQuantity(productId, quantity, input);
});

// Function to update cart quantity via AJAX
function updateCartQuantity(productId, quantity, input) {
    input.prop('disabled', true);
    
    $.ajax({
        url: '/cart/update/' + productId,
        type: 'POST',
        data: {
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'success') {
                var row = input.closest('tr');
                row.find('.item-total').text('₹' + response.itemTotal.toLocaleString());
                $('#grand-total').text('₹' + response.grandTotal.toLocaleString());
                $('#cart-count').text(response.count);
                
                row.find('.item-total').addClass('text-success fw-bold');
                setTimeout(function() {
                    row.find('.item-total').removeClass('text-success fw-bold');
                }, 800);
                
                console.log('Cart quantity updated');
            }
            input.prop('disabled', false);
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Failed to update cart');
            input.prop('disabled', false);
        }
    });
}

// Update wishlist count for guest users
function updateWishlistCount() {
    if (window.isLoggedIn) {
        return;
    }

    let wishlist = getGuestWishlist();
    let count = Array.isArray(wishlist) ? wishlist.length : 0;

    if ($('#wishlist-count').length) {
        $('#wishlist-count').text(count);
    }
}

// Apply guest wishlist UI state
function applyGuestWishlistUI() {
    if (window.isLoggedIn) return;

    let wishlist = getGuestWishlist(); 

    $('.wishlist-btn').each(function () {
        let $btn = $(this);
        let productId = parseInt($btn.data('product-id'));
        let $icon = $btn.find('i');

        if (wishlist.includes(productId)) {
            $btn.addClass('added');
            $icon.removeClass('bi-heart')
                .addClass('bi-heart-fill text-danger');
        } else {
            $btn.removeClass('added');
            $icon.removeClass('bi-heart-fill text-danger')
                .addClass('bi-heart');
        }
    });
}

// Initial UI setup
applyGuestWishlistUI();
updateWishlistCount();

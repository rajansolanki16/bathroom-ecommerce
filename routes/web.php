<?php

// Libraries

use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Controllers
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoriesController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MediaController;
// use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentOptionsController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\User\WishListController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductReviewController;

//Auth
Route::get('/login', [RedirectController::class, 'login'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/sign-up', [AuthController::class, 'view_signup'])->name('view.signup');
Route::post('/sign-up', [AuthController::class, 'signup'])->name('auth.signup');
Route::get('/forgot-password', [RedirectController::class, 'forgotPassword'])->name('view.forget_password');
Route::post('/forgot-password', [AuthController::class, 'forgot_password'])->name('auth.password.otp');
Route::get('/verification/{token}', [AuthController::class, 'view_otp_verify'])->name('view.otp_verify');
Route::post('/verification', [AuthController::class, 'otp_verify'])->name('auth.otp_verify');
Route::get('/new-password/{token}', [RedirectController::class, 'newPassword'])->name('view.new_password');
Route::post('/new-password', [AuthController::class, 'new_password'])->name('auth.password');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/states', [AuthController::class, 'getStates'])->name("get.states");

//User
Route::get('/', [HomeController::class, 'list'])->name('view.home');
Route::get('/home', [HomeController::class, 'list'])->name('user.home');
Route::get('/product', [HomeController::class, 'list'])->name('user.product');

Route::get('/product/{slug}', [ProductController::class, 'userShow'])->name('product.user.show');
Route::post('/guest/merge', [AuthController::class, 'mergeGuestStorage'])->middleware('auth')->name('guest.merge');

Route::post('/wishlist/toggle', [WishListController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/wishlist', [WishListController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/delete/{id}', [WishListController::class, 'deleteById'])->name('wishlist.delete');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply.coupon');

Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove.coupon');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/reviews', [ProductReviewController::class, 'store'])->name('reviews.store');

//admin panel
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'show_admin'])->name('view.admin.dashboard');
        Route::resource('/blogs', BlogController::class)->names('blogs');
        Route::resource('/blog-categories', BlogCategoriesController::class)->names('blog_categories');
        Route::resource('/room-services', ServiceController::class)->names('services');
        Route::resource('/room-amenities', AmenityController::class)->names('amenities');
        Route::resource('/rooms', RoomController::class)->names('rooms');

        Route::post('/products/{product}/variants/update', [ProductController::class, 'updateVariants'])->name('products.variants.update');
        Route::post('/products/{product}/variants/remove', [ProductController::class, 'removeVariant'])->name('products.variants.remove');
        Route::post('/products/generate-variants', [ProductController::class, 'generateVariants'])->name('products.generate.variants');
        Route::resource('/products', ProductController::class)->names('products');
        Route::resource('/categories', CategoryController::class)->names('categories');
        Route::resource('/tags', TagsController::class)->names('tags');
        Route::resource('/coupons', CouponController::class)->names('coupons');
        Route::resource('/payment-options', PaymentOptionsController::class)->names('paymentoptions');
        Route::resource('/product-attributes', ProductAttributeController::class)->names('product_attributes');
        Route::resource('/attribute-values', AttributeValueController::class)->names('attribute_values');

        Route::get('/wishlist/show', [WishListController::class, 'showadmin'])->name('wishlist.show');

        //order routes
        Route::get('/orders', [OrderController::class, 'indexshow'])->name('orders.show');
         Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        Route::prefix('settings')->group(function () {

            Route::get('/about-us', [SettingController::class, 'show_about_us'])->name('view.settings.about');
            Route::post('/about_us', [SettingController::class, 'save_about_us'])->name('settings.about.save');

            Route::get('/env', [SettingController::class, 'show_env'])->name('view.settings.env');
            Route::post('/env', [SettingController::class, 'save_env'])->name('settings.env.save');

            Route::resource('/faqs', FaqController::class)->names('faqs');

            Route::get('/general', [SettingController::class, 'show_general'])->name('view.settings.general');
            Route::post('/general', [SettingController::class, 'save_general'])->name('settings.general.save');

            Route::get('/home', [SettingController::class, 'show_home'])->name('view.settings.home');
            Route::post('/home', [SettingController::class, 'save_home'])->name('settings.home.save');

            Route::get('/pages', [SettingController::class, 'show_pages'])->name('view.settings.pages');
            Route::post('/pages', [SettingController::class, 'save_pages'])->name('settings.pages.save');

            Route::get('/ecommerce', [SettingController::class, 'show_ecommerce'])->name('view.settings.ecommerce');
            Route::post('/ecommerce/store', [SettingController::class, 'store_ecommerce'])->name('settings.ecommerce.store');
        });

        Route::get('/offline-booking', [BookingController::class, 'show_offline_booking'])->name('view.offline_booking');
        Route::post('/offline-booking', [BookingController::class, 'store_offline_booking'])->name('offline_booking.save');

        Route::get('/transactions', [BookingController::class, 'show_transactions'])->name('view.transactions');
        Route::get('/bookings', [BookingController::class, 'show_bookings'])->name('view.bookings');

        Route::get('/booking/{id}', [BookingController::class, 'show_single_booking'])->name('view.booking');
        // Route::get('/booking/{id}/edit', [BookingController::class, 'edit_booking'])->name('view.edit_booking');
        // Route::post('/booking/{id}/edit', [BookingController::class, 'save_edit_booking'])->name('edit_booking.save');
        Route::post('/booking/pay-status/{bid}', [BookingController::class, 'change_pay_status'])->name('booking_payment.change.save');
        Route::get('/users', [AdminController::class, 'show_users'])->name('view.users');
        Route::post('/remove-room-media', [RoomController::class, 'remove_room_media'])->name('rooms.media.remove');
        Route::post('/room-wise-services', [RoomController::class, 'room_wise_services'])->name('rooms.services');
    });
});

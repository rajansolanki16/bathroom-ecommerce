<?php

// Libraries

use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\User\WishListController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductReviewController;
use App\Http\Controllers\Admin\SalesmanController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\StoreVisitController;

// Auth
Route::get('/login', [RedirectController::class, 'login'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/sign-up', [AuthController::class, 'view_signup'])->name('view.signup');
Route::post('/sign-up', [AuthController::class, 'signup'])->name('auth.signup');
Route::get('/forgot-password', [RedirectController::class, 'forgotPassword'])->name('view.forget_password');
Route::post('/forgot-password', [AuthController::class, 'forgot_password'])->name('auth.password.otp');
Route::get('/verification/{token}', [AuthController::class, 'view_otp_verify'])->name('view.otp_verify');
Route::post('/verification', [AuthController::class, 'otp_verify'])->name('auth.otp_verify');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('auth.resend_otp');
Route::get('/new-password/{token}', [RedirectController::class, 'newPassword'])->name('view.new_password');
Route::post('/new-password', [AuthController::class, 'new_password'])->name('auth.password');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/states', [AuthController::class, 'getStates'])->name("get.states");
Route::post('/users/{user}/send-otp', [UserController::class, 'sendOtpEmail'])->name('users.send_otp');

// User
Route::get('/', [RedirectController::class, 'login'])->name('login');;
Route::get('/home', [HomeController::class, 'list'])->name('view.home');
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


Route::get('/user/orders/{order}', [OrderController::class, 'show'])->name('user.order.details');

// Route::middleware('auth')->group(function () {
//     Route::post('/activity/online', [UserActivityController::class, 'online']);
//     Route::post('/activity/offline', [UserActivityController::class, 'offline']);
//     Route::post('/activity/ping', [UserActivityController::class, 'ping']);
// });

//admin panel
Route::get('admin/dashboard', [AdminController::class, 'show_admin'])->name('admin.dashboard');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
    Route::get('/media-library/picker', [MediaLibraryController::class, 'picker'])->name('media-library.picker');
        // Route::get('/dashboard', [AdminController::class, 'show_admin'])->name('admin.dashboard');

        // Media Library
        Route::get('/media-library', [MediaLibraryController::class, 'index'])->name('media-library.index');
        Route::get('/media-library/{media}', [MediaLibraryController::class, 'show'])->name('media-library.show');
        Route::post('/media-library', [MediaLibraryController::class, 'store'])->name('media-library.store');
        Route::delete('/media-library/{media}', [MediaLibraryController::class, 'destroy'])->name('media-library.destroy');
        Route::put('/media-library/{media}', [MediaLibraryController::class, 'update'])->name('media-library.update');

        Route::post('/products/{product}/variants/update', [ProductController::class, 'updateVariants'])->name('products.variants.update');
        Route::post('/products/{product}/variants/remove', [ProductController::class, 'removeVariant'])->name('products.variants.remove');
        Route::post('/products/generate-variants', [ProductController::class, 'generateVariants'])->name('products.generate.variants');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
        Route::resource('/products', ProductController::class)->names('products');
        Route::resource('/categories', CategoryController::class)->names('categories');
        Route::resource('/brands', BrandController::class)->names('brands');
        Route::post('/brands/toggle-home', [BrandController::class, 'toggleHome'])->name('brands.toggle-home');
        Route::resource('/tags', TagsController::class)->names('tags');
        Route::resource('/coupons', CouponController::class)->names('coupons');
        // Route::resource('/payment-options', PaymentOptionsController::class)->names('paymentoptions');

        Route::get('/wishlist/show', [WishListController::class, 'showadmin'])->name('wishlist.show');

        Route::get('/orders/export/{type}', [OrderController::class, 'export'])->name('orders.export');

        Route::get('/orders', [OrderController::class, 'indexshow'])->name('orders.show');
        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('/orders/{order}/notes', [OrderController::class, 'updateNotes'])->name('orders.notes');

        // Color
        Route::get('/colors/create', [ColorController::class, 'create'])->name('colors.create');
        Route::get('/colors', [ColorController::class, 'index'])->name('colors.index');
        Route::post('/colors', [ColorController::class, 'store'])->name('colors.store');
        Route::get('/colors/{id}/edit', [ColorController::class, 'edit'])->name('colors.edit');
        Route::put('/colors/{id}', [ColorController::class, 'update'])->name('colors.update');
        Route::delete('/colors/{id}', [ColorController::class, 'destroy'])->name('colors.destroy');

        // stock
        Route::get('/stocks/create', [StockController::class, 'create'])->name('stocks.create');
        Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::get('/stocks/{id}', [StockController::class, 'show'])->name('stocks.show');
        // Route::get('/stocks/{id}/edit', [StockController::class, 'edit'])->name('stocks.edit');
        // Route::put('/stocks/{id}', [StockController::class, 'update'])->name('stocks.update');
        Route::delete('/stocks/{id}', [StockController::class, 'destroy'])->name('stocks.destroy');

        // settings
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

        Route::resource('/users', UserController::class)->names('users');
        Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::post('/users/{user}/toggle-approval', [UserController::class, 'toggleApproval'])->name('users.toggle-approval');

        Route::resource('/store_visits', StoreVisitController::class)->names('store_visits');
        Route::get('/store_visits/{storeVisit}', [StoreVisitController::class, 'show'])->name('visit_report.view');
        Route::post('/store_visits/{id}/approve', [StoreVisitController::class, 'approve'])->name('visit_report.approve');
        Route::post('/store_visits/{id}/reject', [StoreVisitController::class, 'reject'])->name('visit_report.reject');
        // Export routes
        Route::get('/store_visits-export/excel', [StoreVisitController::class, 'exportExcel'])->name('visit_report.export.excel');
        Route::get('/store_visits-export/pdf', [StoreVisitController::class, 'exportPdf'])->name('visit_report.export.pdf');

        Route::post('media/delete/{media}', function (
            \Spatie\MediaLibrary\MediaCollections\Models\Media $media
        ) {
            $media->delete();

            return response()->json(['success' => true]);
        })->name('media.delete');
    });
});

Route::middleware(['role:salesman'])->group(function () {
    Route::get('/salesman/visit/create', [SalesmanController::class, 'createVisit'])->name('salesman.visit.create');
    Route::post('/salesman/visit/store', [SalesmanController::class, 'storeVisit'])->name('salesman.visit.store');
    Route::get('/salesman/visit/{id}/edit', [SalesmanController::class, 'editVisit'])->name('salesman.visit.edit');
    Route::put('/salesman/visit/{id}', [SalesmanController::class, 'updateVisit'])->name('salesman.visit.update');
});
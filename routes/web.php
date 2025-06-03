<?php

use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Order\OrderController as OrderOrderController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;

Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
Route::get('/products', [UserProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [UserProductController::class, 'show'])->name('products.show');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile/update-info', [UserProfileController::class, 'updateInfo'])->name('user.profile.update.info');
    Route::put('/profile/update-password', [UserProfileController::class, 'updatePassword'])->name('user.profile.update.password');


    // CRUD Address
    Route::get('/address', [AddressController::class, 'index'])->name('user.address.index');
    Route::post('/address/store', [AddressController::class, 'store'])->name('user.address.store');
    Route::post('/address/store-new-from-checkout', [AddressController::class, 'storeNewFromCheckout'])->name('user.address.store-new-from-checkout');
    Route::put('/address/update/{address}', [AddressController::class, 'update'])->name('user.address.update');
    Route::delete('/address/delete/{address}', [AddressController::class, 'deleteAddress'])->name('user.address.delete');
    Route::patch('/address/set-default/{address}', [AddressController::class, 'setDefaultAddress'])->name('user.address.set_default');
    Route::patch('/address/set-active/{address}', [AddressController::class, 'setActiveAddress'])->name('user.address.set_active');
    Route::patch('/address/set-inactive/{address}', [AddressController::class, 'setInactiveAddress'])->name('user.address.set_inactive');

    Route::get('/cart', [CartController::class, 'index'])->name('user.cart.index');
    Route::get('/modal-cart', [CartController::class, 'getModalCart'])->name('user.modal.cart');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('user.cart.add');


    // /cart/decrement/
    Route::post('/cart/decrement/{itemsId}', [CartController::class, 'decrementCart'])->name('user.cart.decrement');
    Route::post('/cart/increment/{itemsId}', [CartController::class, 'incrementCart'])->name('user.cart.increment');
    Route::post('/cart/remove/{itemsId}', [CartController::class, 'removeCart'])->name('user.cart.remove');



    Route::post('/cart/checkout', [CartController::class, 'cartCheckout'])->name('user.cart.checkout');


    Route::get('/checkout', [CheckoutController::class, 'index'])->name('user.checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('user.checkout.process');


    Route::get('/orders', [OrderController::class, 'index'])->name('user.order.index');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('user.order.show');
    Route::get('/order/{order}/upload-proof', [OrderController::class, 'getUploadProof'])->name('user.order.get-upload-proof');
    Route::POST('/order/{order}/upload-proof', [OrderController::class, 'uploadProof'])->name('user.order.store-upload-proof');
    Route::post('/order/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('user.order.cancel');

    Route::post('/order/{order}/received', [OrderController::class, 'receivedOrder'])->name('user.order.received');
});

// admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard.index');

    // category
    Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        // Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}/update', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');

        // Additional routes for create, store, show, edit, update, destroy can be added here
    });

    // products
    Route::prefix('admin/products')->name('admin.products.')->group(function () {
        // Define product routes here
        // Example:
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}/update', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // order
    Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
        Route::get('/', [OrderOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderOrderController::class, 'show'])->name('show');
        Route::post('/{order}/{payment_history}/update-status', [OrderOrderController::class, 'updateStatus'])->name('update-status');
    });

    route::POST('/payment/update-amount/{history}', [OrderOrderController::class, 'updatePaymentAmount'])->name('admin.payment.update-amount');
});




require __DIR__ . '/auth.php';

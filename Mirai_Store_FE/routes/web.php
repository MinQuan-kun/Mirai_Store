<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GameAdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/debug', function () {
    $results = [];

    // Check PHP extensions
    $results['php_version'] = PHP_VERSION;
    $results['mongodb_extension'] = extension_loaded('mongodb') ? 'Loaded' : 'NOT LOADED';
    $results['app_key_set'] = !empty(env('APP_KEY')) ? 'Yes' : 'NO (MISSING!)';

    // Check DB Connection
    try {
        $db = \DB::connection()->getPdo();
        $results['db_connection'] = 'Success (SQL)';
    } catch (\Exception $e) {
        $results['db_connection_error'] = $e->getMessage();
    }

    // Check MongoDB Connection specifically
    try {
        $mongo = \DB::connection('mongodb')->getMongoClient();
        $results['mongodb_connection'] = 'Success';
    } catch (\Exception $e) {
        $results['mongodb_error'] = $e->getMessage();
    }

    return response()->json($results);
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/shop', [HomeController::class, 'shop'])->name('shop.index');
Route::get('/game/{id}', [HomeController::class, 'show'])->name('game.show');
Route::view('/community', 'community.index')->name('community.index');
Route::get('/gacha', [HomeController::class, 'gacha'])->name('gacha');
Route::post('/chatbot/send', [HomeController::class, 'chatbotSend'])->name('chatbot.send');
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout.process');
Route::post('/checkout/validate-discount', [App\Http\Controllers\CheckoutController::class, 'validateDiscount'])->name('checkout.validate.discount');


Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');


Route::middleware('auth.custom')->group(function () {
    Route::get('/wishlist', [HomeController::class, 'wishlist'])->name('wishlist.index');
    Route::post('/wishlist/add/{gameId}', [HomeController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{gameId}', [HomeController::class, 'removeFromWishlist'])->name('wishlist.remove');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/deposit', [WalletController::class, 'showDeposit'])->name('wallet.deposit');
    Route::post('/wallet/deposit/test', [WalletController::class, 'depositTest'])->name('wallet.test.deposit');
    Route::delete('/wallet/transactions/{id}', [WalletController::class, 'cancel'])->name('wallet.transaction.cancel');
    Route::post('/payment/momo/deposit', [PaymentController::class, 'momoDeposit'])->name('payment.momo.deposit');
    Route::post('/payment/paypal/deposit', [PaymentController::class, 'paypalDeposit'])->name('payment.paypal.deposit');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');


    Route::get('/user/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/user/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/user/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove']);
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Admin FE routes
    Route::prefix('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/games', [GameAdminController::class, 'index'])->name('admin.games.index');
        Route::get('/games/create', [GameAdminController::class, 'create'])->name('admin.games.create');
        Route::post('/games', [GameAdminController::class, 'store'])->name('admin.games.store');
        Route::get('/games/{id}/edit', [GameAdminController::class, 'edit'])->name('admin.games.edit');
        Route::put('/games/{id}', [GameAdminController::class, 'update'])->name('admin.games.update');
        Route::delete('/games/{id}', [GameAdminController::class, 'destroy'])->name('admin.games.destroy');
        Route::patch('/games/{id}/toggle-status', [GameAdminController::class, 'toggleStatus'])->name('admin.games.toggle-status');

        Route::get('/categories', [App\Http\Controllers\Admin\CategoryAdminController::class, 'index'])->name('admin.categories.index');
        Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryAdminController::class, 'create'])->name('admin.categories.create');
        Route::post('/categories', [App\Http\Controllers\Admin\CategoryAdminController::class, 'store'])->name('admin.categories.store');
        Route::get('/categories/{id}/edit', [App\Http\Controllers\Admin\CategoryAdminController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/categories/{id}', [App\Http\Controllers\Admin\CategoryAdminController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{id}', [App\Http\Controllers\Admin\CategoryAdminController::class, 'destroy'])->name('admin.categories.destroy');

        Route::get('/discounts', [App\Http\Controllers\Admin\DiscountAdminController::class, 'index'])->name('admin.discounts.index');
        Route::get('/discounts/create', [App\Http\Controllers\Admin\DiscountAdminController::class, 'create'])->name('admin.discounts.create');
        Route::post('/discounts', [App\Http\Controllers\Admin\DiscountAdminController::class, 'store'])->name('admin.discounts.store');
        Route::get('/discounts/{id}/edit', [App\Http\Controllers\Admin\DiscountAdminController::class, 'edit'])->name('admin.discounts.edit');
        Route::put('/discounts/{id}', [App\Http\Controllers\Admin\DiscountAdminController::class, 'update'])->name('admin.discounts.update');
        Route::delete('/discounts/{id}', [App\Http\Controllers\Admin\DiscountAdminController::class, 'destroy'])->name('admin.discounts.destroy');

        Route::get('/users', [App\Http\Controllers\Admin\UserAdminController::class, 'index'])->name('admin.users.index');
        Route::patch('/users/{id}/toggle-status', [App\Http\Controllers\Admin\UserAdminController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::patch('/users/{id}/role', [App\Http\Controllers\Admin\UserAdminController::class, 'updateRole'])->name('admin.users.update-role');
        Route::patch('/users/{id}/reset-password', [App\Http\Controllers\Admin\UserAdminController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserAdminController::class, 'destroy'])->name('admin.users.destroy');
    });
});

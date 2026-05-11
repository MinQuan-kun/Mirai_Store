<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/shop', [HomeController::class, 'shop'])->name('shop.index');
Route::get('/game/{id}', [HomeController::class, 'show'])->name('game.show');
Route::view('/community', 'community.index')->name('community.index');
Route::get('/gacha', [HomeController::class, 'index'])->name('gacha');
Route::get('/orders', [HomeController::class, 'index'])->name('orders.index');
Route::post('/chatbot/send', [HomeController::class, 'index'])->name('chatbot.send');
Route::get('/wishlist', [HomeController::class, 'index'])->name('wishlist.index');
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout.process');
Route::post('/checkout/validate-discount', [App\Http\Controllers\CheckoutController::class, 'validateDiscount'])->name('checkout.validate.discount');


Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');


Route::middleware('auth.custom')->group(function () {
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
});

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminPanel\RoleController;
use App\Http\Controllers\AdminPanel\UserController;
use App\Http\Controllers\AdminPanel\ProductController;
use App\Http\Controllers\AdminPanel\OrderController;

use App\Http\Controllers\StoreFront\HomePageController;
use App\Http\Controllers\StoreFront\CartController;
use App\Http\Controllers\StoreFront\CheckoutController;
use App\Http\Controllers\StoreFront\CustomerAddressController;
use App\Http\Controllers\StoreFront\AccountsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', [HomePageController::class, 'index'])->name('home');
Route::resource('cart', CartController::class)->except(['create', 'edit']);
Route::group(['middleware' => ['auth', 'web']], function () {
    Route::get('accounts', [AccountsController::class, 'index'])->name('accounts');
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('checkout/execute', [CheckoutController::class, 'executePayPalPayment'])->name('checkout.execute');
    Route::post('checkout/execute', [CheckoutController::class, 'charge'])->name('checkout.execute');
    Route::get('checkout/cancel', [CheckoutController::class,'cancel'])->name('checkout.cancel');
    Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::resource('customer.address', CustomerAddressController::class);
});

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
});
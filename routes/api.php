<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Customer\LoginController;
use App\Http\Controllers\Api\Customer\LogoutController;
use App\Http\Controllers\Api\Public\CategoryController;
use App\Http\Controllers\Api\Customer\MyOrderController;
use App\Http\Controllers\Api\Customer\MyProfileController;
use App\Http\Controllers\Api\Customer\RatingController;
use App\Http\Controllers\Api\Customer\RegisterController;
use App\Http\Controllers\Api\Public\CartController;
use App\Http\Controllers\Api\Public\ProductController;
use App\Http\Controllers\Api\Public\RajaOngkirController;
use App\Http\Controllers\Api\Public\SliderController;

Route::group(['prefix' => 'customer'], function () {
    Route::post('/register', RegisterController::class)->name('customer.register');
    Route::post('/login', LoginController::class)->name('customer.login');
    Route::post('/logout', LogoutController::class)->name('customer.logout');
    Route::get('/my-orders', [MyOrderController::class, 'index'])->name('customer.my-orders');
    Route::get('/my-orders/{snap_token}', [MyOrderController::class, 'show'])->name('customer.my-orders.show');
    Route::get('/profile', [MyProfileController::class, 'index'])->name('customer.profile');
    Route::post('/profile', [MyProfileController::class, 'update'])->name('customer.profile.update');
    Route::post('/ratings', RatingController::class)->name('customer.ratings');
});

Route::group(['prefix' => 'public'], function () {
    Route::get('/sliders', SliderController::class)->name('public.sliders');
    Route::get('/categories', [CategoryController::class, 'index'])->name('public.categories');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('public.categories.show');
    Route::get('/products', [ProductController::class, 'index'])->name('public.products');
    Route::get('/products-popular', [ProductController::class, 'ProductPopular'])->name('public.products.popular');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('public.products.show');
    Route::get('/carts', [CartController::class, 'index'])->name('public.carts');
    Route::post('/carts', [CartController::class, 'store'])->name('public.carts.store');
    Route::post('/carts/increment', [CartController::class, 'IncrementCart'])->name('public.carts.increment');
    Route::post('/carts/decrement', [CartController::class, 'DecrementCart'])->name('public.carts.decrement');
    Route::delete('/carts/{id}', [CartController::class, 'destroy'])->name('public.carts.destroy');
    Route::get('/search-destination', [RajaOngkirController::class, 'searchDestination'])->name('public.search-destination');
    Route::post('/check-ongkir', [RajaOngkirController::class, 'checkOngkir'])->name('public.ongkir');

});

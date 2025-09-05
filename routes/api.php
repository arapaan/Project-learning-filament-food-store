<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Customer\LoginController;
use App\Http\Controllers\Api\Customer\LogoutController;
use App\Http\Controllers\Api\Public\CategoryController;
use App\Http\Controllers\Api\Customer\MyOrderController;
use App\Http\Controllers\Api\Customer\MyProfileController;
use App\Http\Controllers\Api\Customer\RegisterController;

Route::group(['prefix' => 'customer'], function () {
    Route::post('/register', RegisterController::class)->name('customer.register');
    Route::post('/login', LoginController::class)->name('customer.login');
    Route::post('/logout', LogoutController::class)->name('customer.logout');
    Route::get('/my-orders', [MyOrderController::class, 'index'])->name('customer.my-orders');
    Route::get('/my-orders/{snap_token}', [MyOrderController::class, 'show'])->name('customer.my-orders.show');
    Route::get('/profile', [MyProfileController::class, 'index'])->name('customer.profile');
    Route::post('/profile', [MyProfileController::class, 'update'])->name('customer.profile.update');
});

Route::group(['prefix' => 'public'], function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('public.categories');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('public.categories.show');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Public\CategoryController;
use App\Http\Controllers\Api\Customer\RegisterController;

Route::group(['prefix' => 'customer'], function () {
    // Route::post('/register', RegisterController::class)->name('customer.register');
    // Route::post('/login', LoginController::class)->name('customer.login');
    // Route::post('/logout', LogoutController::class)->name('customer.logout');
});

Route::group(['prefix' => 'public'], function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('public.categories');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('public.categories.show');
});

<?php

use App\Http\Controllers\AlertsController;
use App\Http\Controllers\AllJobsController;
use App\Http\Controllers\CaptureOrderController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CreateOrderController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UserLocaleController;
use App\Http\Controllers\UserPasswordController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/user/profile', UserProfileController::class)
        ->name('user.profile')
        ->middleware(['auth', 'verified']);

    Route::post('/user/password', UserPasswordController::class)
        ->name('user.password')
        ->middleware(['auth', 'verified']);

    Route::post('/user/locale', UserLocaleController::class)
        ->name('user.locale')
        ->middleware(['auth', 'verified']);

    Route::get('/jobs/all', AllJobsController::class)
        ->name('jobs.all');

    Route::apiResource('alerts', AlertsController::class)
        ->middleware(['auth', 'verified']);

    Route::apiResource('companies', CompaniesController::class)
        ->middleware(['auth', 'verified'])
        ->except('show');

    Route::apiResource('companies', CompaniesController::class)
        ->only('show');

    Route::apiResource('jobs', JobsController::class)
        ->middleware(['auth', 'verified'])
        ->except('show');

    Route::apiResource('jobs', JobsController::class)
        ->only('show');

    Route::apiResource('orders', OrdersController::class)
        ->middleware(['auth', 'verified'])
        ->only(['index', 'show']);

    Route::post('/jobs/{job}/orders', CreateOrderController::class)
        ->middleware(['auth', 'verified'])
        ->name('jobs.orders.store');

    Route::put('/orders/{order}/capture', CaptureOrderController::class)
        ->middleware(['auth', 'verified'])
        ->name('orders.capture');

    Route::get('/countries', CountriesController::class)
        ->name('countries.index');
});

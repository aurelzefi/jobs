<?php

use App\Http\Controllers\AlertsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CreateOrderController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\CaptureOrderController;
use App\Http\Controllers\AllJobsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UserLocaleController;
use App\Http\Controllers\UserPasswordController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

if (app()->environment('beta')) {
    Route::get('/register/{email}/{hash}', function (Request $request) {
        return view('beta.register', ['request' => $request]);
    })->middleware(['guest', 'signed'])->name('invitation.register');
}

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
});

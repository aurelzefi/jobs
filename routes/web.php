<?php

use App\Http\Controllers\AlertsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CreateOrderController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\CaptureOrderController;
use App\Http\Controllers\AllJobsController;
use App\Http\Controllers\OrdersController;
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

Route::get('/jobs/all', AllJobsController::class)
    ->name('jobs.all');

Route::apiResource('alerts', AlertsController::class)
    ->middleware('auth');

Route::apiResource('companies', CompaniesController::class)
    ->middleware('auth')
    ->only('index', 'store', 'update');

Route::apiResource('companies', CompaniesController::class)
    ->only('show');

Route::apiResource('jobs', JobsController::class)
    ->middleware('auth')
    ->only('index', 'store', 'update');

Route::apiResource('jobs', JobsController::class)
    ->only('show');

Route::apiResource('orders', OrdersController::class)
    ->middleware('auth')
    ->only(['index', 'show']);

Route::post('/jobs/{job}/orders', CreateOrderController::class)
    ->middleware('auth')
    ->name('jobs.orders.store');

Route::put('/orders/{order}/capture', CaptureOrderController::class)
    ->middleware('auth')
    ->name('orders.capture');

<?php

use App\Http\Controllers\AlertsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CreateOrderController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\CaptureOrderController;
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

Route::apiResource('alerts', AlertsController::class);

Route::apiResource('companies', CompaniesController::class)->except('destroy');

Route::apiResource('jobs', JobsController::class)->except('destroy');

Route::apiResource('orders', OrdersController::class)->only(['index', 'show']);

Route::post('/jobs/{job}/orders', CreateOrderController::class)->name('jobs.orders.store');
Route::put('/orders/{order}/capture', CaptureOrderController::class)->name('orders.capture');

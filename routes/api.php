<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store'])
            ->middleware('admin')
            ->name('products.store');

        Route::patch('/{product}', [ProductController::class, 'update'])
            ->middleware('admin')
            ->name('products.update');

        Route::delete('/{product}', [ProductController::class, 'destroy'])
            ->middleware('admin')
            ->name('products.destroy');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::delete('/{order}', [OrderController::class, 'destroy'])
            ->name('orders.destroy');
    });
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/{product}', [ProductController::class, 'show'])
        ->name('products.show');
});

Route::prefix('orders')->group(function () {
    Route::post('/{product}', [OrderController::class, 'store'])
        ->name('orders.store');
    Route::get('/', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/{order}', [OrderController::class, 'show'])
        ->name('orders.show');
});

Route::get('/user', function () {
    return ! empty(Auth::user()) ? new UserResource(Auth::user()) : response(['data' => null]);
});

<?php

use App\Http\Controllers\Api\AdminPortalController;
use App\Http\Controllers\Api\CustomerPortalController;
use App\Http\Controllers\Api\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/{id}', [MenuController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customer/cart', [CustomerPortalController::class, 'cart']);
    Route::get('/customer/orders', [CustomerPortalController::class, 'orders']);

    Route::middleware('admin')->group(function () {
        Route::get('/admin/summary', [AdminPortalController::class, 'summary']);
    });
});

<?php

use App\Http\Controllers\Api\AdminPortalController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerPortalController;
use App\Http\Controllers\Api\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes - accessible without authentication

// Menu routes - publicly accessible
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/{id}', [MenuController::class, 'show']);

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes - require Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // User authentication check endpoint
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Authentication logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Customer portal routes
    Route::get('/customer/cart', [CustomerPortalController::class, 'cart']);
    Route::get('/customer/orders', [CustomerPortalController::class, 'orders']);

    // Admin routes - require both authentication and admin role
    Route::middleware('admin')->group(function () {
        Route::get('/admin/summary', [AdminPortalController::class, 'summary']);
    });
});

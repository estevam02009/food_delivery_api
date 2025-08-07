<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


// Rotas públicas (sem autenticação)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('restaurants', RestaurantController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('restaurants', RestaurantController::class)->except(['index', 'show']);
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('addresses', AddressController::class);
    Route::apiResource('users', UserController::class);

    // Rota para atualizar o status de um pedido
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    // Rota para cancelar um pedido
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);
});


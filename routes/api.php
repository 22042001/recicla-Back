<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MaterialTypeController;
use App\Http\Controllers\OfferController;

// Rutas de autenticación
Route::post('register', [AuthController::class, 'register']); // Ruta pública para registro
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth.jwt');

// Rutas protegidas por JWT
Route::middleware('auth.jwt')->group(function () {
    // CRUD de Usuarios (excepto creación)
    Route::apiResource('users', UserController::class);

    // CRUD de Tipos de Material
    Route::apiResource('materials', MaterialTypeController::class);

    // CRUD de Ofertas
    Route::apiResource('offers', OfferController::class);
});


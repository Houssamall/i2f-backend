<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinancierController;
use App\Http\Controllers\FiscalController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user-id', [AuthController::class, 'getUserIdFromToken']);
});

// Group protected routes under 'auth:api' middleware
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('clients', [AuthController::class, 'getClients']);

    // Financier routes
    Route::get('financier', [FinancierController::class, 'index']);
    Route::get('financier/getfinancier/{id}', [FinancierController::class, 'getFinancier']);
    Route::post('financier', [FinancierController::class, 'store']);

    // Fiscal routes
    Route::get('fiscal', [FiscalController::class, 'index']);
    Route::get('fiscal/getfiscal/{id}', [FiscalController::class, 'getFiscal']);
    Route::post('fiscal', [FiscalController::class, 'store']);

    // Social routes
    Route::get('social', [SocialController::class, 'index']);
    Route::get('social/getsocial/{id}', [SocialController::class, 'getSocial']);
    Route::post('social', [SocialController::class, 'store']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;

Route::prefix('client')->group(function () {
    Route::post('/register', [ClientController::class, 'register']);
});

Route::prefix('wallet')->group(function () {
    Route::post('/recharge', [WalletController::class, 'recharge']);
    Route::post('/balance', [WalletController::class, 'balance']);
    # Route::post('/audit', [WalletController::class, 'audit']);
});

Route::prefix('payment')->group(function () {
    Route::post('/transfer', [PaymentController::class, 'pay']);
    Route::post('/confirm', [PaymentController::class, 'confirm']);
});

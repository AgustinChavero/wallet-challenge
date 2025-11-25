<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WalletController;

Route::prefix('wallet')->group(function () {

    Route::post('/register', [WalletController::class, 'register']);

    Route::post('/recharge', [WalletController::class, 'recharge']);

    Route::post('/pay', [WalletController::class, 'pay']);

    Route::post('/confirm', [WalletController::class, 'confirm']);

    Route::post('/balance', [WalletController::class, 'balance']);
});

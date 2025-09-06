<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WalletApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Wallet API Routes
Route::middleware('auth:sanctum')->group(function () {
    // Wallet Resource API
    Route::apiResource('wallets', WalletApiController::class);
    
    // Wallet Operations API
    Route::post('wallets/{wallet}/deposit', [WalletApiController::class, 'deposit']);
    Route::post('wallets/{wallet}/withdraw', [WalletApiController::class, 'withdraw']);
    Route::post('wallets/{wallet}/transfer', [WalletApiController::class, 'transfer']);
    
    // Wallet Information API
    Route::get('wallets/{wallet}/balance', [WalletApiController::class, 'balance']);
    Route::get('wallets/{wallet}/transactions', [WalletApiController::class, 'transactions']);
    Route::get('wallets/{wallet}/statistics', [WalletApiController::class, 'statistics']);
    
    // Wallet Utilities API
    Route::post('wallets/{wallet}/verify-pin', [WalletApiController::class, 'verifyPin']);
    Route::get('wallets/search', [WalletApiController::class, 'search']);
});

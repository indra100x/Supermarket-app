<?php

use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('cash-registers',CashRegisterController::class)->middleware('manager');
    Route::apiResource('cashiers', UserController::class)->middleware('manager');
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('sales', SalesController::class)->only(['index', 'store']);
    Route::apiResource('shifts', ShiftController::class)->only(['index', 'store', 'update']);
    Route::apiResource('stock', StockController::class)->only(['index']);
    Route::post('/stock-alerts', [StockController::class, 'sendStockAlert']);
});


<?php

use App\Http\Controllers\API\DeviceController;
use App\Http\Controllers\API\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('devices')->group(function () {
    Route::post('/', [DeviceController::class, 'store'])->name('devices.store');
});

Route::prefix('subscriptions')->group(function () {
    Route::post('/', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/', [SubscriptionController::class, 'show'])->name('subscriptions.show');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

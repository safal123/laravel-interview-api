<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * Health check route
 */
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('/me', function (Request $request) {
            return $request->user();
        });
        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');
        Route::post('/customers', [CustomerController::class, 'store'])
            ->name('customers.store');

        Route::put('/customers/{customer}', [CustomerController::class, 'update'])
            ->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
            ->name('customers.destroy');
    });

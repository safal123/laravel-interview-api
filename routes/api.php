<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomerController;


/*
 * Health check route
 */
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');
        Route::post('/customers', [CustomerController::class, 'store'])
            ->name('customers.store');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])
            ->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
            ->name('customers.destroy');
    });


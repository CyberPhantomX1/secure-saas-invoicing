<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    Route::post('/invoices/{id}/toggle-status', [InvoiceController::class, 'toggleStatus']);
});
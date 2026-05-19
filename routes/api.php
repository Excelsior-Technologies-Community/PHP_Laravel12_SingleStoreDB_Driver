<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);

// NEW FEATURE ROUTES
Route::patch('products/{id}/toggle-status', [ProductController::class, 'toggleStatus']);

Route::get('products-analytics', [ProductController::class, 'analytics']);
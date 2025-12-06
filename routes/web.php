<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'index']);

Route::get('/cart', [CartController::class, 'show']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::patch('/cart/lines/{line}', [CartController::class, 'update']);
Route::delete('/cart/lines/{line}', [CartController::class, 'destroy']);
Route::delete('/cart', [CartController::class, 'clear']);

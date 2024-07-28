<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Customer register
Route::post('/customer/register', [App\Http\Controllers\Api\AuthController::class, 'customerRegister']);
// Seller register
Route::post('/seller/register', [App\Http\Controllers\Api\AuthController::class, 'sellerRegister']);

// login
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
// Logout
Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');

// Store category
Route::post('/seller/category', [App\Http\Controllers\Api\CategoryController::class, 'store'])->middleware('auth:sanctum');

// Get all category
Route::get('/seller/category', [App\Http\Controllers\Api\CategoryController::class, 'index'])->middleware('auth:sanctum');

// Product
Route::apiResource('/seller/product', App\Http\Controllers\Api\ProductController::class)->middleware('auth:sanctum');

// Address
Route::apiResource('/customer/address', App\Http\Controllers\Api\AddressController::class)->middleware('auth:sanctum');

// Order
Route::post('/customer/order', [App\Http\Controllers\Api\OrderController::class, 'store'])->middleware('auth:sanctum');

// Store
Route::get('/customer/store', [App\Http\Controllers\Api\StoreController::class, 'index'])->middleware('auth:sanctum');

// Get all products from a store
Route::get('/customer/store/{id}/products', [App\Http\Controllers\Api\StoreController::class, 'productsByStore'])->middleware('auth:sanctum');

// Update shipping receipt number
Route::put('/seller/order/{id}/update-shipping-receipt-number', [App\Http\Controllers\Api\OrderController::class, 'updateShippingReceiptNumber'])->middleware('auth:sanctum');

// History order for customer
Route::get('/customer/history', [App\Http\Controllers\Api\OrderController::class, 'customerOrderHistory'])->middleware('auth:sanctum');

// History order for seller
Route::get('/seller/history', [App\Http\Controllers\Api\OrderController::class, 'sellerOrderHistory'])->middleware('auth:sanctum');

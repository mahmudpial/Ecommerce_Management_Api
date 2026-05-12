<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;

// ১. পাবলিক রুট (সবাই এক্সেস করতে পারবে)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/category/{id}', [ProductController::class, 'getByCategory']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);

// ২. অথেনটিকেটেড রুট (লগইন করা যে কেউ এক্সেস করতে পারবে)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // কাস্টমার কার্ট এবং চেকআউট (এগুলো অ্যাডমিন মিডলওয়্যারের বাইরে থাকবে)
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'store']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);

    Route::post('/checkout', [CheckoutController::class, 'placeOrder']);

    // কাস্টমারের নিজের অর্ডার দেখার জন্য
    Route::get('/my-orders', [OrderController::class, 'index']);
    Route::get('/my-orders/{id}', [OrderController::class, 'show']);
    Route::get('/order/invoice/{id}', [OrderController::class, 'downloadInvoice']);

    // ৩. শুধুমাত্র অ্যাডমিন রুট (যাদের role_id অ্যাডমিন)
    Route::middleware('admin')->group(function () {
        // প্রোডাক্ট ম্যানেজমেন্ট
        Route::post('/products', [ProductController::class, 'store']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // ক্যাটাগরি ও ব্র্যান্ড ম্যানেজমেন্ট
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::post('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::post('/brands', [BrandController::class, 'store']);
        Route::post('/brands/{id}', [BrandController::class, 'update']);
        Route::delete('/brands/{id}', [BrandController::class, 'destroy']);

        // অ্যাডমিন অর্ডার ম্যানেজমেন্ট
        Route::get('/admin/orders', [OrderController::class, 'allOrders']);
        Route::post('/admin/order-status/{id}', [OrderController::class, 'updateStatus']);
    });
});
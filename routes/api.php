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
use App\Http\Controllers\Api\ReportController; // Report-er jonno path define kora

// ১. পাবলিক রুট
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']); // Single product details
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);

// ২. অথেনটিকেটেড রুট (Customer + Admin)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // কার্ট এবং চেকআউট
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'store']);
        Route::put('/update/{id}', [CartController::class, 'update']);
        Route::delete('/remove/{id}', [CartController::class, 'destroy']);
        Route::delete('/clear', [CartController::class, 'clear']);
    });

    Route::post('/checkout', [CheckoutController::class, 'placeOrder']);

    // কাস্টমার অর্ডার হিস্ট্রি
    Route::get('/my-orders', [OrderController::class, 'index']);
    Route::get('/order/invoice/{id}', [OrderController::class, 'downloadInvoice']);


    // ৩. শুধুমাত্র অ্যাডমিন ও ম্যানেজার রুট
    Route::middleware('admin')->group(function () {

        Route::post('/products', [ProductController::class, 'store']);
        Route::post('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // 🎯 অ্যাডমিন অর্ডার ম্যানেজমেন্ট
        Route::get('/admin/orders', [OrderController::class, 'allOrders']);
        Route::post('/admin/order-status/{id}', [OrderController::class, 'updateStatus']);
        Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy']);

        // 🎯 ক্যাটাগরি এবং ব্র্যান্ডের জন্য অ্যাডমিন রাউটস (ইউআরএল এর আগে /admin যোগ করা হলো)
        Route::post('/admin/categories', [CategoryController::class, 'store']);
        Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy']);

        Route::post('/admin/brands', [BrandController::class, 'store']);
        Route::delete('/admin/brands/{id}', [BrandController::class, 'destroy']);

        // ৪. রিপোর্ট মডিউল
        Route::get('/admin/reports/sales', [ReportController::class, 'salesReport']);
        Route::get('/admin/reports/stock', [ReportController::class, 'stockReport']);
    });
});
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


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


// এই রুটগুলো সবাই দেখতে পারবে (লগইন ছাড়াও)
Route::get('/products', [ProductController::class, 'index']);

// ক্যাটাগরি ভিত্তিক প্রডাক্ট ফিল্টার (সবাই দেখতে পারবে)
Route::get('/products/category/{id}', [ProductController::class, 'getByCategory']);

// সবার জন্য
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

Route::get('brands', [BrandController::class, 'index']);
Route::get('brands/{id}', [BrandController::class, 'show']);

// এই রুটগুলোর জন্য লগইন (Sanctum) লাগবে
Route::middleware('auth:sanctum')->group(function () {

    // এই রুটগুলোর জন্য লগইন + অ্যাডমিন রোল লাগবে
    Route::middleware('admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Category Routes
        Route::post('categories', [CategoryController::class, 'store']);
        Route::post('categories/{id}', [CategoryController::class, 'update']); // আপডেট করার জন্য POST ব্যবহার করা সহজ (form-data এর ক্ষেত্রে)
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // Brand Routes
        Route::post('brands', [BrandController::class, 'store']); // নতুন ব্র্যান্ড তৈরি
        Route::post('brands/{id}', [BrandController::class, 'update']); // আপডেট (POST মেথড + _method=PUT)
        Route::delete('brands/{id}', [BrandController::class, 'destroy']); // ডিলিট

        // Cart Routes
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/add', [CartController::class, 'store']);
        Route::put('/cart/update/{id}', [CartController::class, 'update']);
        Route::delete('/cart/remove/{id}', [CartController::class, 'destroy']);
        Route::delete('/cart/clear', [CartController::class, 'clear']);

        // Checkout Route (অর্ডার প্লেস করার জন্য)
        Route::post('/checkout', [CheckoutController::class, 'placeOrder']);

        // কাস্টমার রুট
        Route::get('/my-orders', [OrderController::class, 'index']);
        Route::get('/my-orders/{id}', [OrderController::class, 'show']);

        // অ্যাডমিন রুট (Admin Middleware থাকলে ভালো)
        Route::get('/admin/orders', [OrderController::class, 'allOrders']);
        Route::post('/admin/orders/update/{id}', [OrderController::class, 'updateStatus']);
    });

});
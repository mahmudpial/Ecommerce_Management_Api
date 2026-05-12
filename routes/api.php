<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// এই রুটগুলো সবাই দেখতে পারবে (লগইন ছাড়াও)
Route::get('/products', [ProductController::class, 'index']);

// এই রুটগুলোর জন্য লগইন (Sanctum) লাগবে
Route::middleware('auth:sanctum')->group(function () {

    // এই রুটগুলোর জন্য লগইন + অ্যাডমিন রোল লাগবে
    Route::middleware('admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    });

});
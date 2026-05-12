<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ১. সবাই প্রোডাক্ট দেখতে পারবে
    public function index()
    {
        return response()->json(Product::with('category')->get());
    }

    // ২. শুধুমাত্র অ্যাডমিন প্রোডাক্ট যোগ করবে
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }
}
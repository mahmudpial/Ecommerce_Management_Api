<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // ১. সার্চ এবং ফিল্টারসহ ইনডেক্স মেথড
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // নাম দিয়ে সার্চ
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ক্যাটাগরি ফিল্টার
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // ব্র্যান্ড ফিল্টার
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // দামের ফিল্টার (Min/Max)
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // প্রতি পেজে ১০টি করে প্রোডাক্ট দেখাবে (Vue 3 এর জন্য পেজিনেশন সহজ)
        return response()->json($query->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string'
        ]);

        $product = new Product($validatedData);

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product->load(['category', 'brand'])
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'brand_id' => 'sometimes|exists:brands,id',
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        // শুধু ভ্যালিডেটেড ডাটা আপডেট করা
        $product->update($request->except('image'));

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product->load(['category', 'brand'])
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        DB::transaction(function () use ($product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
        });

        return response()->json(['message' => 'Product and image deleted successfully'], 200);
    }

    public function getByCategory($id)
    {
        $products = Product::where('category_id', $id)
            ->with(['category', 'brand'])
            ->latest()
            ->paginate(10); // এখানেও পেজিনেশন ব্যবহার করা ভালো

        return response()->json($products, 200);
    }
}
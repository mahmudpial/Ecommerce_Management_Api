<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * সব ক্যাটাগরির লিস্ট দেখাবে (Public Access)
     */
    public function index()
    {
        // ক্যাটাগরির সাথে তার অধীনে থাকা প্রোডাক্টের সংখ্যাও দেখতে চাইলে withCount ব্যবহার করতে পারেন
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    /**
     * নতুন ক্যাটাগরি তৈরি (Only Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // ২ এমবি লিমিট
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // 'public/categories' ফোল্ডারে ইমেজ সেভ হবে
            $path = $request->file('image')->store('categories', 'public');
            $category->image = $path;
        }

        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * নির্দিষ্ট একটি ক্যাটাগরি দেখানো
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $category], 200);
    }

    /**
     * ক্যাটাগরি আপডেট করা (Only Admin)
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
            'image' => 'nullable|image|max:2048',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // নতুন ইমেজ আসলে পুরানোটা ডিলিট করে ক্লিন রাখা
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->image = $request->file('image')->store('categories', 'public');
        }

        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
    }

    /**
     * ক্যাটাগরি ডিলিট করা (Only Admin)
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // ডাটাবেস থেকে ডিলিট করার আগে ইমেজটি স্টোরেজ থেকে ডিলিট করা ভালো
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
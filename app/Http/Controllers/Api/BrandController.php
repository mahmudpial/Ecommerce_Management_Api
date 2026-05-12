<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * সব ব্র্যান্ডের লিস্ট দেখাবে (Public Access)
     */
    public function index()
    {
        $brands = Brand::all();
        return response()->json([
            'success' => true,
            'data' => $brands
        ], 200);
    }

    /**
     * নতুন ব্র্যান্ড তৈরি (Only Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // 'public/brands' ফোল্ডারে লোগো সেভ হবে
            $path = $request->file('image')->store('brands', 'public');
            $brand->image = $path;
        }

        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully',
            'data' => $brand
        ], 201);
    }

    /**
     * নির্দিষ্ট একটি ব্র্যান্ড দেখানো
     */
    public function show($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $brand], 200);
    }

    /**
     * ব্র্যান্ড আপডেট করা (Only Admin)
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:brands,name,' . $id,
            'image' => 'nullable|image|max:2048',
        ]);

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // নতুন লোগো আসলে পুরানোটি ডিলিট করা
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }
            $brand->image = $request->file('image')->store('brands', 'public');
        }

        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully',
            'data' => $brand
        ], 200);
    }

    /**
     * ব্র্যান্ড ডিলিট করা (Only Admin)
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully'
        ], 200);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ১. কার্টের সব আইটেম দেখা (প্রোডাক্ট ডিটেইলস সহ)
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($cartItems);
    }

    // ২. কার্টে প্রোডাক্ট যোগ করা (স্টক চেক সহ)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // স্টক চেক: প্রোডাক্ট কি যথেষ্ট আছে?
        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Stock-e jotheshto product nei!'], 422);
        }

        $userId = Auth::id();

        // চেক করুন প্রোডাক্টটি অলরেডি কার্টে আছে কি না
        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // কার্টে অলরেডি থাকলে নতুন কোয়ান্টিটি চেক করে বাড়ান
            $totalQty = $cartItem->quantity + $request->quantity;
            if ($product->stock < $totalQty) {
                return response()->json(['message' => 'Stock limit cross hoye jacche!'], 422);
            }
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // না থাকলে নতুন এন্ট্রি তৈরি করুন
            $cartItem = CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Product cart-e add hoyeche', 'data' => $cartItem], 201);
    }

    // ৩. কার্টের কোয়ান্টিটি আপডেট করা (এখানেও স্টক চেক করা হয়েছে)
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);

        // স্টক চেক
        if ($cartItem->product->stock < $request->quantity) {
            return response()->json(['message' => 'Etogulo product stock-e nei'], 422);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart update hoyeche']);
    }

    // ৪. কার্ট থেকে আইটেম মুছে ফেলা
    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Item removed']);
    }

    // ৫. পুরো কার্ট খালি করা
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Cart cleared']);
    }
}
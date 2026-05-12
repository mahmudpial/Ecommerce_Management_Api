<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ১. কার্টের সব আইটেম দেখা
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($cartItems);
    }

    // ২. কার্টে প্রোডাক্ট যোগ করা (Add to Cart)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        // চেক করুন প্রোডাক্টটি অলরেডি কার্টে আছে কি না
        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // থাকলে শুধু কোয়ান্টিটি বাড়িয়ে দিন
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // না থাকলে নতুন এন্ট্রি তৈরি করুন
            $cartItem = CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Product added to cart', 'data' => $cartItem], 201);
    }

    // ৩. কার্টের কোয়ান্টিটি আপডেট করা
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated successfully']);
    }

    // ৪. কার্ট থেকে একটি আইটেম মুছে ফেলা
    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    // ৫. পুরো কার্ট খালি করা (Clear Cart)
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Cart cleared']);
    }
}
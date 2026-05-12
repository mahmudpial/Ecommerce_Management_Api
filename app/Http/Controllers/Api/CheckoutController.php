<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem; // এটি এখন আমাদের সোর্স
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $userId = Auth::id();

        // ১. ইউজারের কার্ট থেকে আইটেমগুলো নিয়ে আসা
        $cartItems = CartItem::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'আপনার কার্টটি খালি!'], 400);
        }

        return DB::transaction(function () use ($request, $userId, $cartItems) {
            $totalAmount = 0;

            // ২. মেইন অর্ডার তৈরি
            $order = Order::create([
                'user_id' => $userId,
                'invoice_no' => 'INV-' . strtoupper(Str::random(10)),
                'name' => $request->name,
                'email' => Auth::user()->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'total_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($cartItems as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);

                // ৩. স্টক চেক
                if ($product->stock < $item->quantity) {
                    throw new \Exception("পণ্য {$product->name} এর পর্যাপ্ত স্টক নেই।");
                }

                $itemTotal = $product->price * $item->quantity;
                $totalAmount += $itemTotal;

                // ৪. অর্ডার আইটেম সেভ
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal,
                ]);

                // ৫. স্টক কমানো
                $product->decrement('stock', $item->quantity);
            }

            // ৬. ফাইনাল এমাউন্ট আপডেট
            $order->update(['total_amount' => $totalAmount]);

            // ৭. সবচেয়ে গুরুত্বপূর্ণ: অর্ডার হয়ে গেলে কার্ট খালি করে দেওয়া
            CartItem::where('user_id', $userId)->delete();

            return response()->json([
                'message' => 'অর্ডারটি সফলভাবে সম্পন্ন হয়েছে!',
                'invoice_no' => $order->invoice_no,
                'total' => $totalAmount
            ], 201);
        });
    }
}
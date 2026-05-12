<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // কাস্টমার তার নিজের সব অর্ডার দেখবে
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($orders);
    }

    // একটি নির্দিষ্ট অর্ডারের বিস্তারিত (Item সহ)
    public function show($id)
    {
        $order = Order::with('orderItems')->where('user_id', Auth::id())->findOrFail($id);
        return response()->json($order);
    }

    // শুধুমাত্র অ্যাডমিন সব অর্ডার দেখতে পারবে
    public function allOrders()
    {
        // এখানে অ্যাডমিন মিডলওয়্যার চেক থাকবে
        $orders = Order::with('user')->latest()->paginate(15);
        return response()->json($orders);
    }

    // শুধুমাত্র অ্যাডমিন অর্ডারের স্ট্যাটাস পরিবর্তন করবে
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:unpaid,paid'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        return response()->json(['message' => 'Order status updated successfully', 'data' => $order]);
    }
}
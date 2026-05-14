<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * কাস্টমারের জন্য: নিজের সব অর্ডার দেখা
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * কাস্টমার ও অ্যাডমিন উভয়ের জন্য: অর্ডারের বিস্তারিত দেখা
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        if (Auth::user()->role_id !== 1 && $order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        return response()->json($order);
    }

    /**
     * 🎯 অ্যাডমিনের জন্য: সব অর্ডার দেখা (ফ্রন্টএন্ড টেবিল ফ্রেন্ডলি আপডেট)
     */
    public function allOrders()
    {
        // ফ্রন্টএন্ডের orders.value = response.data কন্ডিশন ম্যাচ করার জন্য সরাসরি get() অথবা Paginate কালেকশন রিটার্ন করা
        $orders = Order::with('user')->latest()->get();
        return response()->json($orders);
    }

    /**
     * 🎯 অ্যাডমিনের জন্য: স্ট্যাটাস আপডেট করা (ভ্যালিডেশন ফিক্সড)
     */
    public function updateStatus(Request $request, $id)
    {
        // ফ্রন্টএন্ড থেকে পাঠানো কেস-ইনসেনসিটিভ (Pending/Processing) স্ট্যাটাস হ্যান্ডেল করার জন্য lowercase এ কনভার্ট করা
        if ($request->has('status')) {
            $request->merge(['status' => strtolower($request->status)]);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,completed',
            'payment_status' => 'nullable|in:unpaid,paid' // ফ্রন্টএন্ড মডালে না থাকলে যেন এরর না দেয় (nullable করা হলো)
        ]);

        $order = Order::findOrFail($id);

        // শুধুমাত্র ফ্রন্টএন্ড থেকে যা পাঠানো হয়েছে (যেমন শুধু status) তাই আপডেট হবে
        $updateData = ['status' => $request->status];
        if ($request->has('payment_status')) {
            $updateData['payment_status'] = $request->payment_status;
        }

        $order->update($updateData);

        return response()->json([
            'message' => 'Order status updated successfully',
            'data' => $order
        ], 200);
    }

    /**
     * 🚨 নতুন মেথড: অর্ডার ডিলিট করা (destroy)
     * আপনার api.php রাউটের ডিলিট অ্যাকশনের সাথে যুক্ত
     */
    public function destroy($id)
    {
        // রোল লেভেল ডাবল সিকিউরিটি চেক
        if (Auth::user()->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized: Only admins can delete records.'], 403);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found or already purged'], 404);
        }

        // রিলেশনাল ডেটাবেজ সেফটির জন্য অর্ডারের আইটেমগুলো আগে ডিলিট হতে পারে (যদি ক্যাসকেড না থাকে)
        if ($order->items()) {
            $order->items()->delete();
        }

        $order->delete();

        return response()->json([
            'message' => 'Order pipeline data successfully purged from core system.'
        ], 200);
    }

    /**
     * ইনভয়েস জেনারেট করা (PDF)
     */
    public function downloadInvoice($id)
    {
        $order = Order::with(['items'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if (Auth::user()->role_id !== 1 && $order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $pdf = Pdf::loadView('emails.invoice', compact('order'))
                ->setPaper('a4', 'portrait')
                ->setWarnings(false);

            return $pdf->download('invoice-' . ($order->invoice_number ?? $order->id) . '.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'PDF Compilation Error',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
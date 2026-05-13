<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // PDF এর জন্য এটি জরুরি

class OrderController extends Controller
{
    /**
     * কাস্টমারের জন্য: নিজের সব অর্ডার দেখা
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10); // get() এর বদলে paginate ব্যবহার করা ভালো

        return response()->json($orders);
    }

    /**
     * কাস্টমার ও অ্যাডমিন উভয়ের জন্য: অর্ডারের বিস্তারিত দেখা
     */
    public function show($id)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($id);

        // সিকিউরিটি চেক: যদি ইউজার অ্যাডমিন না হয়, তবে সে শুধু নিজের অর্ডার দেখতে পারবে
        if (Auth::user()->role_id !== 1 && $order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        return response()->json($order);
    }

    /**
     * অ্যাডমিনের জন্য: সব অর্ডার দেখা
     */
    public function allOrders()
    {
        // এখানে সরাসরি role_id চেক করা যেতে পারে অথবা মিডলওয়্যার ব্যবহার করা যায়
        $orders = Order::with('user')->latest()->paginate(15);
        return response()->json($orders);
    }

    /**
     * অ্যাডমিনের জন্য: স্ট্যাটাস আপডেট করা
     */
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

        return response()->json([
            'message' => 'Order status updated successfully',
            'data' => $order
        ]);
    }

    /**
     * ইনভয়েস জেনারেট করা (PDF)
     */
    public function downloadInvoice($id)
    {
        $order = Order::with(['orderItems', 'user'])->findOrFail($id);

        // সিকিউরিটি চেক
        if (Auth::user()->role_id !== 1 && $order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pdf = Pdf::loadView('invoices.order_invoice', compact('order'));

        // ফাইলটি ডাউনলোড করার জন্য
        return $pdf->download('invoice-' . $order->invoice_number . '.pdf');
    }
}
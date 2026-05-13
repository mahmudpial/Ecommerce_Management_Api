<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    // ১. সেলস রিপোর্ট (Date Filter সহ)
    public function salesReport(Request $request)
    {
        $query = Order::where('status', 'delivered');

        // Date filter logic
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $totalSales = $query->sum('total_amount');
        $orderCount = $query->count();
        $orders = $query->with('user')->get();

        return response()->json([
            'total_sales' => $totalSales,
            'total_orders' => $orderCount,
            'orders' => $orders
        ]);
    }

    // ২. স্টক রিপোর্ট (কোন প্রোডাক্ট কতটুকু আছে)
    public function stockReport()
    {
        $products = Product::select('id', 'name', 'stock', 'price')->get();
        
        $lowStock = $products->filter(function($product) {
            return $product->stock < 10; // ১০ এর নিচে থাকলে লো-স্টক
        });

        return response()->json([
            'all_products' => $products,
            'low_stock_count' => $lowStock->count(),
            'low_stock_items' => $lowStock->values()
        ]);
    }
}
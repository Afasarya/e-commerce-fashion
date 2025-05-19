<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductSize;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Order statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        
        // Revenue statistics
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');
            
        // Product statistics
        $totalProducts = Product::count();
        $outOfStockProducts = Product::whereHas('sizes', function ($query) {
            $query->where('stock', '<=', 0)->orWhere('active', false);
        })->count();
        
        // Low stock products
        $lowStockProducts = ProductSize::with('product')
            ->where('stock', '<=', 10)
            ->where('active', true)
            ->orderBy('stock')
            ->take(10)
            ->get();
        
        // User statistics
        $totalUsers = User::where('role', 'user')->count();
        $newUsers = User::where('role', 'user')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
            
        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalOrders', 
            'pendingOrders', 
            'processingOrders', 
            'completedOrders',
            'totalRevenue',
            'monthlyRevenue',
            'totalProducts',
            'outOfStockProducts',
            'totalUsers',
            'newUsers',
            'recentOrders',
            'lowStockProducts'
        ));
    }
    
    /**
     * Display sales report
     */
    public function salesReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        // Daily sales data for chart
        $dailySales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            ]);
            
        // Product sales data
        $productSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name')
            ->select(
                'products.id', 
                'products.name',
                DB::raw('SUM(order_items.quantity) as quantity_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_sales')
            )
            ->orderByDesc('total_sales')
            ->take(10)
            ->get();
            
        return view('admin.reports.sales', compact('dailySales', 'productSales', 'startDate', 'endDate'));
    }
}

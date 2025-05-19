<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display the sales report.
     */
    public function sales(Request $request)
    {
        try {
            $startDate = $request->input('start_date') 
                ? Carbon::parse($request->input('start_date'))
                : Carbon::now()->subMonth()->startOfMonth();
                    
            $endDate = $request->input('end_date')
                ? Carbon::parse($request->input('end_date'))
                : Carbon::now()->endOfDay();
                    
            // Include ends of the day for accurate filtering
            $startDateFormatted = $startDate->copy()->startOfDay();
            $endDateFormatted = $endDate->copy()->endOfDay();
            
            // Get daily sales breakdown
            $dailySales = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total_orders'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                    
            // Get popular products
            $popularProducts = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select(
                    'products.id',
                    'products.name',
                    'products.image',
                    DB::raw('SUM(order_items.quantity) as total_qty'),
                    DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                )
                ->groupBy('products.id', 'products.name', 'products.image')
                ->orderByDesc('total_qty')
                ->limit(10)
                ->get();
            
            // Calculate summary data explicitly
            $totalOrders = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])
                ->count();
                
            $totalRevenue = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])
                ->sum('total_amount') ?? 0;
                
            $averageOrder = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;
                    
            // Define summary explicitly
            $summary = [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'average_order' => $averageOrder,
            ];
        } catch (\Exception $e) {
            // Fallback if there's an error
            Log::error('Error in sales report: ' . $e->getMessage());
            
            $dailySales = collect();
            $popularProducts = collect();
            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate = Carbon::now();
            
            // Always define summary even in error case
            $summary = [
                'total_orders' => 0,
                'total_revenue' => 0,
                'average_order' => 0,
            ];
        }
                
        // Ensure we're explicitly passing all variables to the view
        return view('admin.reports.sales', [
            'dailySales' => $dailySales, 
            'popularProducts' => $popularProducts, 
            'summary' => $summary, 
            'startDate' => $startDate, 
            'endDate' => $endDate
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current month date range
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        // Get last month date range for comparison
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        // === CURRENT MONTH STATS ===
        
        // Total Revenue (from sales)
        $currentRevenue = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('total');
        
        // Total Sales Count
        $currentSalesCount = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])->count();
        
        // Total Income
        $currentIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('amount');
        
        // Total Expenses
        $currentExpenses = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('amount');
        
        // Net Profit
        $currentProfit = $currentIncome - $currentExpenses;

        // === LAST MONTH STATS (for comparison) ===
        
        $lastRevenue = Sale::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->get()
            ->sum('total');
        
        $lastSalesCount = Sale::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])->count();
        
        $lastIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->get()
            ->sum('amount');
        
        $lastExpenses = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->get()
            ->sum('amount');
        
        $lastProfit = $lastIncome - $lastExpenses;

        // === CALCULATE GROWTH PERCENTAGES ===
        
        $revenueGrowth = $lastRevenue > 0 ? (($currentRevenue - $lastRevenue) / $lastRevenue) * 100 : 0;
        $salesGrowth = $lastSalesCount > 0 ? (($currentSalesCount - $lastSalesCount) / $lastSalesCount) * 100 : 0;
        $profitGrowth = $lastProfit != 0 ? (($currentProfit - $lastProfit) / abs($lastProfit)) * 100 : 0;

        // === SALES BREAKDOWN BY TYPE ===
        
        $indoorSales = Sale::where('saleType', 'indoor')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('total');
        
        $onlineSales = Sale::where('saleType', 'online')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('total');

        // === TOP SELLING PRODUCTS ===
        
        $sales = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])->get();
        $productSales = [];
        
        foreach ($sales as $sale) {
            if (!empty($sale->items)) {
                foreach ($sale->items as $item) {
                    if ($item['type'] === 'product') {
                        $productId = $item['itemID'];
                        $revenue = $item['quantity'] * $item['price'];
                        
                        if (!isset($productSales[$productId])) {
                            $productSales[$productId] = [
                                'quantity' => 0,
                                'revenue' => 0,
                            ];
                        }
                        
                        $productSales[$productId]['quantity'] += $item['quantity'];
                        $productSales[$productId]['revenue'] += $revenue;
                    }
                }
            }
        }
        
        // Sort by revenue and get top 5
        arsort($productSales);
        $topProductIds = array_slice(array_keys($productSales), 0, 5, true);
        
        $topProducts = [];
        foreach ($topProductIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $topProducts[] = [
                    'name' => $product->name,
                    'quantity' => $productSales[$productId]['quantity'],
                    'revenue' => $productSales[$productId]['revenue'],
                ];
            }
        }

        // === RECENT SALES ===
        
        $recentSales = Sale::with(['customer', 'employee'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // === OTHER COUNTS ===
        
        $totalProducts = Product::count();
        $totalEmployees = Employee::where('working', true)->count();
        $totalCustomers = Customer::count();
        
        // Low stock products (qty < 10)
        $lowStockCount = Product::where('qty', '<', 10)->count();

        return view('dashboard', compact(
            'currentRevenue',
            'currentSalesCount',
            'currentProfit',
            'lowStockCount',
            'revenueGrowth',
            'salesGrowth',
            'profitGrowth',
            'indoorSales',
            'onlineSales',
            'topProducts',
            'recentSales',
            'totalProducts',
            'totalEmployees',
            'totalCustomers'
        ));
    }
}

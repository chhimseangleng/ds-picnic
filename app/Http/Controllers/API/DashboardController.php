<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        
        // Get last month for comparison
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        // Current month stats
        $currentRevenue = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()->sum('total');
        
        $currentSalesCount = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])->count();
        
        $currentIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()->sum('amount');
        
        $currentExpenses = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()->sum('amount');
        
        $currentProfit = $currentIncome - $currentExpenses;

        // Last month stats
        $lastRevenue = Sale::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->get()->sum('total');
        
        $lastSalesCount = Sale::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])->count();
        
        $lastIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->get()->sum('amount');
        
        $lastExpenses = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->get()->sum('amount');
        
        $lastProfit = $lastIncome - $lastExpenses;

        // Growth percentages
        $revenueGrowth = $lastRevenue > 0 ? (($currentRevenue - $lastRevenue) / $lastRevenue) * 100 : 0;
        $salesGrowth = $lastSalesCount > 0 ? (($currentSalesCount - $lastSalesCount) / $lastSalesCount) * 100 : 0;
        $profitGrowth = $lastProfit != 0 ? (($currentProfit - $lastProfit) / abs($lastProfit)) * 100 : 0;

        // Sales breakdown
        $indoorSales = Sale::where('saleType', 'indoor')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()->sum('total');
        
        $onlineSales = Sale::where('saleType', 'online')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()->sum('total');

        // Other counts
        $totalProducts = Product::count();
        $totalEmployees = Employee::where('working', true)->count();
        $totalCustomers = Customer::count();
        $lowStockCount = Product::where('qty', '<', 10)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'currentMonth' => [
                    'revenue' => $currentRevenue,
                    'salesCount' => $currentSalesCount,
                    'profit' => $currentProfit,
                    'income' => $currentIncome,
                    'expenses' => $currentExpenses,
                ],
                'growth' => [
                    'revenue' => round($revenueGrowth, 1),
                    'sales' => round($salesGrowth, 1),
                    'profit' => round($profitGrowth, 1),
                ],
                'salesBreakdown' => [
                    'indoor' => $indoorSales,
                    'online' => $onlineSales,
                ],
                'counts' => [
                    'products' => $totalProducts,
                    'employees' => $totalEmployees,
                    'customers' => $totalCustomers,
                    'lowStock' => $lowStockCount,
                ],
            ],
        ]);
    }
}

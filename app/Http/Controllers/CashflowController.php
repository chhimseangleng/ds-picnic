<?php

namespace App\Http\Controllers;

use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    public function index()
    {
        // Monthly stats could be added later, for now total or this month
        // Let's get totals for the current month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $totalIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('amount');

        $totalExpense = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('amount');

        // Calculate % change (mock implementation for now or simple logic if previous month data exists)
        // For simplicity, I'll specific previous month data fetching
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        $lastMonthIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->get()
            ->sum('amount');

        $lastMonthExpense = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->get()
            ->sum('amount');

        $incomeChange = $lastMonthIncome > 0 ? (($totalIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : 0;
        $expenseChange = $lastMonthExpense > 0 ? (($totalExpense - $lastMonthExpense) / $lastMonthExpense) * 100 : 0;


        $transactions = TransactionHistory::orderBy('date', 'desc')->paginate(10);

        return view('cashflow.index', compact(
            'totalIncome',
            'totalExpense',
            'incomeChange',
            'expenseChange',
            'transactions'
        ));
    }
}

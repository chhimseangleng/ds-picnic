<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function cashflow(Request $request)
    {
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);
        
        $startOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();
        
        $startOfLastMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->subMonth()->startOfMonth();
        $endOfLastMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->subMonth()->endOfMonth();

        // Current month
        $totalIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        $totalExpenses = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        $balance = $totalIncome - $totalExpenses;

        // Last month for comparison
        $lastMonthIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');
        
        $lastMonthExpenses = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');

        // Calculate changes
        $incomeChange = $lastMonthIncome > 0 ? (($totalIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : 0;
        $expenseChange = $lastMonthExpenses > 0 ? (($totalExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100 : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'month' => $selectedMonth,
                'year' => $selectedYear,
                'totalIncome' => $totalIncome,
                'totalExpenses' => $totalExpenses,
                'balance' => $balance,
                'incomeChange' => round($incomeChange, 1),
                'expenseChange' => round($expenseChange, 1),
            ],
        ]);
    }

    public function index(Request $request)
    {
        $query = TransactionHistory::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('month') && $request->has('year')) {
            $startOfMonth = \Carbon\Carbon::create($request->year, $request->month, 1)->startOfMonth();
            $endOfMonth = \Carbon\Carbon::create($request->year, $request->month, 1)->endOfMonth();
            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    public function addExpense(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $transaction = TransactionHistory::create([
            'amount' => (float) $request->amount,
            'type' => 'expense',
            'description' => $request->description,
            'date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expense added successfully',
            'data' => $transaction,
        ], 201);
    }

    public function addBonus(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $transaction = TransactionHistory::create([
            'amount' => (float) $request->amount,
            'type' => 'income',
            'description' => $request->description,
            'date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bonus added successfully',
            'data' => $transaction,
        ], 201);
    }
}

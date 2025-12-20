<?php

namespace App\Http\Controllers;

use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters or default to current month/year
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);
        
        // Create date range for the selected month
        $startOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        $totalIncome = TransactionHistory::where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('amount');

        $totalExpense = TransactionHistory::whereIn('type', ['expense', 'expence'])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->sum('amount');

        // Calculate % change (comparing to previous month)
        $startOfLastMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->subMonth()->startOfMonth();
        $endOfLastMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->subMonth()->endOfMonth();

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

        // Get transactions for selected month
        $transactions = TransactionHistory::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('cashflow.index', compact(
            'totalIncome',
            'totalExpense',
            'incomeChange',
            'expenseChange',
            'transactions',
            'selectedMonth',
            'selectedYear'
        ));
    }

    /**
     * Add a general expense
     */
    public function addExpense(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        TransactionHistory::create([
            'amount' => $validated['amount'],
            'type' => 'expense',
            'description' => $validated['name'] . ' - ' . $validated['description'],
            'date' => now(),
        ]);

        return redirect()->back()->with('success', 'Expense added successfully!');
    }

    /**
     * Add a bonus/income
     */
    public function addBonus(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        TransactionHistory::create([
            'amount' => $validated['amount'],
            'type' => 'income',
            'description' => $validated['name'] . ' - ' . $validated['description'],
            'date' => now(),
        ]);

        return redirect()->back()->with('success', 'Bonus/Income added successfully!');
    }
}

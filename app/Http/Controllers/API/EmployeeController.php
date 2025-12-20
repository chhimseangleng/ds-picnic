<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('working')) {
            $query->where('working', $request->working === 'true');
        }

        $employees = $query->get();

        return response()->json([
            'success' => true,
            'data' => $employees,
        ]);
    }

    public function show($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|in:male,female',
            'role' => 'required|string',
            'salary' => 'required|numeric',
            'joinDate' => 'required|date',
        ]);

        $employee = Employee::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'role' => $request->role,
            'salary' => $request->salary,
            'joinDate' => $request->joinDate,
            'phone' => $request->phone,
            'telegramId' => $request->telegramId,
            'working' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
            'data' => $employee,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found',
            ], 404);
        }

        $employee->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully',
            'data' => $employee,
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found',
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully',
        ]);
    }

    public function salaryManagement(Request $request)
    {
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);
        
        $startOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        $employees = Employee::where('working', true)->get();
        
        $employeesWithSalary = $employees->map(function ($employee) use ($startOfMonth, $endOfMonth) {
            $paidSalary = TransactionHistory::where('type', 'expense')
                ->where('description', 'like', '%Salary for ' . $employee->name . '%')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            return [
                'employee' => $employee,
                'paidSalary' => $paidSalary,
                'remainingSalary' => $employee->salary - $paidSalary,
                'isPaid' => $paidSalary >= $employee->salary,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'month' => $selectedMonth,
                'year' => $selectedYear,
                'employees' => $employeesWithSalary,
            ],
        ]);
    }

    public function paySalary(Request $request)
    {
        $request->validate([
            'employeeId' => 'required',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
        ]);

        $employee = Employee::find($request->employeeId);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found',
            ], 404);
        }

        TransactionHistory::create([
            'amount' => (float) $request->amount,
            'type' => 'expense',
            'description' => 'Salary for ' . $employee->name . ' - ' . date('F Y', mktime(0, 0, 0, $request->month, 1, $request->year)),
            'date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Salary paid successfully',
        ], 201);
    }
}

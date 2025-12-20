<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'employee']);

        if ($request->has('saleType')) {
            $query->where('saleType', $request->saleType);
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('orderID', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $sales = $query->orderBy('date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $sales,
        ]);
    }

    public function show($id)
    {
        $sale = Sale::with(['customer', 'employee'])->find($id);

        if (!$sale) {
            return response()->json([
                'success' => false,
                'message' => 'Sale not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sale,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customerId' => 'required',
            'employeeId' => 'required',
            'saleType' => 'required|in:indoor,online',
            'items' => 'required|array',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
        ]);

        // Generate order ID
        $orderID = Sale::generateOrderID();

        $sale = Sale::create([
            'customerId' => $request->customerId,
            'employeeId' => $request->employeeId,
            'orderID' => $orderID,
            'saleType' => $request->saleType,
            'items' => $request->items,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
            'date' => now(),
        ]);

        // Record transaction history
        TransactionHistory::create([
            'amount' => (float) $request->total,
            'type' => 'income',
            'description' => 'Sale Order #' . $orderID,
            'sale_id' => $sale->_id,
            'date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sale created successfully',
            'data' => $sale,
        ], 201);
    }

    public function invoice($id)
    {
        $sale = Sale::with(['customer', 'employee'])->find($id);

        if (!$sale) {
            return response()->json([
                'success' => false,
                'message' => 'Sale not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sale,
        ]);
    }
}

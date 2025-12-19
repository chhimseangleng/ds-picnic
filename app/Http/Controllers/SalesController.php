<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Bundle;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SalesController extends Controller
{
    /**
     * Display list of sales with filtering
     */
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'employee']);

        // Filter by sale type
        if ($request->has('saleType') && $request->saleType != '') {
            $query->where('saleType', $request->saleType);
        }

        // Search by customer name or order ID
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('orderID', 'like', '%' . $searchTerm . '%');
                // We'll also search customer names if needed
            });
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        return view('sales.index', compact('sales'));
    }

    /**
     * Show create sale page
     */
    public function create()
    {
        // Get all necessary data
        $employees = Employee::get();
        $customers = Customer::get();
        $categories = ProductCategory::where('deleted', '!=', true)->orderBy('name')->get();
        
        // Get products with images
        $products = Product::with('category')->orderBy('name')->get();
        $products->transform(function ($product) {
            $product->image_url = $product->image ? Storage::disk('s3')->url($product->image) : null;
            return $product;
        });

        // Get bundles with images
        $bundles = Bundle::orderBy('name')->get();
        $bundles->transform(function ($bundle) {
            $bundle->image_url = $bundle->image ? Storage::disk('s3')->url($bundle->image) : null;
            $bundle->available_stock = $bundle->available_stock;
            return $bundle;
        });

        return view('sales.create', compact('employees', 'customers', 'categories', 'products', 'bundles'));
    }

    /**
     * Store a new sale
     */
    public function store(Request $request)
    {
        // Decode items JSON string to array
        $itemsArray = json_decode($request->items, true);
        $request->merge(['items' => $itemsArray]);

        $request->validate([
            'saleType' => 'required|in:indoor,online',
            'employeeID' => 'required',
            'customerID' => 'required',
            'items' => 'required|array|min:1',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        // Generate order ID
        $orderID = Sale::generateOrderID();

        // Create sale
        $sale = Sale::create([
            'orderID' => $orderID,
            'saleType' => $request->saleType,
            'employeeID' => $request->employeeID,
            'customerID' => $request->customerID,
            'items' => $request->items,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount ?? 0,
            'total' => $request->total,
            'date' => now(),
        ]);

        // Update stock for products and bundles
        foreach ($request->items as $item) {
            if ($item['type'] === 'product') {
                $product = Product::find($item['itemID']);
                if ($product) {
                    $product->qty -= $item['quantity'];
                    $product->save();
                }
            } elseif ($item['type'] === 'bundle') {
                $bundle = Bundle::find($item['itemID']);
                if ($bundle && !empty($bundle->products)) {
                    // Deduct stock from each product in the bundle
                    foreach ($bundle->products as $bundleProduct) {
                        $product = Product::find($bundleProduct['productID']);
                        if ($product) {
                            $quantityToDeduct = $bundleProduct['quantity'] * $item['quantity'];
                            $product->qty -= $quantityToDeduct;
                            $product->save();
                        }
                    }
                }
            }
        }


        return redirect()->route('sale.invoice', $sale->_id);
    }

    /**
     * Show invoice for a sale
     */
    public function show(string $id)
    {
        $sale = Sale::with(['customer', 'employee'])->findOrFail($id);

        // Get detailed item information
        $detailedItems = [];
        foreach ($sale->items as $item) {
            if ($item['type'] === 'product') {
                $product = Product::find($item['itemID']);
                if ($product) {
                    $detailedItems[] = [
                        'type' => 'product',
                        'name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ];
                }
            } elseif ($item['type'] === 'bundle') {
                $bundle = Bundle::find($item['itemID']);
                if ($bundle) {
                    $detailedItems[] = [
                        'type' => 'bundle',
                        'name' => $bundle->name,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ];
                }
            }
        }

        return view('sales.invoice', compact('sale', 'detailedItems'));
    }

    /**
     * API: Get customer contact by ID
     */
    public function getCustomerContact(string $id)
    {
        $customer = Customer::find($id);
        
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json([
            'phone' => $customer->phone,
        ]);
    }
}

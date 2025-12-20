<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Bundle;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Products
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category')) {
            $query->where('categoryId', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'categoryId' => 'required',
            'unitPrice' => 'required|numeric',
            'sellPrice' => 'required|numeric',
            'qty' => 'required|integer',
            'minStock' => 'required|integer',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    public function addStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $product->qty += $request->quantity;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Stock added successfully',
            'data' => $product,
        ]);
    }

    // Categories
    public function categories()
    {
        $categories = ProductCategory::all();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $category = ProductCategory::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    public function destroyCategory($id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    // Bundles
    public function bundles()
    {
        $bundles = Bundle::all();

        return response()->json([
            'success' => true,
            'data' => $bundles,
        ]);
    }

    public function showBundle($id)
    {
        $bundle = Bundle::find($id);

        if (!$bundle) {
            return response()->json([
                'success' => false,
                'message' => 'Bundle not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $bundle,
        ]);
    }

    public function storeBundle(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'products' => 'required|array',
            'price' => 'required|numeric',
        ]);

        $bundle = Bundle::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Bundle created successfully',
            'data' => $bundle,
        ], 201);
    }

    public function updateBundle(Request $request, $id)
    {
        $bundle = Bundle::find($id);

        if (!$bundle) {
            return response()->json([
                'success' => false,
                'message' => 'Bundle not found',
            ], 404);
        }

        $bundle->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Bundle updated successfully',
            'data' => $bundle,
        ]);
    }

    public function destroyBundle($id)
    {
        $bundle = Bundle::find($id);

        if (!$bundle) {
            return response()->json([
                'success' => false,
                'message' => 'Bundle not found',
            ], 404);
        }

        $bundle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bundle deleted successfully',
        ]);
    }
}

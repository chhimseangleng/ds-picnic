<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request as Psr7Request;
use PhpParser\Node\Expr\FuncCall;

class StockController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all();

        // Load products and eager load category relation to avoid N+1
        $products = Product::with('category')->orderBy('name')->get();

        return view('stock.index', compact('categories', 'products'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ProductCategory::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('stock')->with('success', 'Product category added successfully.');
    }

    public function storeProduct(Request $request)
{
    // dd($request->all());
    // 1. Validate the incoming data
    // $request->validate([
    //     'name'       => 'required|string|max:255',
    //     'categoryID' => 'required',
    //     'unitPrice'  => 'required|numeric',
    //     'sellPrice'  => 'required|numeric',
    //     'qty'        => 'required|integer',
    //     'minStock'   => 'nullable|integer',
    //     'image'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    // ]);


    $request->validate([
            'name'       => 'required|string|max:255',
            'categoryID' => 'required',
            'unitPrice'  => 'required|numeric',
            'sellPrice'  => 'required|numeric',
            'qty'        => 'required|integer',
            'minStock'   => 'nullable|integer',
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);




        $imagePath = null;

        if ($request->hasFile('image')) {
            // 'store' with an empty string '' puts the file in the root of the bucket.
            // It returns the filename (e.g., "unique_id.jpg").
            // $imagePath = $request->file('image')->store('images', 's3');
            try {
                // Option 1: Store to a specific folder 'images'
                $imagePath = $request->file('image')->store('images', 's3');

                // OR Option 2: Store to the root folder '' (if you prefer that)
                // $imagePath = $request->file('image')->store('', 's3');

            } catch (\Exception $e) {
                // If S3 fails, this will display the exact AWS error message.
                dd("S3 Upload Failed. AWS Error:", $e->getMessage());
            }
        }


   Product::create([
            'name'       => $request->name,
            'categoryID' => $request->categoryID,
            'unitPrice'  => $request->unitPrice,
            'sellPrice'  => $request->sellPrice,
            'qty'        => $request->qty,
            'minStock'   => $request->minStock ?? 0,
            'image'      => $imagePath, // Saves the S3 filename to the database
        ]);

    // 4. Return success response
    return redirect()->back()->with('success', 'Product added successfully!');
}
}

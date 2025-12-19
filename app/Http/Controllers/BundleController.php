<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BundleController extends Controller
{
    /**
     * Display a listing of all bundles
     */
    public function index()
    {
        $bundles = Bundle::orderBy('created_at', 'desc')->get();
        
        // Add image URLs and available stock to each bundle
        $bundles->transform(function ($bundle) {
            $bundle->image_url = $bundle->image ? Storage::disk('s3')->url($bundle->image) : null;
            $bundle->available_stock = $bundle->available_stock;
            return $bundle;
        });

        return view('stock.bundles', compact('bundles'));
    }

    /**
     * Show the form for creating a new bundle
     */
    public function create()
    {
        // Get all active products with their categories
        $categories = ProductCategory::where('deleted', '!=', true)->orderBy('name')->get();
        $products = Product::with('category')->orderBy('name')->get();
        
        // Add image URLs to products
        $products->transform(function ($product) {
            $product->image_url = $product->image ? Storage::disk('s3')->url($product->image) : null;
            return $product;
        });

        return view('stock.create-bundle', compact('products', 'categories'));
    }

    /**
     * Store a newly created bundle
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bundlePrice' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.productID' => 'required',
            'products.*.quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImageToS3($request->file('image'));
        }

        Bundle::create([
            'name' => $request->name,
            'description' => $request->description,
            'products' => $request->products,
            'bundlePrice' => $request->bundlePrice,
            'image' => $imagePath,
            'isActive' => true,
            'createdBy' => auth()->user()->_id ?? null,
        ]);

        return redirect()->route('bundles')->with('success', 'Bundle created successfully!');
    }

    /**
     * Show the form for editing a bundle
     */
    public function edit(string $id)
    {
        $bundle = Bundle::findOrFail($id);
        $categories = ProductCategory::where('deleted', '!=', true)->orderBy('name')->get();
        $products = Product::with('category')->orderBy('name')->get();
        
        // Add image URLs
        $products->transform(function ($product) {
            $product->image_url = $product->image ? Storage::disk('s3')->url($product->image) : null;
            return $product;
        });
        
        $bundle->image_url = $bundle->image ? Storage::disk('s3')->url($bundle->image) : null;

        return view('stock.edit-bundle', compact('bundle', 'products', 'categories'));
    }

    /**
     * Update the specified bundle
     */
    public function update(Request $request, string $id)
    {
        $bundle = Bundle::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bundlePrice' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.productID' => 'required',
            'products.*.quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($bundle->image) {
                Storage::disk('s3')->delete($bundle->image);
            }
            $bundle->image = $this->uploadImageToS3($request->file('image'));
        }

        $bundle->update([
            'name' => $request->name,
            'description' => $request->description,
            'products' => $request->products,
            'bundlePrice' => $request->bundlePrice,
        ]);

        return redirect()->route('bundles')->with('success', 'Bundle updated successfully!');
    }

    /**
     * Remove the specified bundle
     */
    public function destroy(string $id)
    {
        $bundle = Bundle::findOrFail($id);

        // Delete image from S3 if exists
        if ($bundle->image) {
            Storage::disk('s3')->delete($bundle->image);
        }

        $bundle->delete();

        return redirect()->route('bundles')->with('success', 'Bundle deleted successfully!');
    }

    /**
     * Upload image to S3
     */
    private function uploadImageToS3($file, string $folder = 'bundles'): ?string
    {
        try {
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->putFileAs($folder, $file, $filename);
            
            if ($path) {
                \Log::info('S3 Upload Success: ' . $path);
                return $path;
            }
            
            \Log::error('S3 Upload Failed: Path returned empty');
            return null;
        } catch (\Exception $e) {
            \Log::error('S3 Upload Failed: ' . $e->getMessage());
            if (config('app.debug')) {
                throw $e;
            }
            return null;
        }
    }
}

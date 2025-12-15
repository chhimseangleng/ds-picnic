<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockController extends Controller
{
    /**
     * Display the stock index page with all products and categories.
     */
    public function index()
    {
        $categories = ProductCategory::all();

        // Load products and eager load category relation to avoid N+1
        $products = Product::with('category')->orderBy('name')->get();

        // Add S3 image URLs to each product
        $products->transform(function ($product) {
            $product->image_url = $this->getImageUrl($product->image);
            return $product;
        });

        return view('stock.index', compact('categories', 'products'));
    }

    /**
     * Get the full S3 URL for an image path.
     * 
     * @param string|null $imagePath The stored image path (e.g., 'images/filename.jpg')
     * @return string|null The full S3 URL or null if no image
     */
    public function getImageUrl(?string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        // Generate a temporary signed URL (valid for 60 minutes)
        // Use this if your bucket is private
        // return Storage::disk('s3')->temporaryUrl($imagePath, now()->addMinutes(60));

        // Generate a public URL (use this if your bucket/objects are public)
        return Storage::disk('s3')->url($imagePath);
    }

    /**
     * Store a new product category.
     */
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

    /**
     * Store a new product with S3 image upload.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'categoryID' => 'required',
            'unitPrice'  => 'required|numeric',
            'sellPrice'  => 'required|numeric',
            'qty'        => 'required|integer',
            'minStock'   => 'nullable|integer',
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImageToS3($request->file('image'));
        }

        Product::create([
            'name'       => $request->name,
            'categoryID' => $request->categoryID,
            'unitPrice'  => $request->unitPrice,
            'sellPrice'  => $request->sellPrice,
            'qty'        => $request->qty,
            'minStock'   => $request->minStock ?? 0,
            'image'      => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Product added successfully!');
    }

    /**
     * Show a single product with its S3 image URL.
     * 
     * @param string $id The product ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function showProduct(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        $product->image_url = $this->getImageUrl($product->image);

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    /**
     * Update an existing product with optional S3 image replacement.
     * 
     * @param Request $request
     * @param string $id The product ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProduct(Request $request, string $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'categoryID' => 'required',
            'unitPrice'  => 'required|numeric',
            'sellPrice'  => 'required|numeric',
            'qty'        => 'required|integer',
            'minStock'   => 'nullable|integer',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $product = Product::findOrFail($id);

        // If a new image is uploaded, delete the old one and upload the new one
        if ($request->hasFile('image')) {
            // Delete old image from S3 if it exists
            $this->deleteImageFromS3($product->image);

            // Upload new image
            $imagePath = $this->uploadImageToS3($request->file('image'));
            $product->image = $imagePath;
        }

        // Update product fields
        $product->name       = $request->name;
        $product->categoryID = $request->categoryID;
        $product->unitPrice  = $request->unitPrice;
        $product->sellPrice  = $request->sellPrice;
        $product->qty        = $request->qty;
        $product->minStock   = $request->minStock ?? 0;
        $product->save();

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    /**
     * Delete a product and its associated S3 image.
     * 
     * @param string $id The product ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProduct(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete image from S3 if it exists
        $this->deleteImageFromS3($product->image);

        // Delete the product from database
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }

    /**
     * Upload an image file to S3.
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder The folder to store the image in
     * @return string|null The S3 path or null on failure
     */
    private function uploadImageToS3($file, string $folder = 'images'): ?string
    {
        try {
            // Generate a unique filename with original extension
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Simple store approach - let S3 bucket policy handle visibility
            $path = Storage::disk('s3')->putFileAs($folder, $file, $filename);

            if ($path) {
                \Log::info('S3 Upload Success: ' . $path);
                return $path;
            }
            
            \Log::error('S3 Upload Failed: Path returned empty');
            return null;
        } catch (\Exception $e) {
            \Log::error('S3 Upload Failed: ' . $e->getMessage());
            // Re-throw in local environment to see the actual error
            if (config('app.debug')) {
                throw $e;
            }
            return null;
        }
    }

    /**
     * Delete an image from S3.
     * 
     * @param string|null $imagePath The S3 path of the image
     * @return bool True if deleted successfully, false otherwise
     */
    private function deleteImageFromS3(?string $imagePath): bool
    {
        if (empty($imagePath)) {
            return false;
        }

        try {
            if (Storage::disk('s3')->exists($imagePath)) {
                Storage::disk('s3')->delete($imagePath);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('S3 Delete Failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all products as JSON with S3 image URLs (for API use).
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductsApi()
    {
        $products = Product::with('category')->orderBy('name')->get();

        $products->transform(function ($product) {
            $product->image_url = $this->getImageUrl($product->image);
            return $product;
        });

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }
}

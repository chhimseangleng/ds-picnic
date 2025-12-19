<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Bundle extends Model
{
    // MongoDB connection
    protected $connection = 'mongodb';
    
    // Collection name
    protected $table = 'bundles';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'description',
        'products', // Array of {productID: string, quantity: int}
        'bundlePrice',
        'image',
        'isActive',
        'createdBy',
    ];

    // Cast types
    protected $casts = [
        'products' => 'array',
        'bundlePrice' => 'float',
        'isActive' => 'boolean',
    ];

    /**
     * Calculate available stock for this bundle based on component products
     * Returns the maximum number of this bundle that can be created
     * 
     * @return int
     */
    public function getAvailableStockAttribute(): int
    {
        if (empty($this->products)) {
            return 0;
        }

        $maxBundles = PHP_INT_MAX;

        foreach ($this->products as $item) {
            $product = Product::find($item['productID']);
            
            if (!$product) {
                return 0; // If any product doesn't exist, bundle can't be created
            }

            $quantity = $item['quantity'] ?? 1;
            $possibleBundles = floor($product->qty / $quantity);
            
            $maxBundles = min($maxBundles, $possibleBundles);
        }

        return $maxBundles === PHP_INT_MAX ? 0 : $maxBundles;
    }

    /**
     * Get the products with their details
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getProductsWithDetailsAttribute()
    {
        if (empty($this->products)) {
            return collect([]);
        }

        return collect($this->products)->map(function ($item) {
            $product = Product::find($item['productID']);
            if ($product) {
                return [
                    'product' => $product,
                    'quantity' => $item['quantity'] ?? 1,
                ];
            }
            return null;
        })->filter();
    }

    /**
     * Relationship: Bundle created by a User
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'createdBy', '_id');
    }
}

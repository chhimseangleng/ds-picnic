<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    // Use MongoDB connection and collection
    protected $connection = 'mongodb';
    protected $collection = 'products';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'categoryID',
        'unitPrice',
        'qty',
        'sellPrice',
        'minStock',
        'image',
    ];

    // Cast types
    protected $casts = [
        'unitPrice' => 'float',
        'sellPrice' => 'float',
        'qty' => 'int',
        'minStock' => 'int',
    ];

    /**
     * Belongs to ProductCategory via `categoryID` (stores the category _id).
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'categoryID', '_id');
    }

     public function addstocks()
    {
        return $this->hasMany(\App\Models\Product_Addstock::class, 'productID', '_id');
    }

    
}

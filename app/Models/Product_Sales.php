<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_Sales extends Model
{
    // Table name (optional)
    protected $table = 'product_sales';

    // Mass assignable fields
    protected $fillable = [
        'employeeID',
        'customerID',
        'saleType',     // e.g., 'single' or 'set'
        'productID',    // nullable if saleType is 'set'
        'setID',        // nullable if saleType is 'single'
        'totalAmount',
        'discount',
        'date',
    ];

    // Casts
    protected $casts = [
        'totalAmount' => 'float',
        'discount' => 'float',
        'date' => 'datetime',
    ];

    /**
     * Relationship: Sale belongs to Employee
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'employeeID', 'id');
    }

    /**
     * Relationship: Sale belongs to Customer
     */
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customerID', 'id');
    }

    /**
     * Relationship: Sale belongs to Product (if single sale)
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'productID', '_id'); // Product is MongoDB
    }

    /**
     * Relationship: Sale belongs to Bundle / Set (if saleType is 'set')
     */
    // public function set()
    // {
    //     return $this->belongsTo(\App\Models\Bundle::class, 'setID', 'id'); // adjust PK of Bundle
    // }
}

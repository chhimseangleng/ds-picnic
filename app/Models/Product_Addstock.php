<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_Addstock extends Model
{
    // Table name (if not following Laravel naming convention)
    protected $table = 'product_addstocks';

    // Mass assignable attributes
    protected $fillable = [
        'employeeID',
        'productID',
        'qty',
        'purchasePrice',
        'totalAmount',
        'date',
    ];

    // Cast types
    protected $casts = [
        'qty' => 'int',
        'purchasePrice' => 'float',
        'totalAmount' => 'float',
        'date' => 'datetime',
    ];

    /**
     * Relationship: Stock addition belongs to a Product
     */
    public function product()
    {
        // Note: Product is in MongoDB
        return $this->belongsTo(\App\Models\Product::class, 'productID', '_id');
    }

    /**
     * Relationship: Stock addition belongs to an Employee
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'employeeID', 'id'); // adjust 'id' if your Employee PK is different
    }
}

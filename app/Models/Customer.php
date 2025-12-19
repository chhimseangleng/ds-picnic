<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Customer extends Model
{
    // MongoDB connection
    protected $connection = 'mongodb';
    
    // Collection name
    protected $table = 'customers';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'phone',
    ];

    /**
     * Relationship: Customer has many Product Sales
     */
    public function productSales()
    {
        return $this->hasMany(\App\Models\Product_Sales::class, 'customerID', '_id');
    }
}

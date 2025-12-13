<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // Table name (optional, if not default "customers")
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
        return $this->hasMany(\App\Models\Product_Sales::class, 'customerID', 'id');
    }
}

<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Sale extends Model
{
    // MongoDB connection
    protected $connection = 'mongodb';
    
    // Collection name
    protected $table = 'sales';

    // Mass assignable fields
    protected $fillable = [
        'orderID',
        'saleType',      // 'indoor' or 'online'
        'employeeID',
        'customerID',
        'items',         // Array of products/bundles
        'subtotal',
        'discount',
        'total',
        'date',
    ];

    // Cast types
    protected $casts = [
        'items' => 'array',
        'subtotal' => 'float',
        'discount' => 'float',
        'total' => 'float',
        'date' => 'datetime',
    ];

    /**
     * Generate unique order ID (e.g., A-123)
     */
    public static function generateOrderID()
    {
        $lastSale = self::orderBy('created_at', 'desc')->first();
        
        if (!$lastSale || !$lastSale->orderID) {
            return 'A-001';
        }

        // Extract number from last order ID (A-123 -> 123)
        $lastNumber = (int) substr($lastSale->orderID, 2);
        $newNumber = $lastNumber + 1;
        
        return 'A-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relationship: Sale belongs to Employee
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'employeeID', '_id');
    }

    /**
     * Relationship: Sale belongs to Customer
     */
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customerID', '_id');
    }
}

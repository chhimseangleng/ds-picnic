<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Table name (optional)
    protected $table = 'transactions';

    // Mass assignable fields
    protected $fillable = [
        'transactionType', // Income or Expense
        'type',            // category ID or custom type
        'subject',
        'amount',
        'date',
    ];

    // Casts
    protected $casts = [
        'amount' => 'float',
        'date' => 'datetime',
    ];

    /**
     * Optional: Relationship to CategoryCashSource
     * Assuming 'type' stores the category ID
     */
    // public function category()
    // {
    //     return $this->belongsTo(\App\Models\CategoryCashSource::class, 'type', 'id');
    // }
}

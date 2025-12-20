<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class TransactionHistory extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'transaction_histories';

    protected $fillable = [
        'amount',
        'type',        // 'income' or 'expense'
        'description',
        'date',
        'sale_id',     // Reference to Sale for income transactions
    ];

    protected $casts = [
        'amount' => 'float',
        'date' => 'datetime',
    ];
}

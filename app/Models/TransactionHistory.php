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
    ];

    protected $casts = [
        'amount' => 'float',
        'date' => 'datetime',
    ];
}

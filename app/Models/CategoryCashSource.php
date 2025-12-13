<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryCashSource extends Model
{
    // Table name (optional)
    protected $table = 'category_cash_sources';

    // Mass assignable fields
    protected $fillable = [
        'type', // income or expense
        'name',
    ];

   
}

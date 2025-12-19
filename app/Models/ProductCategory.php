<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductCategory extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'product_categories';

    protected $fillable = [
        'name',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    /**
     * Get the products that belong to this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'categoryID', '_id');
    }
}




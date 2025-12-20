<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Employee extends Model
{
    // MongoDB connection
    protected $connection = 'mongodb';

    // Collection name
    protected $collection = 'employees';

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'role',
        'phone',
        'gender',
        'email',
        'salary',
        'working',    // boolean
        'startWork',  // date
        'stopWork',   // date or null
        'profile_image', // S3 path to profile image
    ];

    // Casts for proper data types
    protected $casts = [
        'working' => 'boolean',
        'startWork' => 'datetime',
        'stopWork' => 'datetime',
    ];

    public function addstocks()
    {
        return $this->hasMany(\App\Models\Product_Addstock::class, 'employeeID', 'id');
    }

    public function salaryPayments()
    {
        return $this->hasMany(\App\Models\SalaryPayment::class, 'employeeID', '_id');
    }
    
}

<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class SalaryPayment extends Model
{
    // MongoDB connection
    protected $connection = 'mongodb';
    
    // Collection name
    protected $collection = 'salary_payments';

    // Fillable fields for mass assignment
    protected $fillable = [
        'employeeID',
        'amount',
        'paymentPeriod',
        'notes',
        'paidBy',
        'paymentDate',
    ];

    // Casts for proper data types
    protected $casts = [
        'amount' => 'float',
        'paymentDate' => 'datetime',
    ];

    /**
     * Relationship: Payment belongs to an Employee
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'employeeID', '_id');
    }

    /**
     * Relationship: Payment was made by a User
     */
    public function paidByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'paidBy', 'id');
    }
}

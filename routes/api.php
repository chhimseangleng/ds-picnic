<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (require authentication)
Route::middleware('auth.mongo')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Products/Stock
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::post('/products/{id}/add-stock', [ProductController::class, 'addStock']);
    
    // Categories
    Route::get('/categories', [ProductController::class, 'categories']);
    Route::post('/categories', [ProductController::class, 'storeCategory']);
    Route::delete('/categories/{id}', [ProductController::class, 'destroyCategory']);
    
    // Bundles
    Route::get('/bundles', [ProductController::class, 'bundles']);
    Route::get('/bundles/{id}', [ProductController::class, 'showBundle']);
    Route::post('/bundles', [ProductController::class, 'storeBundle']);
    Route::put('/bundles/{id}', [ProductController::class, 'updateBundle']);
    Route::delete('/bundles/{id}', [ProductController::class, 'destroyBundle']);
    
    // Sales
    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/sales/{id}', [SaleController::class, 'show']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::get('/sales/{id}/invoice', [SaleController::class, 'invoice']);
    
    // Customers
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::put('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
    
    // Employees
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    Route::get('/employees/salary-management', [EmployeeController::class, 'salaryManagement']);
    Route::post('/employees/pay-salary', [EmployeeController::class, 'paySalary']);
    
    // Cashflow/Transactions
    Route::get('/cashflow', [TransactionController::class, 'cashflow']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions/expense', [TransactionController::class, 'addExpense']);
    Route::post('/transactions/bonus', [TransactionController::class, 'addBonus']);
});

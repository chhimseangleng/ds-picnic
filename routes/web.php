<?php

use App\Http\Controllers\CashflowController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales', [SalesController::class, 'index'])->name('sales');
    Route::get('/stock', [StockController::class, 'index'])->name('stock');
    Route::get('/stock/categories', [StockController::class, 'manageCategories'])->name('stock.categories');
    Route::post('/stock/add-category', [StockController::class, 'storeCategory'])->name('stock.add-category');
    Route::delete('/stock/category/{id}', [StockController::class, 'deleteCategory'])->name('stock.delete-category');
    Route::post('/stock/product-store', [StockController::class, 'storeProduct'])->name('stock.store.product');
    //newwwwww 
    Route::get('/stock/product/{id}', [StockController::class, 'showProduct'])->name('stock.show.product');
    Route::put('/stock/product/{id}', [StockController::class, 'updateProduct'])->name('stock.update.product');
    Route::delete('/stock/product/{id}', [StockController::class, 'deleteProduct'])->name('stock.delete.product');
    // Route::get('/api/products', [StockController::class, 'getProductsApi'])->name('api.products'); // Moved to API routes
    Route::post('/stock/add-stock', [StockController::class, 'addStock'])->name('stock.addStock');
    
    // Bundle routes
    Route::get('/stock/bundles', [\App\Http\Controllers\BundleController::class, 'index'])->name('bundles');
    Route::get('/stock/bundles/create', [\App\Http\Controllers\BundleController::class, 'create'])->name('bundles.create');
    Route::post('/stock/bundles', [\App\Http\Controllers\BundleController::class, 'store'])->name('bundles.store');
    Route::get('/stock/bundles/{id}/edit', [\App\Http\Controllers\BundleController::class, 'edit'])->name('bundles.edit');
    Route::put('/stock/bundles/{id}', [\App\Http\Controllers\BundleController::class, 'update'])->name('bundles.update');
    Route::delete('/stock/bundles/{id}', [\App\Http\Controllers\BundleController::class, 'destroy'])->name('bundles.destroy');
    /////
    Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflow');
    Route::post('/cashflow/add-expense', [CashflowController::class, 'addExpense'])->name('cashflow.add-expense');
    Route::post('/cashflow/add-bonus', [CashflowController::class, 'addBonus'])->name('cashflow.add-bonus');
    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
    Route::get('/employees/{id}', [EmployeesController::class, 'show'])->name('employees.show');
    Route::post('/employees', [EmployeesController::class, 'store'])->name('employees.store');
    Route::put('/employees/{id}', [EmployeesController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeesController::class, 'destroy'])->name('employees.destroy');
    Route::get('/employees/salary/management', [EmployeesController::class, 'salaryManagement'])->name('employees.salary.management');
    Route::post('/employees/salary/pay', [EmployeesController::class, 'paySalary'])->name('employees.salary.pay');
    
    // Customer routes
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers');
    Route::post('/customers', [CustomersController::class, 'store'])->name('customers.store');
    Route::put('/customers/{id}', [CustomersController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomersController::class, 'destroy'])->name('customers.destroy');
    
    // Sale routes
    Route::get('/sale', [\App\Http\Controllers\SalesController::class, 'index'])->name('sale');
    Route::get('/sale/create', [\App\Http\Controllers\SalesController::class, 'create'])->name('sale.create');
    Route::post('/sale', [\App\Http\Controllers\SalesController::class, 'store'])->name('sale.store');
    Route::get('/sale/{id}/invoice', [\App\Http\Controllers\SalesController::class, 'show'])->name('sale.invoice');
    Route::get('/api/customer/{id}/contact', [\App\Http\Controllers\SalesController::class, 'getCustomerContact']);
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

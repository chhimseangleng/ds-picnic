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
    Route::get('/api/products', [StockController::class, 'getProductsApi'])->name('api.products');
    /////
    Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflow');
    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

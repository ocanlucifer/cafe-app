<?php

// routes/web.php
use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IssuingController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockMutationController;



//auth Route
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

//middleware auth
// Route yang dilindungi dengan autentikasi
Route::middleware('auth')->group(function () {
    //User Route
    Route::resource('users', UserController::class);
            //Add a route to toggle item active status
            Route::post('/users/{id}/toggleStatus', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
            // Add Route to reset Password
            Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
            // Add Route to Change Password
            Route::post('/password/change', [UserController::class, 'changePassword'])->name('password.change');

    //Dashboard Route
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    //Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Category Routes
    Route::resource('categories', CategoryController::class);

    // Type Routes
    Route::resource('types', TypeController::class);

    // Item Routes
    Route::resource('items', ItemController::class);
        // Add a route to toggle item active status
        Route::post('/items/{item}/toggle-active', [ItemController::class, 'toggleActive'])->name('items.toggleActive');

     // Menu Routes
    Route::resource('menus', MenuController::class);
        // Add a route to toggle item active status
        Route::post('/menus/{menu}/toggle-active', [MenuController::class, 'toggleActive'])->name('menus.toggleActive');

    // Vendor Routes
    Route::resource('vendors', VendorController::class);
        // Add a route to toggle vendor active status
        Route::post('/vendors/{vendor}/toggle-active', [VendorController::class, 'toggleActive'])->name('vendors.toggleActive');

    // Customer Routes
    Route::resource('customers', CustomerController::class);
        // Add a route to toggle custome active status
        Route::post('/customers/{customer}/toggle-active', [CustomerController::class, 'toggleActive'])->name('customers.toggleActive');


    // Purchase Routes
    Route::resource('purchases', PurchaseController::class);

    // Issuing Routes
    Route::resource('issuings', IssuingController::class);

    // Sale Routes
    Route::resource('sales', SaleController::class);
    Route::get('/sales/{id}/print-pdf', [SaleController::class, 'printPDF'])->name('sales.print-pdf');


    // Report Routes
    // Route untuk menampilkan laporan penjualan
    Route::get('/penjualan/reports', [SaleController::class, 'generateSalesReport'])->name('penjualan.reports');
    Route::get('/penjualan/printReportPDF', [SaleController::class, 'printReportPDF'])->name('penjualan.printReportPDF');

    //route report mutasi
    Route::get('/stock-mutations', [StockMutationController::class, 'index'])->name('stock-mutations');
    Route::post('/stock-mutations/fetch', [StockMutationController::class, 'fetchData'])->name('stock-mutations.fetch');


});

// Home Route
// Route::get('/', function () {
//     // return view('welcome');
//     return view('welcome');
// });




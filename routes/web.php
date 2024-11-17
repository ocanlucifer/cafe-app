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
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;



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


    // Inventory Routes
    Route::resource('inventories', InventoryController::class);

    // Purchase Routes
    Route::resource('purchases', PurchaseController::class);

    // Sale Routes
    Route::resource('sales', SaleController::class);

    // Report Routes
    Route::get('reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');

});

// Home Route
// Route::get('/', function () {
//     // return view('welcome');
//     return view('welcome');
// });




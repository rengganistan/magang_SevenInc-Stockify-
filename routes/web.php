<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class);

    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class);

    Route::resource('suppliers', SupplierController::class);

    /*
    |--------------------------------------------------------------------------
    | Transaksi
    |--------------------------------------------------------------------------
    */
Route::get(
    '/transactions/incoming',
    [StockTransactionController::class,'incoming']
)->name('transactions.incoming');

Route::get(
    '/transactions/outgoing',
    [StockTransactionController::class,'outgoing']
)->name('transactions.outgoing');

Route::get(
    '/transactions/create',
    [StockTransactionController::class,'create']
)->name('transactions.create');

Route::post(
    '/transactions',
    [StockTransactionController::class,'store']
)->name('transactions.store');

Route::get(
    '/transactions/{id}/edit',
    [StockTransactionController::class,'edit']
)->name('transactions.edit');

Route::put(
    '/transactions/{id}',
    [StockTransactionController::class,'update']
)->name('transactions.update');

Route::delete(
    '/transactions/{id}',
    [StockTransactionController::class,'destroy']
)->name('transactions.destroy');
}); // <<< ADMIN DITUTUP DI SINI

/*
|--------------------------------------------------------------------------
| REPORT
|--------------------------------------------------------------------------
*/

Route::prefix('reports')->group(function () {

    Route::get('/stock', [ReportController::class, 'stock'])
        ->name('reports.stock');

    Route::get('/transaction', [ReportController::class, 'transaction'])
        ->name('reports.transaction');

    Route::get('/activity', [ReportController::class, 'activity'])
        ->name('reports.activity');

});


Route::get(
    '/settings',
    [SettingController::class, 'index']
)->name('settings.index');

Route::put(
    '/settings/{id}',
    [SettingController::class, 'update']
)->name('settings.update');

/*
|--------------------------------------------------------------------------
| MANAGER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,manager'])->group(function () {

    Route::get('/manager/dashboard', [DashboardController::class, 'manager'])
        ->name('manager.dashboard');

});

/*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,manager,staff'])->group(function () {

    Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
        ->name('staff.dashboard');

});

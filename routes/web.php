<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/



Route::get('/', function () {
    return redirect()->route('login');
});
//login
Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');


// admin
Route::middleware(['auth','role:admin'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard');

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])
    ->name('users.edit');

    Route::put('/users/{id}', [UserController::class, 'update'])
    ->name('users.update');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->name('users.destroy');

    Route::resource('products', ProductController::class);

    Route::resource('suppliers', SupplierController::class);

    Route::resource(
    'stock-transactions',
    StockTransactionController::class
);

/*
|--------------------------------------------------------------------------
| Category
|--------------------------------------------------------------------------
*/


Route::get('/categories',[CategoryController::class,'index'])
        ->name('categories.index');

Route::get('/categories/create',[CategoryController::class,'create'])
        ->name('categories.create');

Route::post('/categories',[CategoryController::class,'store'])
        ->name('categories.store');

Route::get('/categories/{id}/edit',[CategoryController::class,'edit'])
        ->name('categories.edit');

Route::put('/categories/{id}',[CategoryController::class,'update'])
        ->name('categories.update');

Route::delete('/categories/{id}',[CategoryController::class,'destroy'])
        ->name('categories.destroy');
});

Route::prefix('reports')->group(function () {

    Route::get('/stock', [ReportController::class, 'stock'])
        ->name('reports.stock');

    Route::get('/incoming', [ReportController::class, 'incoming'])
        ->name('reports.incoming');

    Route::get('/outgoing', [ReportController::class, 'outgoing'])
        ->name('reports.outgoing');

});

Route::get('/manager/dashboard', [DashboardController::class, 'manager'])
    ->middleware(['auth', 'role:admin,manager'])
    ->name('manager.dashboard');

Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
    ->middleware(['auth', 'role:admin,manager,staff'])
    ->name('staff.dashboard');

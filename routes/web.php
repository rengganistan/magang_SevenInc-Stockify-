<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'admin']);

});

Route::middleware(['auth', 'role:admin,manager'])->group(function () {

    Route::get('/manager/dashboard', [DashboardController::class, 'manager']);

});

Route::middleware(['auth', 'role:admin,manager,staff'])->group(function () {

    Route::get('/staff/dashboard', [DashboardController::class, 'staff']);

});

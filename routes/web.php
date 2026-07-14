<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [DashboardController::class, 'admin']);
Route::get('/manager/dashboard', [DashboardController::class, 'manager']);
Route::get('/staff/dashboard', [DashboardController::class, 'staff']);

//login
Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::get('/', function () {
    return redirect('/login');
});

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

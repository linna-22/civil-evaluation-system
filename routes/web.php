<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'authenticate'])
        ->name('login.authenticate');

});

Route::middleware('auth')->group(function () {

    // Route::get('/dashboard', function () {

    //     return '
    //             <h1>Welcome to Dashboard</h1>

    //             <form action="'.route('logout').'" method="POST">

    //             '.csrf_field().'

    //             <button type="submit">

    //             Logout

    //             </button>

    //             </form>
    //             ';

    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');
       

});
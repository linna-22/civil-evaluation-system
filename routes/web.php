<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OrganizationController;

Route::middleware('guest')->group(function () {

    Route::get('/', [LoginController::class, 'index'])
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

    // Organization 
    Route::prefix('organizations')
        ->name('organizations.')
        ->middleware('auth')
        ->group(function () {

            Route::get('/', [OrganizationController::class, 'index'])->name('index');
            Route::get('/data', [OrganizationController::class, 'data'])->name('data');
            Route::get('/create', [OrganizationController::class, 'create'])->name('create');
            Route::post('/', [OrganizationController::class, 'store'])->name('store');
            Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('edit');
            Route::put('/{organization}', [OrganizationController::class, 'update'])->name('update');
        });
    
    // Department
    Route::prefix('departments')
        ->name('departments.')
        ->middleware('auth')
        ->group(function () {

            Route::get('/', [DepartmentController::class, 'index'])->name('index');
            Route::get('/data', [DepartmentController::class, 'data'])->name('data');
            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/', [DepartmentController::class, 'store'])->name('store');
            Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
            Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
            Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
        });

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');


});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizationController;

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

    // Organization 


    Route::prefix('organizations')
        ->name('organizations.')
        ->middleware('auth')
        ->group(function () {

            // List
            Route::get('/', [OrganizationController::class, 'index'])
                ->name('index');
            Route::get('/data', [OrganizationController::class, 'data'])
                ->name('data');

            // Create Page
            Route::get('/create', [OrganizationController::class, 'create'])
                ->name('create');

            // Store
            Route::post('/', [OrganizationController::class, 'store'])
                ->name('store');

            // Edit Page
            Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])
                ->name('edit');

            // Update
            Route::put('/{organization}', [OrganizationController::class, 'update'])
                ->name('update');

            // Delete
            Route::delete('/{organization}', [OrganizationController::class, 'destroy'])
                ->name('destroy');

        });

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');


});
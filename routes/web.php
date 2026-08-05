<?php

use Asorasoft\Chhankitek\Chhankitek;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\MyEvaluationController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationReportController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use Carbon\CarbonImmutable;



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
            Route::get('/by-organization/{organization}', [DepartmentController::class, 'byOrganization'])->name('byOrganization');
            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/', [DepartmentController::class, 'store'])->name('store');
            Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
            Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
            Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
        });

    // User
    Route::prefix('users')
        ->name('users.')
        ->middleware('auth')
        ->group(function () {

            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/data', [UserController::class, 'data'])->name('data');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/change-password', [UserController::class, 'changePassword'])->name('change-password');
            Route::put('/update-password', [UserController::class, 'updatePassword'])->name('update-password');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::get('/profile', [UserController::class, 'profile'])->name('profile');

        });

    // Evaluation 
    Route::prefix('evaluations')
        ->name('evaluations.')
        ->group(function () {

            Route::get('/', [EvaluationController::class, 'index'])->name('index');
            Route::get('/create', [EvaluationController::class, 'create'])->name('evaluations.create');
            Route::get('/history', [EvaluationController::class, 'history'])->name('history');
            Route::get('/list', [EvaluationController::class, 'list'])
                ->middleware('role:super_admin,organization_admin,department_admin')
                ->name('list');
            Route::get('/{evaluation}', [EvaluationController::class, 'show'])->name('show');
            Route::post('/', [EvaluationController::class, 'store'])->name('store');
            Route::get('/departments', [EvaluationController::class, 'departments'])->name('departments');

        });

        // Evaluation Report
        Route::prefix('reports')
    ->name('reports.')
    ->middleware(['role:super_admin,organization_admin,department_admin'
    ])
    ->group(function () {

        Route::get('/evaluation/preview', [EvaluationReportController::class, 'preview'])->name('evaluation.preview');

    });


    // Employee
    // Route::get('/   ', [EmployeeDashboardController::class, 'index'])
    //     ->name('employee.dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');


Route::get('/test-lunar', function () {

    return toLunarDate(
        CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh')
    )->toString();

});



});
<?php

use Asorasoft\Chhankitek\Chhankitek;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\BehaviorEvaluationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationPeriodController;
use App\Http\Controllers\EvaluationReportController;
use App\Http\Controllers\Evaluations\AttendanceEvaluationController;
use App\Http\Controllers\Evaluations\WorkPerformanceEvaluationController;
use App\Http\Controllers\OfficeController;
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
            // Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
        });
    // Department
    Route::prefix('offices')
        ->name('offices.')
        ->middleware('auth')
        ->group(function () {

            Route::get('/', [OfficeController::class, 'index'])->name('index');
            Route::get('/data', [OfficeController::class, 'data'])->name('data');
            Route::get('/by-department/{departmentId}', [OfficeController::class, 'getByDepartment']);
            Route::get('/create', [OfficeController::class, 'create'])->name('create');
            Route::post('/', [OfficeController::class, 'store'])->name('store');
            Route::get('/{office}/edit', [OfficeController::class, 'edit'])->name('edit');
            Route::put('/{office}', [OfficeController::class, 'update'])->name('update');
            Route::delete('/{office}', [OfficeController::class, 'destroy'])->name('destroy');
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
    // Evaluation Period
    Route::prefix('evaluation-periods')
        ->name('evaluation-periods.')
        ->middleware('auth')
        ->group(function () {
            Route::get('/', [EvaluationPeriodController::class, 'index'])->name('index');
            Route::get('/data', [EvaluationPeriodController::class, 'data'])->name('data');
            Route::get('/create', [EvaluationPeriodController::class, 'create'])->name('create');
            Route::post('/', [EvaluationPeriodController::class, 'store'])->name('store');
            Route::get('/{evaluationPeriod}/edit', [EvaluationPeriodController::class, 'edit'])->name('edit');
            Route::put('/{evaluationPeriod}', [EvaluationPeriodController::class, 'update'])->name('update');
            Route::patch('/{evaluationPeriod}/close', [EvaluationPeriodController::class, 'close'])->name('close');
            Route::get('/{evaluationPeriod}', [EvaluationPeriodController::class, 'show'])->name('show');

        });
    // ==========================================
    // Behavior Evaluation
    // =========================================
    Route::prefix('evaluations/behavior')->group(function () {

        Route::get('/', [BehaviorEvaluationController::class, 'index'])->name('evaluations.behavior.index');
        Route::get('/create', [BehaviorEvaluationController::class, 'create'])->name('evaluations.behavior.create');
        Route::post('/', [BehaviorEvaluationController::class, 'store'])->name('evaluations.behavior.store');
        Route::get('/preview', [BehaviorEvaluationController::class, 'preview'])->name('evaluations.behavior.preview');
        Route::get('/view', [BehaviorEvaluationController::class, 'view'])->name('evaluations.behavior.view');
    });

    // Work Performance evaluation
    Route::prefix('evaluations/work-performance')
        ->middleware('role:super_admin,organization_admin,department_admin')
        ->name('evaluations.work-performance.')
        ->group(function () {

            // Department cards
            Route::get('/', [WorkPerformanceEvaluationController::class, 'index'])->name('index');
            Route::get('/department/{department}/users', [WorkPerformanceEvaluationController::class, 'usersByDepartment'])->name('department.users');
            Route::get('/office/{office}/users', [WorkPerformanceEvaluationController::class, 'usersByOffice'])->name('office.users');
            Route::get('/create/{office?}', [WorkPerformanceEvaluationController::class, 'create'])->name('create');
            Route::get('/preview', [WorkPerformanceEvaluationController::class, 'preview'])->name('preview');
            Route::post('/submit', [WorkPerformanceEvaluationController::class, 'submit'])->name('submit');
            Route::get('/view/{office?}', [WorkPerformanceEvaluationController::class, 'view'])->name('view');

        });
    // Attendance evaluation
    Route::prefix('evaluations/attendance')
        ->middleware('role:super_admin,organization_admin,department_admin')
        ->name('evaluations.attendance.')
        ->group(function () {
            // Department / Office
            Route::get('/', [AttendanceEvaluationController::class, 'index'])->name('index');
            Route::get('/department/{department}/users', [AttendanceEvaluationController::class, 'usersByDepartment'])->name('department.users');
            Route::get('/office/{office}/users', [AttendanceEvaluationController::class, 'usersByOffice'])->name('office.users');
            // Evaluation
            Route::get('/create/{office?}', [AttendanceEvaluationController::class, 'create'])->name('create');
            Route::get('/preview', [AttendanceEvaluationController::class, 'preview'])->name('preview');
            Route::post('/submit', [AttendanceEvaluationController::class, 'submit'])->name('submit');
            Route::get('/view/{office?}', [AttendanceEvaluationController::class, 'view'])->name('view');
        });


    // Evaluation 
    Route::prefix('evaluations')
        ->name('evaluations.')
        ->group(function () {

            Route::get('/', [EvaluationController::class, 'index'])->name('index');
            Route::get('/create', [EvaluationController::class, 'create'])->name('evaluations.create');
            Route::get('/history', [EvaluationController::class, 'history'])->name('history');
            Route::get('/list', [EvaluationController::class, 'list'])->middleware('role:super_admin,organization_admin,department_admin')->name('list');
            Route::get('/{evaluation}', [EvaluationController::class, 'show'])->name('show');
            Route::post('/', [EvaluationController::class, 'store'])->name('store');
            Route::get('/departments', [EvaluationController::class, 'departments'])->name('departments');

        });

    // Evaluation Report
    Route::prefix('reports')->name('reports.')->middleware(['role:super_admin,organization_admin,department_admin'])
        ->group(function () {
            Route::get('/evaluation/preview', [EvaluationReportController::class, 'preview'])->name('evaluation.preview');
            Route::get('/evaluations/export-word', [EvaluationReportController::class, 'exportWord'])->name('evaluations.export.word');
        });


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Route::get('/test-lunar', function () {
    //     return toLunarDate(
    //         CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh')
    //     )->toString();
    // });


});
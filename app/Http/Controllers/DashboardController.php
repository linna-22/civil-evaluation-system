<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Models\Department;
use App\Models\Organization;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
// public function index(DashboardService $dashboardService)
//     {
//         $user = Auth::user();

//         switch ($user->role) {

//             case 'user':
//                 return app(EmployeeDashboardController::class)->index();

//             case 'super_admin':
//                 return view('dashboard.index', [
//                     'statistics' => $dashboardService->statistics(),
//                 ]);

//             case 'department_admin':
//                 abort(501, 'Department dashboard is not implemented yet.');

//             case 'organization_admin':
//                 abort(501, 'Organization dashboard is not implemented yet.');

//             default:
//                 abort(403);
//         }
//     }
public function index(DashboardService $dashboardService)
    {
        $user = Auth::user();

        return view('dashboard.index', [
                    'statistics' => $dashboardService->statistics(),
                    'user' => $user,
                ]);
    }
}

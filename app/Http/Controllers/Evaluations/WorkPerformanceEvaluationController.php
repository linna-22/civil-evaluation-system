<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Office;
use App\Services\WorkPerformanceEvaluationService;

class WorkPerformanceEvaluationController extends Controller
{
    /**
     * Display work performance evaluation page.
     */
    public function index()
    {
        $user = auth()->user();

        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }

        // Get offices in admin's department
        $offices = Office::query()
            ->where('department_id', $user->department_id)
            ->withCount([
                'users' => function ($query) {
                    $query->where('status', 'active')
                        ->where('is_leader', false);
                }
            ])
            ->orderBy('office_name_kh')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Department has no offices
        |--------------------------------------------------------------------------
        |
        | If there are no offices, go directly to the department users.
        |
        */

        if ($offices->isEmpty()) {
            return redirect()->route(
                'evaluations.work-performance.department.users',
                $user->department_id
            );
        }

        return view(
            'evaluations.work-performance.index',
            compact('offices')
        );
    }


    /**
     * Display users under a department.
     */
    public function usersByDepartment(Department $department)
    {
        $user = auth()->user();

        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }

        // Department must belong to logged-in admin
        if ($department->department_id !== $user->department_id) {
            abort(403);
        }

        $users = $department->users()
            ->whereNull('office_id')
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();

        // No office
        $office = null;

        return view(
            'evaluations.work-performance.users',
            compact(
                'department',
                'office',
                'users'
            )
        );
    }


    /**
     * Display users under an office.
     */
    public function usersByOffice(Office $office)
    {
        $user = auth()->user();

        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }

        // Office must belong to admin's department
        if ($office->department_id !== $user->department_id) {
            abort(403);
        }

        $department = $office->department;

        $users = $office->users()
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();

        return view(
            'evaluations.work-performance.users',
            compact(
                'department',
                'office',
                'users'
            )
        );
    }


    /**
     * Show work performance evaluation form.
     */
    public function create(WorkPerformanceEvaluationService $service)
    {
        $user = auth()->user();
        $users = $service->getEligibleUsers();
        if ($users->isEmpty()) {
            abort(404, 'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ');
        }
        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }

        //  The Service handles the session and user list.

        if (!session()->has('work_performance_user_ids')) {
            $service->startEvaluation();
        }

        //  Get current evaluation data
        $currentUser = $service->getCurrentUser();

        if (!$currentUser) {
            abort(404, 'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ');
        }

        $currentUserNumber = $service->getCurrentUserNumber();
        $totalUsers = $service->getTotalUsers();

        return view(
            'evaluations.work-performance.create',
            compact(
                'currentUser',
                'currentUserNumber',
                'totalUsers',
                'users'
            )
        );
    }


    /**
     * Display evaluation preview.
     */
    public function preview()
    {
        return view(
            'evaluations.work-performance.preview'
        );
    }
}
<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;

class WorkPerformanceEvaluationController extends Controller
{
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
                    $query->where('status', 'active');
                }
            ])
            ->orderBy('office_name_kh')
            ->get();

        // If department has no offices,
        // go directly to users under department
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

    public function usersByDepartment(Department $department)
    {
        $user = auth()->user();

        // Security: only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }

        // Security: department must belong to logged-in admin
        if ($department->department_id !== $user->department_id) {
            abort(403);
        }

        $users = $department->users()
            ->whereNull('office_id')
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();

        // This department has no office
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
    public function usersByOffice(Office $office)
    {
        $user = auth()->user();
        if ($user->role !== 'department_admin') {
            abort(403);
        }
        // Office must belong to admin's department
        if ($office->department_id !== $user->department_id) {
            abort(403);
        }
        // Get Department
        $department = $office->department;
        // Get Users in Office
        $users = $office->users()
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();
        return view(
            'evaluations.work-performance.users',
            compact(
                'office',
                'department',
                'users'
            )
        );
    }
    public function create(User $user)
    {
        return view('evaluations.work-performance.create', compact('user'));
    }
}

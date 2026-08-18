<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Office;
use Illuminate\Http\Request;

class WorkAttendanceEvaluationController extends Controller
{

    // Display departments for organization admin.
    public function index()
    {
        $user = auth()->user();
        $departments = Department::query()->where('organization_id', $user->organization_id)->orderBy('department_name_kh')->get();
        return view('evaluations.work-attendance.index', compact('departments'));
    }

    // Display offices inside selected department.
    public function offices(Department $department)
    {
        $user = auth()->user();
        // Security Check
        if ($department->organization_id !== $user->organization_id) {
            abort(403);
        }

        // Get Offices + Eligible User Count
        $offices = $department->offices()
            ->withCount(['users as eligible_users_count' => function ($query) {
                    $query
                        ->where('status', 'active')
                        ->where('is_leader', false);
                }
            ])
            ->orderBy('office_name_kh')
            ->get();

        return view('evaluations.work-attendance.offices', compact('department', 'offices'));
    }
    public function usersByOffice(Office $office)
    {
        $user = auth()->user();
        // Security Check
        if ($office->department->organization_id !== $user->organization_id) {
            abort(403);
        }

        //  Get Users
        $users = $office->users()
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();

        // Return Users Page
        return view('evaluations.work-attendance.users',
            [
                'users' => $users,
                'office' => $office,
                'department' => $office->department,
            ]
        );
    }
    public function usersByDepartment(Department $department)
    {
        $user = auth()->user();
        // Security Check
        if ($department->organization_id !== $user->organization_id) {
            abort(403);
        }

        // Department Must Have No Offices
        if ($department->offices()->exists()) {
            abort(404);
        }

        // Get Users
        $users = $department->users()
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();

        return view('evaluations.work-attendance.users',
            [
                'users' => $users,
                'office' => null,
                'department' => $department,
            ]
        );
    }
}
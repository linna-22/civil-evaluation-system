<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Services\AttendanceEvaluationService;
use App\Models\Department;
use App\Models\Office;
use Illuminate\Http\Request;

class AttendanceEvaluationController extends Controller
{
    /**
     * Display attendance evaluation departments/offices.
     */
    public function index(AttendanceEvaluationService $service)
    {
        return $service->index(
            auth()->user()
        );
    }


    /**
     * Display users without office.
     */
    public function usersByDepartment(
        Department $department,
        AttendanceEvaluationService $service
    ) {
        return $service->usersByDepartment(
            auth()->user(),
            $department
        );
    }


    /**
     * Display users under an office.
     */
    public function usersByOffice(
        Office $office,
        AttendanceEvaluationService $service
    ) {
        return $service->usersByOffice(
            auth()->user(),
            $office
        );
    }


    /**
     * Create attendance evaluation.
     */
    public function create(
        AttendanceEvaluationService $service,
        ?int $office = null
    ) {
        return $service->create(
            auth()->user(),
            $office
        );
    }


    /**
     * Preview attendance evaluation.
     */
    public function preview(AttendanceEvaluationService $service)
    {
        return $service->preview(
            auth()->user()
        );
    }


    /**
     * Submit attendance evaluation.
     */
    public function submit(
        Request $request,
        AttendanceEvaluationService $service
    ) {
        return $service->submit(
            auth()->user(),
            $request->all()
        );
    }


    /**
     * View submitted attendance evaluations.
     */
    public function view(
        AttendanceEvaluationService $service,
        ?int $office = null
    ) {
        return $service->view(
            auth()->user(),
            $office
        );
    }
}
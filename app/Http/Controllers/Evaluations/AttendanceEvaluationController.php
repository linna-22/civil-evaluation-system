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
     * Create attendance evaluation.
     */
    public function create(
        AttendanceEvaluationService $service
    ) {
        return $service->create(
            auth()->user()
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
     * Display submitted attendance evaluations.
     */
    public function view(
        Request $request,
        AttendanceEvaluationService $service
    ) {
        return $service->view(
            auth()->user(),
            $request
        );
    }
}
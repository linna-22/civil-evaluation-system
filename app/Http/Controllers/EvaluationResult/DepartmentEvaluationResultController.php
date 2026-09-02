<?php

namespace App\Http\Controllers\EvaluationResult;

use App\Http\Controllers\Controller;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationSummary;
use App\Models\User;
use App\Services\DepartmentEvaluationResultService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentEvaluationResultController extends Controller
{
    /**
     * AJAX data for department evaluation results.
     */
    public function data(
        Request $request,
        EvaluationPeriod $evaluationPeriod,
        DepartmentEvaluationResultService $service
    ) {
        $results = $service->getDepartmentResults(
            auth()->user(),
            $evaluationPeriod,
            $request
        );

        return response()->json([
            'success' => true,
            'message' => 'Department evaluation results loaded successfully.',
            'data' => $results,
        ]);
    }


    /**
     * Display all closed evaluation periods.
     */
    public function index(
        DepartmentEvaluationResultService $service
    ): View {

        if (auth()->user()->role !== 'department_admin') {
            abort(403);
        }
        $periods = $service->getClosedPeriods();

        return view(
            'evaluation-results.department.index',
            compact('periods')
        );
    }


    /**
     * Display evaluation results
     * for users in the department.
     */
    public function show(
        EvaluationPeriod $evaluationPeriod,
        DepartmentEvaluationResultService $service
    ): View {

        return view(
            'evaluation-results.department.show',
            compact('evaluationPeriod')
        );
    }

    public function updateRemark(
        Request $request,
        EvaluationSummary $evaluationSummary,
        DepartmentEvaluationResultService $service
    ) {
        $request->validate([
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $service->updateRemark(
            $evaluationSummary,
            $request->input('remarks')
        );

        return response()->json([
            'message' => 'មូលវិចារណ៍ត្រូវបានរក្សាទុកដោយជោគជ័យ។',
        ]);
    }
    public function print(
        EvaluationPeriod $evaluationPeriod,
        User $user,
        DepartmentEvaluationResultService $service
    ): View {

        $departmentAdmin = auth()->user();
        if ($departmentAdmin->role !== 'department_admin') {
            abort(403);
        }

        $result = $service->getUserResult(
            $departmentAdmin,
            $evaluationPeriod,
            $user
        );

        if (!$result) {
            abort(404, 'Evaluation result not found.');
        }

        return view(
            'evaluation-results.department.print',
            compact(
                'evaluationPeriod',
                'result',
                'departmentAdmin'

            )
        );
    }
}
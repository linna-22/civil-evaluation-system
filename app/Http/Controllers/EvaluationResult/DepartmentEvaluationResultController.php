<?php

namespace App\Http\Controllers\EvaluationResult;

use App\Exports\DepartmentEvaluationWordBulkExport;
use App\Exports\DepartmentEvaluationWordExport;
use App\Http\Controllers\Controller;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationSummary;
use App\Models\Office;
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

        if (
            !in_array(auth()->user()->role, [
                'department_admin',
                'super_admin',
            ])
        ) {
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
    public function show(EvaluationPeriod $evaluationPeriod, DepartmentEvaluationResultService $service): View
    {
         if (
            !in_array(auth()->user()->role, [
                'department_admin',
                'super_admin',
            ])
        ) {
            abort(403);
        }
        $departmentAdmin = auth()->user();

        $offices = Office::query()
            ->where('department_id', $departmentAdmin->department_id)
            ->orderBy('office_name_kh')
            ->get();
        return view(
            'evaluation-results.department.show',
            compact('evaluationPeriod', 'offices')
        );
    }

    /**
     * Review one employee's evaluation result.
     */
    public function review(
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
            'evaluation-results.department.review',
            compact(
                'evaluationPeriod',
                'result',
                'departmentAdmin'
            )
        );
    }

    /**
     * Edit one employee's submitted Work Performance evaluation.
     */
    public function editWorkPerformance(
        EvaluationPeriod $evaluationPeriod,
        User $user,
        DepartmentEvaluationResultService $service
    ): View {

        $departmentAdmin = auth()->user();

        if ($departmentAdmin->role !== 'department_admin') {
            abort(403);
        }

        $evaluation = $service->getWorkPerformanceForEdit(
            $departmentAdmin,
            $evaluationPeriod,
            $user
        );

        if (!$evaluation) {
            abort(404, 'Work Performance evaluation not found.');
        }

        return view('evaluation-results.department.work-performance.edit',
            compact(
                'evaluationPeriod',
                'evaluation',
                'departmentAdmin'
            )
        );
    }


    /**
     * Update one employee's submitted Work Performance evaluation.
     */
    public function updateWorkPerformance(
        Request $request,
        EvaluationPeriod $evaluationPeriod,
        User $user,
        DepartmentEvaluationResultService $service
    ) {

        $departmentAdmin = auth()->user();

        if ($departmentAdmin->role !== 'department_admin') {
            abort(403);
        }

        $request->validate([
            'performances' => ['required', 'array'],
            'performances.*.work_performance_id' => ['nullable', 'integer'],
            'performances.*.activity' => ['nullable', 'string', 'max:1000'],
            'performances.*.indicator' => ['nullable', 'string', 'max:1000'],
            'performances.*.achievement_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $service->updateWorkPerformance(
            $departmentAdmin,
            $evaluationPeriod,
            $user,
            $request->input('performances', [])
        );

        return redirect()
            ->route(
                'department-evaluation-results.review',
                [
                    'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                    'user' => $user->user_id,
                ]
            )
            ->with('success', 'សមិទ្ធកម្មការងារត្រូវបានកែប្រែ និងគណនាពិន្ទុឡើងវិញដោយជោគជ័យ។');
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
    public function print(EvaluationPeriod $evaluationPeriod, User $user, DepartmentEvaluationResultService $service): View
    {

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
    public function downloadWord(EvaluationPeriod $evaluationPeriod, User $user, DepartmentEvaluationResultService $service)
    {
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

        $export = new DepartmentEvaluationWordExport(
            $evaluationPeriod,
            $result,
            $departmentAdmin
        );

        return $export->download();
    }
    public function downloadPdf(Request $request, EvaluationPeriod $evaluationPeriod, DepartmentEvaluationResultService $service)
    {
        $departmentAdmin = auth()->user();
        if ($departmentAdmin->role !== 'department_admin') {
            abort(403);
        }
        $results = $service->getDepartmentResultsForExport(
            $departmentAdmin,
            $evaluationPeriod,
            $request
        );
        if ($results->isEmpty()) {
            abort(404, 'No evaluation results found.');
        }
        return view(
            'evaluation-results.department.print-all',
            compact(
                'evaluationPeriod',
                'results',
                'departmentAdmin'
            )
        );
    }
    public function downloadWordAll(Request $request, EvaluationPeriod $evaluationPeriod, DepartmentEvaluationResultService $service) {
        $departmentAdmin = auth()->user();
        if ($departmentAdmin->role !== 'department_admin') {
            abort(403);
        }
        $results = $service->getDepartmentResultsForExport(
            $departmentAdmin,
            $evaluationPeriod,
            $request
        );
        if ($results->isEmpty()) {
            abort(404, 'No evaluation results found.');
        }
        $export = new DepartmentEvaluationWordBulkExport(
            $evaluationPeriod,
            $results,
            $departmentAdmin
        );
        return $export->download();
    }
}
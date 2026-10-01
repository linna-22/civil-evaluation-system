<?php

namespace App\Http\Controllers\EvaluationResult;

use App\Http\Controllers\Controller;
use App\Models\EvaluationPeriod;
use App\Models\Evaluation;
use App\Models\EvaluationAttendance;
use App\Models\EvaluationSummary;
use App\Models\Office;
use App\Services\DepartmentEvaluationResultService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class DepartmentEvaluationOutcomeController extends Controller
{
    public function workPerformance(Request $request): View
    {
        return $this->periodCards($request, 'work-performance', 'សមិទ្ធកម្មការងារ', 'លទ្ធផលវាយតម្លៃសមិទ្ធកម្មការងាររបស់មន្ត្រី');
    }

    public function attendance(Request $request): View
    {
        return $this->periodCards($request, 'attendance', 'វត្តមាន', 'លទ្ធផលវាយតម្លៃវត្តមានរបស់មន្ត្រី');
    }

    public function behavior(Request $request): View
    {
        return $this->periodCards($request, 'behavior', 'ឥរិយាបថ', 'លទ្ធផលវាយតម្លៃឥរិយាបថរបស់មន្ត្រី');
    }

    public function overall(Request $request): View
    {
        return $this->periodCards($request, 'overall', 'លទ្ធផលវាយតម្លៃរួម', 'លទ្ធផលវាយតម្លៃរួមរបស់មន្ត្រី');
    }

    public function workPerformanceDetail(Request $request, EvaluationPeriod $evaluationPeriod): View
    {
        return $this->detailView($request, $evaluationPeriod, 'work-performance');
    }

    public function attendanceDetail(Request $request, EvaluationPeriod $evaluationPeriod): View
    {
        return $this->detailView($request, $evaluationPeriod, 'attendance');
    }

    public function behaviorDetail(Request $request, EvaluationPeriod $evaluationPeriod): View
    {
        return $this->detailView($request, $evaluationPeriod, 'behavior');
    }

    public function overallDetail(Request $request, EvaluationPeriod $evaluationPeriod): View
    {
        return $this->detailView($request, $evaluationPeriod, 'overall');
    }

    public function workPerformanceData(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        return $this->data($request, $evaluationPeriod, 'work-performance');
    }

    public function attendanceData(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        return $this->data($request, $evaluationPeriod, 'attendance');
    }

    public function behaviorData(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        return $this->data($request, $evaluationPeriod, 'behavior');
    }

    public function overallData(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        return $this->data($request, $evaluationPeriod, 'overall');
    }

    public function finalize(
        Request $request,
        string $type,
        EvaluationPeriod $evaluationPeriod,
        DepartmentEvaluationResultService $service
    ) {
        $admin = $request->user();

        if ($admin->role !== 'department_admin') {
            abort(403);
        }

        if (!in_array($type, [
            'work-performance',
            'attendance',
            'behavior',
            'overall',
        ], true)) {
            abort(404);
        }

        $service->finalizeDepartment($admin, $evaluationPeriod);

        return redirect()
            ->route("evaluation-results.{$type}.show", $evaluationPeriod->evaluation_period_id)
            ->with('success', 'លទ្ធផលត្រូវបានបញ្ជាក់ដោយជោគជ័យ។');
    }

    private function data(Request $request, EvaluationPeriod $evaluationPeriod, string $type)
    {
        $admin = $request->user();

        abort_unless(
            in_array($admin->role, ['department_admin', 'super_admin'], true),
            403
        );

        $config = $this->typeConfig($type);

        $perPage = (int) $request->input('per_page', 5);
        if (!in_array($perPage, [5, 10, 25, 50, 100], true)) {
            $perPage = 5;
        }

        $query = EvaluationSummary::query()
            ->with([
                'evaluationPeriodUser.user',
                'evaluationPeriodUser.evaluationPeriod',
            ])
            ->whereHas('evaluationPeriodUser', function ($periodUserQuery) use ($admin, $evaluationPeriod) {
                $periodUserQuery
                    ->where('evaluation_period_id', $evaluationPeriod->evaluation_period_id)
                    ->whereHas('user', function ($userQuery) use ($admin) {
                        if ($admin->role === 'department_admin') {
                            $userQuery->where('department_id', $admin->department_id);
                        }

                        $userQuery->whereNotIn('role', [
                            'super_admin',
                            'evaluation_admin',
                            'department_admin',
                        ]);
                    });
            });

        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            $query->whereHas('evaluationPeriodUser.user', function ($userQuery) use ($search) {
                $userQuery->where(function ($q) use ($search) {
                    $q->where('name_kh', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%")
                        ->orWhere('id_code', 'like', "%{$search}%");
                });
            });
        }

        $officeId = $request->input('office_id');
        if ($officeId !== null && $officeId !== '') {
            $query->whereHas('evaluationPeriodUser.user', function ($userQuery) use ($officeId) {
                $userQuery->where('office_id', $officeId);
            });
        }

        $results = $query
            ->orderByDesc($config['score_column'])
            ->orderBy('evaluation_summary_id')
            ->paginate($perPage)
            ->appends($request->only(['search', 'office_id', 'per_page']));

        // Keep overtime hours consistent with the existing
        // Department Evaluation Results implementation.
        $results->getCollection()->transform(function ($result) use ($evaluationPeriod) {
            $userId = $result->evaluationPeriodUser?->user_id;

            if (!$userId) {
                $result->overtime_hours = 0;
                return $result;
            }

            $attendanceEvaluation = Evaluation::query()
                ->where('evaluation_period_id', $evaluationPeriod->evaluation_period_id)
                ->where('evaluatee_id', $userId)
                ->where('evaluation_type', 'attendance')
                ->first();

            if (!$attendanceEvaluation) {
                $result->overtime_hours = 0;
                return $result;
            }

            $attendance = EvaluationAttendance::query()
                ->where('evaluation_id', $attendanceEvaluation->evaluation_id)
                ->first();

            $result->overtime_hours = (float) ($attendance?->overtime_hours ?? 0);

            return $result;
        });

        return response()->json([
            'success' => true,
            'message' => 'Evaluation results loaded successfully.',
            'data' => $results,
        ]);
    }

    private function periodCards(Request $request, string $type, string $title, string $description): View
    {
        $admin = $request->user();

        abort_unless(
            in_array($admin->role, ['department_admin', 'super_admin'], true),
            403
        );

        $evaluationPeriods = EvaluationPeriod::query()
            ->whereHas('periodUsers', function ($query) use ($admin) {
                if ($admin->role === 'department_admin') {
                    $query->whereHas('user', function ($userQuery) use ($admin) {
                        $userQuery->where('department_id', $admin->department_id)
                            ->whereNotIn('role', [
                                'super_admin',
                                'evaluation_admin',
                                'department_admin',
                            ]);
                    });
                }
            })
            ->withCount([
                'periodUsers as participants_count' => function ($query) use ($admin) {
                    if ($admin->role === 'department_admin') {
                        $query->whereHas('user', function ($userQuery) use ($admin) {
                            $userQuery->where('department_id', $admin->department_id)
                                ->whereNotIn('role', [
                                    'super_admin',
                                    'evaluation_admin',
                                    'department_admin',
                                ]);
                        });
                    }
                },
            ])
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('evaluation_period_id')
            ->get();

        return view('evaluation-results.outcome.periods', compact(
            'evaluationPeriods',
            'type',
            'title',
            'description'
        ));
    }

    private function detailView(Request $request, EvaluationPeriod $evaluationPeriod, string $type): View
    {
        $admin = $request->user();

        abort_unless(
            in_array($admin->role, ['department_admin', 'super_admin'], true),
            403
        );

        $config = $this->typeConfig($type);

        $departmentPeriod = null;
        $isFinalized = false;

        if ($admin->role === 'department_admin') {
            $departmentPeriod = $evaluationPeriod->departments()
                ->where('department_id', $admin->department_id)
                ->first();

            if (!$departmentPeriod) {
                abort(403, 'នាយកដ្ឋានរបស់អ្នកមិនបានចូលរួមក្នុងការវាយតម្លៃនេះទេ។');
            }

            $isFinalized = $departmentPeriod->review_status === 'finalized';
        }

        $offices = Office::query()
            ->when(
                $admin->role === 'department_admin',
                fn ($officeQuery) => $officeQuery->where('department_id', $admin->department_id)
            )
            ->orderBy('office_name_kh')
            ->get();

        return view('evaluation-results.outcome.detail', compact(
            'evaluationPeriod',
            'offices',
            'type',
            'config',
            'departmentPeriod',
            'isFinalized'
        ));
    }

    private function typeConfig(string $type): array
    {
        return match ($type) {
            'work-performance' => [
                'title' => 'សមិទ្ធកម្មការងារ',
                'description' => 'លទ្ធផលវាយតម្លៃសមិទ្ធកម្មការងាររបស់មន្ត្រី',
                'score_column' => 'work_performance_score',
                'icon' => 'clipboard-check',
            ],
            'attendance' => [
                'title' => 'វត្តមាន',
                'description' => 'លទ្ធផលវាយតម្លៃវត្តមានរបស់មន្ត្រី',
                'score_column' => 'attendance_score',
                'icon' => 'calendar-check',
            ],
            'behavior' => [
                'title' => 'ឥរិយាបថ',
                'description' => 'លទ្ធផលវាយតម្លៃឥរិយាបថរបស់មន្ត្រី',
                'score_column' => 'behavior_score',
                'icon' => 'user-check',
            ],
            'overall' => [
                'title' => 'លទ្ធផលវាយតម្លៃរួម',
                'description' => 'លទ្ធផលវាយតម្លៃរួមរបស់មន្ត្រី',
                'score_column' => 'total_score',
                'icon' => 'chart-no-axes-combined',
            ],
            default => abort(404),
        };
    }
}

<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationWorkPerformance;
use App\Models\Office;
use App\Services\WorkPerformanceEvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkPerformanceEvaluationController extends Controller
{
    /**
     * Display work performance evaluation page.
     */
    public function index(
        WorkPerformanceEvaluationService $service
    ) {
        $evaluationPeriod =
            $service->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return view(
                'evaluations.work-performance.index',
                [
                    'evaluationPeriod' => null,
                    'assignment' => null,
                    'office' => null,
                    'users' => collect(),
                    'submittedUserIds' => [],
                    'allUsersSubmitted' => false,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get logged-in user's assignment
        |--------------------------------------------------------------------------
        */

        $assignment =
            $service->getCurrentAssignment(
                $evaluationPeriod
            );

        if (!$assignment) {
            abort(
                403,
                'អ្នកមិនទាន់ត្រូវបានកំណត់ជាអ្នកបញ្ចូលទិន្នន័យសម្រាប់វគ្គវាយតម្លៃនេះទេ។'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get users according to assignment
        |--------------------------------------------------------------------------
        */

        $users =
            $service->getEligibleUsers(
                $evaluationPeriod
            );

        /*
        |--------------------------------------------------------------------------
        | Office information
        |--------------------------------------------------------------------------
        |
        | Only office assignment has an office.
        |
        */

        $office = null;

        if (
            $assignment->scope === 'office' &&
            $assignment->office_id
        ) {
            $office = $assignment->office;
        }

        /*
        |--------------------------------------------------------------------------
        | Submitted users
        |--------------------------------------------------------------------------
        */

        $submittedUserIds =
            $service->getSubmittedUserIds(
                $users
                    ->pluck('user_id')
                    ->toArray()
            );

        $allUsersSubmitted =
            $service->allUsersSubmitted(
                $users
                    ->pluck('user_id')
                    ->toArray()
            );

        return view(
            'evaluations.work-performance.index',
            compact(
                'evaluationPeriod',
                'assignment',
                'office',
                'users',
                'submittedUserIds',
                'allUsersSubmitted'
            )
        );
    }
    /**
     * Show work performance evaluation form.
     */
    public function create(
        WorkPerformanceEvaluationService $service
    ) {
        $evaluationPeriod =
            $service->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            abort(
                404,
                'បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃដែលកំពុងបើកទេ។'
            );
        }

        $assignment =
            $service->getCurrentAssignment(
                $evaluationPeriod
            );

        if (!$assignment) {
            abort(
                403,
                'អ្នកមិនទាន់ត្រូវបានកំណត់ជាអ្នកបញ្ចូលទិន្នន័យសម្រាប់វគ្គវាយតម្លៃនេះទេ។'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure the session belongs to this assignment
        |--------------------------------------------------------------------------
        */

        $sessionPeriodId = session(
            'work_performance_evaluation_period_id'
        );

        $sessionAssignmentId = session(
            'work_performance_assignment_id'
        );

        if (
            (int) $sessionPeriodId !==
            (int) $evaluationPeriod->evaluation_period_id
            ||
            (int) $sessionAssignmentId !==
            (int) $assignment->evaluation_data_entry_assignment_id
        ) {
            $service->startEvaluation();
        }

        $currentUser =
            $service->getCurrentUser();

        if (!$currentUser) {
            abort(
                404,
                'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ។'
            );
        }

        $users = $service->getEligibleUsers($evaluationPeriod);

        return view(
            'evaluations.work-performance.create',
            [
                'evaluationPeriod' => $evaluationPeriod,
                'assignment' => $assignment,
                'currentUser' => $currentUser,
                'currentUserNumber' =>
                    $service->getCurrentUserNumber(),
                'totalUsers' =>
                    $service->getTotalUsers(),
                'users' => $users,
            ]
        );
    }
    /**
     * Display work performance evaluation preview.
     */
    public function preview(WorkPerformanceEvaluationService $service)
    {
        $evaluationPeriod = $service->getOpenEvaluationPeriod();

        if (!$evaluationPeriod || !$service->getCurrentAssignment($evaluationPeriod)) {
            abort(403);
        }

        return view('evaluations.work-performance.preview');
    }

    /**
     * Store work performance evaluation.
     */
    public function submit(
        Request $request,
        WorkPerformanceEvaluationService $service
    ) {
        $request->validate([
            'users' => ['required', 'array'],
            'users.*.user_id' => ['required', 'integer'],
            'users.*.answers' => ['required', 'array'],
        ]);

        $evaluationPeriodId = session()->get(
            'work_performance_evaluation_period_id'
        );

        if (!$evaluationPeriodId) {
            return response()->json([
                'success' => false,
                'message' => 'មិនមានវគ្គវាយតម្លៃសម្រាប់ការវាយតម្លៃនេះទេ។',
            ], 422);
        }

        $evaluationPeriod = EvaluationPeriod::query()
            ->where('evaluation_period_id', $evaluationPeriodId)
            ->where('status', 'open')
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->first();

        if (!$evaluationPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'វគ្គវាយតម្លៃនេះមិនទាន់បើក ឬបានបិទរួចហើយ។',
            ], 422);
        }

        if (!$service->getCurrentAssignment($evaluationPeriod)) {
            return response()->json([
                'success' => false,
                'message' => 'អ្នកមិនត្រូវបានកំណត់ជាអ្នកបញ្ចូលទិន្នន័យសម្រាប់វគ្គនេះទេ។',
            ], 403);
        }

        DB::beginTransaction();

        try {
            foreach ($request->users as $userData) {
                $evaluateeId = (int) $userData['user_id'];

                // Security: evaluatee must belong to the logged-in
                // user's assigned data-entry scope.
                $evaluatee = $service->getEligibleUser(
                    $evaluationPeriod,
                    $evaluateeId
                );

                if (!$evaluatee) {
                    throw new \Exception(
                        'មន្ត្រីមិនស្ថិតក្នុងវិសាលភាពដែលអ្នកទទួលខុសត្រូវទេ។'
                    );
                }

                $existingEvaluation = Evaluation::query()
                    ->where(
                        'evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    )
                    ->where(
                        'evaluatee_id',
                        $evaluateeId
                    )
                    ->where(
                        'evaluation_type',
                        'work_performance'
                    )
                    ->first();

                if ($existingEvaluation) {
                    throw new \Exception(
                        "មន្ត្រី {$evaluatee->name_kh} បានវាយតម្លៃរួចហើយ។"
                    );
                }

                $evaluation = Evaluation::create([
                    'evaluation_period_id' => $evaluationPeriod->evaluation_period_id,
                    'evaluator_id' => auth()->id(),
                    'evaluatee_id' => $evaluateeId,
                    'evaluation_type' => 'work_performance',
                    'evaluation_status' => 'submitted',
                    'submitted_at' => now(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                $performances = $userData['answers']['performances'] ?? [];
                $validPerformances = [];

                foreach ($performances as $performance) {
                    $activity = trim($performance['activity'] ?? '');
                    $indicator = trim($performance['indicator'] ?? '');
                    $achievement = (float) (
                        $performance['achievement_percent'] ?? 0
                    );

                    if (
                        $activity === '' &&
                        $indicator === '' &&
                        $achievement === 0
                    ) {
                        continue;
                    }

                    $achievement = max(0, min(100, $achievement));

                    $validPerformances[] = [
                        'activity' => $activity,
                        'indicator' => $indicator,
                        'achievement_percent' => $achievement,
                    ];
                }

                $numberOfPerformances = count($validPerformances);
                $weight = $numberOfPerformances > 0
                    ? 100 / $numberOfPerformances
                    : 0;

                foreach ($validPerformances as $performance) {
                    $achievement = $performance['achievement_percent'];
                    $score = round(
                        ($achievement * $weight) / 100,
                        2
                    );

                    EvaluationWorkPerformance::create([
                        'evaluation_id' => $evaluation->evaluation_id,
                        'activity' => $performance['activity'],
                        'indicator' => $performance['indicator'],
                        'achievement_percent' => $achievement,
                        'score' => $score,
                    ]);
                }
            }

            DB::commit();

            session()->forget([
                'work_performance_user_ids',
                'work_performance_current_index',
                'work_performance_evaluation_period_id',
                'work_performance_assignment_id',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ការវាយតម្លៃត្រូវបានបញ្ជូនដោយជោគជ័យ។',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display work performance evaluation results for a department admin.
     */
    public function view(
        WorkPerformanceEvaluationService $service,
        ?int $office = null
    ) {
        $user = auth()->user();

        if ($user->role !== 'department_admin') {
            abort(403);
        }

        $evaluationPeriod = $service->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            abort(
                404,
                'បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃដែលកំពុងបើកទេ។'
            );
        }

        $officeModel = null;

        if ($office) {
            $officeModel = Office::query()
                ->where('office_id', $office)
                ->where('department_id', $user->department_id)
                ->firstOrFail();

            $department = $officeModel->department;

            // Office scope: all active participants in the selected office,
            // regardless of role or is_leader.
            $users = $officeModel->users()
                ->where('status', 'active')
                ->whereHas('evaluationPeriodUsers', function ($query) use ($evaluationPeriod) {
                    $query->where(
                        'evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    );
                })
                ->orderBy('name_kh')
                ->get();
        } else {
            $department = $user->department;

            // Department scope: only participants with no office.
            // department_admin is not an evaluatee in this scope.
            $users = $department->users()
                ->where('status', 'active')
                ->whereNull('office_id')
                ->where(function ($query) {
                    $query->whereNull('role')
                        ->orWhere('role', '<>', 'department_admin');
                })
                ->whereHas('evaluationPeriodUsers', function ($query) use ($evaluationPeriod) {
                    $query->where(
                        'evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    );
                })
                ->orderBy('name_kh')
                ->get();
        }

        $evaluations = Evaluation::query()
            ->with([
                'evaluatee',
                'workPerformance',
            ])
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'evaluation_type',
                'work_performance'
            )
            ->where(
                'evaluation_status',
                'submitted'
            )
            ->whereIn(
                'evaluatee_id',
                $users->pluck('user_id')
            )
            ->get()
            ->keyBy('evaluatee_id');

        return view(
            'evaluations.work-performance.view',
            compact(
                'users',
                'evaluations',
                'department',
                'officeModel',
                'evaluationPeriod'
            )
        );
    }
}

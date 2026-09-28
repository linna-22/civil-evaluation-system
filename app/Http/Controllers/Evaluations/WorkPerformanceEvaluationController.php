<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationWorkPerformance;
use App\Models\Office;
use App\Models\User;
use App\Services\WorkPerformanceEvaluationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class WorkPerformanceEvaluationController extends Controller
{
    /**
     * Display work performance evaluation page.
     */
    public function index(WorkPerformanceEvaluationService $service)
    {
        $user = auth()->user();
        // Only Office Admin
        if ($user->role !== 'office_admin') {
            abort(403);
        }
        // Check Open Evaluation Period
        $evaluationPeriod = $service->getOpenEvaluationPeriod();
        // No Open Evaluation Period
        if (!$evaluationPeriod) {
            return view(
                'evaluations.work-performance.index',
                [
                    'office' => null,
                    'users' => collect(),
                    'evaluationPeriod' => null,
                ]
            );
        }

        // Get Office Admin's own office
        $office = Office::query()
            ->where('office_id', $user->office_id)
            ->where('department_id', $user->department_id)
            ->first();

        if (!$office) {
            abort(
                404,
                'មិនមានការិយាល័យសម្រាប់អ្នកប្រើប្រាស់នេះទេ។'
            );
        }

        // Get eligible users in this office
        $users = $service->getEligibleUsers();

        // Get submitted users
        $submittedUserIds = $service->getSubmittedUserIds(
            $users->pluck('user_id')->toArray()
        );

        // Check whether all users have submitted
        $allUsersSubmitted = $service->allUsersSubmitted(
            $users->pluck('user_id')->toArray()
        );

        return view(
            'evaluations.work-performance.index',
            compact(
                'office',
                'users',
                'submittedUserIds',
                'allUsersSubmitted',
                'evaluationPeriod'
            )
        );
    }


    /**
     * Show work performance evaluation form.
     */
    public function create(WorkPerformanceEvaluationService $service)
    {
        $user = auth()->user();

        // Only Office Admin
        if ($user->role !== 'office_admin') {
            abort(403);
        }

        // Office Admin must have an office
        if (!$user->office_id) {
            abort(
                404,
                'មិនមានការិយាល័យសម្រាប់អ្នកប្រើប្រាស់នេះទេ។'
            );
        }

        // Check Open Evaluation Period
        $evaluationPeriod = $service->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            abort(
                404,
                'បច្ចុប្បន្នមិនមានការវាយតម្លៃដែលកំពុងដំណើរការទេ។'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Office Admin's Own Office
        |--------------------------------------------------------------------------
        */

        $office = Office::query()
            ->where('office_id', $user->office_id)
            ->where('department_id', $user->department_id)
            ->first();

        if (!$office) {
            abort(
                404,
                'មិនមានការិយាល័យសម្រាប់អ្នកប្រើប្រាស់នេះទេ។'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Start Evaluation Session
        |--------------------------------------------------------------------------
        |
        | The office is automatically taken from the logged-in
        | Office Admin. There is no office selection anymore.
        |
        */

        $sessionOfficeId = session('work_performance_office_id');

        /*
        | Start a new evaluation if:
        | - no users in session
        | - office changed
        | - evaluation period changed
        */

        if (
            !session()->has('work_performance_user_ids') ||
            $sessionOfficeId != $user->office_id ||
            session('work_performance_evaluation_period_id')
            != $evaluationPeriod->evaluation_period_id
        ) {

            $service->startEvaluation();
        }

        /*
        |--------------------------------------------------------------------------
        | Get Current User
        |--------------------------------------------------------------------------
        */

        $currentUser = $service->getCurrentUser();

        if (!$currentUser) {
            abort(
                404,
                'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Evaluation Information
        |--------------------------------------------------------------------------
        */

        $currentUserNumber = $service->getCurrentUserNumber();

        $totalUsers = $service->getTotalUsers();

        $users = $service->getEligibleUsers();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'evaluations.work-performance.create',
            compact(
                'currentUser',
                'currentUserNumber',
                'totalUsers',
                'users',
                'evaluationPeriod'
            )
        );
    }



    /**
     * Display work performance evaluation preview.
     */
    public function preview()
    {
        $user = auth()->user();
        if ($user->role !== 'office_admin') {
            abort(403);
        }
        return view('evaluations.work-performance.preview');
    }

    /**
     * Store work performance evaluation.
     */
    public function submit(Request $request)
    {
        // return response()->json([
        //     'success' => true,
        //     'debug' => $request->all(),
        // ]);

        $user = auth()->user();

        // Only office Admin
        if ($user->role !== 'office_admin') {
            abort(403);
        }


        // -------------------------------------------------
        // Validate Request
        // -------------------------------------------------

        $request->validate([
            'users' => ['required', 'array'],
            'users.*.user_id' => ['required', 'integer'],
            'users.*.answers' => ['required', 'array'],
        ]);

        // -------------------------------------------------
        // Get Evaluation Period From Session
        // -------------------------------------------------

        $evaluationPeriodId = session()->get('work_performance_evaluation_period_id');
        if (!$evaluationPeriodId) {
            return response()->json([
                'success' => false,
                'message' =>
                    'មិនមានវគ្គវាយតម្លៃសម្រាប់ការវាយតម្លៃនេះទេ។'
            ], 422);
        }
        // Get Evaluation Period
        $evaluationPeriod = EvaluationPeriod::query()->where('evaluation_period_id', $evaluationPeriodId)
            ->where('status', 'open')
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->first();
        if (!$evaluationPeriod) {
            return response()->json([
                'success' => false,
                'message' =>
                    'វគ្គវាយតម្លៃនេះមិនទាន់បើក ឬបានបិទរួចហើយ។'
            ], 422);
        }

        // Save Everything Inside Transaction
        DB::beginTransaction();
        try {
            foreach ($request->users as $userData) {
                $evaluateeId = $userData['user_id'];
                // -------------------------------------------------
                // Security Check
                // -------------------------------------------------
                $evaluatee = User::query()
                    ->where('user_id', $evaluateeId)
                    ->where('department_id', $user->department_id)
                    ->where('office_id', $user->office_id)
                    ->where('status', 'active')
                    ->where('is_leader', false)
                    ->whereHas('evaluationPeriodUsers', function ($query) use ($evaluationPeriod) {
                        $query->where(
                            'evaluation_period_id',
                            $evaluationPeriod->evaluation_period_id
                        );
                    })
                    ->first();
                if (!$evaluatee) {
                    throw new \Exception(
                        'មន្ត្រីមិនត្រឹមត្រូវ។'
                    );
                }
                // Prevent Duplicate Evaluation
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
                // Create Evaluation
                $evaluation = Evaluation::create([
                    'evaluation_period_id' => $evaluationPeriod->evaluation_period_id,
                    'evaluator_id' => $user->user_id,
                    'evaluatee_id' => $evaluateeId,
                    'evaluation_type' => 'work_performance',
                    'evaluation_status' => 'submitted',
                    'submitted_at' => now(),
                    'created_by' => $user->user_id,
                    'updated_by' => $user->user_id,
                ]);

                // Get Activities
                $performances = $userData['answers']['performances'] ?? [];
                // Remove Completely Empty Rows
                $validPerformances = [];
                foreach ($performances as $performance) {
                    $activity = trim(
                        $performance['activity'] ?? ''
                    );
                    $indicator = trim(
                        $performance['indicator'] ?? ''
                    );
                    $achievement = (float) (
                        $performance['achievement_percent'] ?? 0
                    );
                    // Ignore completely empty rows
                    if (
                        $activity === '' &&
                        $indicator === '' &&
                        $achievement === 0
                    ) {
                        continue;
                    }
                    // Keep achievement between 0 - 100
                    $achievement = max(
                        0,
                        min(100, $achievement)
                    );
                    $validPerformances[] = [
                        'activity' => $activity,
                        'indicator' => $indicator,
                        'achievement_percent' => $achievement,
                    ];
                }
                // -------------------------------------------------
                // Calculate Equal Weight
                // -------------------------------------------------
                $numberOfPerformances = count($validPerformances);
                $weight = $numberOfPerformances > 0 ? 100 / $numberOfPerformances : 0;
                // Save Activities
                foreach ($validPerformances as $performance) {
                    $achievement = $performance['achievement_percent'];
                    // Each activity gets equal weight
                    $score = round(($achievement * $weight) / 100, 2);
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
            // Clear Temporary Session
            session()->forget([
                'work_performance_user_ids',
                'work_performance_current_index',
                'work_performance_evaluation_period_id',
            ]);

            return response()->json([
                'success' => true,
                'message' =>
                    'ការវាយតម្លៃត្រូវបានបញ្ជូនដោយជោគជ័យ។',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' =>
                    $e->getMessage(),
            ], 422);

        }
    }
    /**
     * Display work performance evaluation results.
     */
    public function view(
        WorkPerformanceEvaluationService $service,
        ?int $office = null
    ) {
        $user = auth()->user();
        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }
        // Get Open Evaluation Period
        $evaluationPeriod = $service->getOpenEvaluationPeriod();
        if (!$evaluationPeriod) {
            abort(404, 'បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃដែលកំពុងបើកទេ។');
        }

        // Initialize Office
        $officeModel = null;
        // Get Users
        if ($office) {
            // Office
            $officeModel = Office::query()
                ->where('office_id', $office)
                ->where('department_id', $user->department_id)->firstOrFail();
            $department = $officeModel->department;
            $users =
                $officeModel->users()
                    ->where('status', 'active')
                    ->where('is_leader', false)
                    ->orderBy('name_kh')
                    ->get();
        } else {
            // Users without Office
            $department = Department::query()
                ->where('department_id', $user->department_id)
                ->firstOrFail();
            $users =
                $department->users()
                    ->whereNull('office_id')
                    ->where('status', 'active')
                    ->where('is_leader', false)
                    ->orderBy('name_kh')
                    ->get();
        }
        // Get Submitted Evaluations
        $evaluations = Evaluation::query()
            ->with([
                'evaluatee',
                'workPerformance'
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
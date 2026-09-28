<?php

namespace App\Services;

use App\Models\EvaluationDataEntryAssignment;
use App\Models\Office;
use App\Models\Evaluation;
use App\Models\EvaluationAttendance;
use App\Models\EvaluationPeriod;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AttendanceEvaluationService
{
    /**
     * Get the data-entry assignment for the logged-in user
     * in the given evaluation period.
     */
    public function getCurrentAssignment(
        ?EvaluationPeriod $evaluationPeriod = null
    ): ?EvaluationDataEntryAssignment {
        $evaluationPeriod ??= $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod || !auth()->check()) {
            return null;
        }

        return EvaluationDataEntryAssignment::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'user_id',
                auth()->id()
            )
            ->first();
    }

    /**
     * Get users this data-entry user is responsible for.
     *
     * OFFICE assignment:
     * - Same department
     * - Same assigned office
     * - Active users of any role
     * - Must be a participant in this evaluation period
     *
     * DEPARTMENT assignment:
     * - Same department
     * - office_id IS NULL
     * - Active users of any role
     * - Excludes department_admin
     * - Must be a participant in this evaluation period
     */
    public function getEligibleUsers(
        ?EvaluationPeriod $evaluationPeriod = null
    ) {
        $evaluationPeriod ??= $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod || !auth()->check()) {
            return collect();
        }

        $assignment = $this->getCurrentAssignment($evaluationPeriod);

        if (!$assignment) {
            return collect();
        }

        $query = User::query()
            ->where('status', 'active')
            ->whereExists(function ($subQuery) use ($evaluationPeriod) {
                $subQuery
                    ->selectRaw('1')
                    ->from('evaluation_period_users as epu')
                    ->whereColumn('epu.user_id', 'users.user_id')
                    ->where(
                        'epu.evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    );
            });

        if ($assignment->scope === 'office') {
            if (!$assignment->department_id || !$assignment->office_id) {
                return collect();
            }

            return $query
                ->where('department_id', $assignment->department_id)
                ->where('office_id', $assignment->office_id)
                ->orderBy('name_kh')
                ->get();
        }

        if ($assignment->scope === 'department') {
            return $query
                ->where('department_id', $assignment->department_id)
                ->whereNull('office_id')
                ->where(function ($query) {
                    $query->whereNull('role')
                        ->orWhere('role', '<>', 'department_admin');
                })
                ->orderBy('name_kh')
                ->get();
        }

        return collect();
    }

    /**
     * Display attendance evaluation page.
     */
    public function index($user)
    {
        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return view(
                'evaluations.attendance.index',
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

        $assignment = $this->getCurrentAssignment($evaluationPeriod);

        if (!$assignment) {
            abort(
                403,
                'អ្នកមិនទាន់ត្រូវបានកំណត់ជាអ្នកបញ្ចូលទិន្នន័យសម្រាប់វគ្គវាយតម្លៃនេះទេ។'
            );
        }

        $users = $this->getEligibleUsers($evaluationPeriod);

        $office = null;

        if (
            $assignment->scope === 'office' &&
            $assignment->office_id
        ) {
            $office = $assignment->office;
        }

        $submittedUserIds = Evaluation::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where('evaluation_type', 'attendance')
            ->where('evaluation_status', 'submitted')
            ->whereIn('evaluatee_id', $users->pluck('user_id'))
            ->pluck('evaluatee_id')
            ->toArray();

        $allUsersSubmitted =
            $users->isNotEmpty() &&
            count($submittedUserIds) === $users->count();

        return view(
            'evaluations.attendance.index',
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
     * Get currently open evaluation period.
     */
    public function getOpenEvaluationPeriod()
    {
        return EvaluationPeriod::query()
            ->where('status', 'open')
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->latest('evaluation_period_id')
            ->first();
    }

    /**
     * Create attendance evaluation using the logged-in user's assignment.
     */
    public function create($user)
    {
        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            abort(
                404,
                'បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃដែលកំពុងបើកទេ។'
            );
        }

        $assignment = $this->getCurrentAssignment($evaluationPeriod);

        if (!$assignment) {
            abort(
                403,
                'អ្នកមិនទាន់ត្រូវបានកំណត់ជាអ្នកបញ្ចូលទិន្នន័យសម្រាប់វគ្គវាយតម្លៃនេះទេ។'
            );
        }

        $users = $this->getEligibleUsers($evaluationPeriod);

        if ($users->isEmpty()) {
            abort(
                404,
                'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ។'
            );
        }

        $officeModel = null;

        if ($assignment->scope === 'office' && $assignment->office_id) {
            $officeModel = $assignment->office;
        }

        session([
            'attendance_user_ids' =>
                $users->pluck('user_id')->values()->toArray(),

            'attendance_current_index' => 0,

            'attendance_evaluation_period_id' =>
                $evaluationPeriod->evaluation_period_id,

            'attendance_assignment_id' =>
                $assignment->evaluation_data_entry_assignment_id,

            'attendance_office_id' =>
                $assignment->office_id,
        ]);

        return view(
            'evaluations.attendance.create',
            compact(
                'users',
                'officeModel',
                'evaluationPeriod',
                'assignment'
            )
        );
    }

    /**
     * Display attendance evaluation preview.
     */
    public function preview($user)
    {
        $evaluationPeriodId = session()->get(
            'attendance_evaluation_period_id'
        );

        if (!$evaluationPeriodId) {
            abort(404, 'មិនមានព័ត៌មានវគ្គវាយតម្លៃទេ។');
        }

        $evaluationPeriod = EvaluationPeriod::query()
            ->where('evaluation_period_id', $evaluationPeriodId)
            ->where('status', 'open')
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->first();

        if (!$evaluationPeriod) {
            abort(404, 'វគ្គវាយតម្លៃនេះមិនទាន់បើក ឬបានបិទរួចហើយ។');
        }

        $assignment = $this->getCurrentAssignment($evaluationPeriod);

        if (!$assignment) {
            abort(403);
        }

        $sessionAssignmentId = session()->get('attendance_assignment_id');

        if (
            (int) $sessionAssignmentId !==
            (int) $assignment->evaluation_data_entry_assignment_id
        ) {
            abort(403);
        }

        $userIds = session()->get('attendance_user_ids', []);

        if (empty($userIds)) {
            abort(404, 'មិនមានមន្ត្រីសម្រាប់បង្ហាញការវាយតម្លៃទេ។');
        }

        $users = $this->getEligibleUsers($evaluationPeriod)
            ->whereIn('user_id', $userIds)
            ->values();

        return view(
            'evaluations.attendance.preview',
            compact(
                'users',
                'evaluationPeriod',
                'assignment'
            )
        );
    }

    /**
     * Calculate Attendance Percent
     */
    private function calculateAttendancePercent(array $data): float
    {
        // -------------------------------------------------
        // Approved Leave
        // 1 day = 8 hours
        // Approved leave deducts 50%
        // -------------------------------------------------

        $approvedHours = ($data['approved_leave_days'] ?? 0) * 8 * 0.5;
        // -------------------------------------------------
        // Unapproved Leave
        // 1 day = 8 hours
        // Full deduction
        // -------------------------------------------------
        $unapprovedHours = ($data['unapproved_leave_days'] ?? 0) * 8;

        // Late / Leave Early
        $lateHours = (float) ($data['late_hours'] ?? 0);
        $leaveEarlyHours = (float) ($data['leave_early_hours'] ?? 0);

        // Total Deduction Hours
        $deductionHours =
            $approvedHours +
            $unapprovedHours +
            $lateHours +
            $leaveEarlyHours;
        // -------------------------------------------------
        // Convert Hours to Percentage
        // 1% = 1.76 hours
        // -------------------------------------------------
        $deductionPercent = $deductionHours / 1.76;
        // Attendance Percent
        $attendancePercent = 100 - $deductionPercent;
        return max(0, round($attendancePercent, 2));
    }

    /**
     * Calculate Attendance Score
     *
     * < 80%       = 0
     * 80% - <90%  = 5
     * 90% - <95%  = 10
     * 95% - <100% = 15
     * 100%        = 20
     */
    private function calculateAttendanceScore(
        float $attendancePercent
    ): float {
        if ($attendancePercent < 80) {
            return 0;
        }
        if ($attendancePercent < 90) {
            return 5;
        }
        if ($attendancePercent < 95) {
            return 10;
        }
        if ($attendancePercent < 100) {
            return 15;
        }
        return 20;
    }

    public function submit($user, array $data)
    {
        // =====================================================
        // Evaluation Period
        // =====================================================

        $evaluationPeriodId = session()->get(
            'attendance_evaluation_period_id'
        );

        if (!$evaluationPeriodId) {
            return response()->json([
                'success' => false,
                'message' => 'មិនមានព័ត៌មានវគ្គវាយតម្លៃទេ។'
            ], 404);
        }

        $evaluationPeriod = EvaluationPeriod::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriodId
            )
            ->where('status', 'open')
            ->whereDate(
                'start_date',
                '<=',
                now()->toDateString()
            )
            ->whereDate(
                'end_date',
                '>=',
                now()->toDateString()
            )
            ->first();

        if (!$evaluationPeriod) {
            return response()->json([
                'success' => false,
                'message' =>
                    'វគ្គវាយតម្លៃនេះមិនទាន់បើក ឬបានបិទរួចហើយ។'
            ], 404);
        }

        $assignment = $this->getCurrentAssignment($evaluationPeriod);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' =>
                    'អ្នកមិនទាន់ត្រូវបានកំណត់ជាអ្នកបញ្ចូលទិន្នន័យសម្រាប់វគ្គវាយតម្លៃនេះទេ។'
            ], 403);
        }

        $sessionAssignmentId = session()->get('attendance_assignment_id');

        if (
            (int) $sessionAssignmentId !==
            (int) $assignment->evaluation_data_entry_assignment_id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'សម័យវាយតម្លៃនេះមិនត្រូវនឹងការកំណត់អ្នកបញ្ចូលទិន្នន័យបច្ចុប្បន្នទេ។'
            ], 403);
        }

        // =====================================================
        // Attendance Data
        // =====================================================

        $attendanceData = $data['attendance'] ?? [];

        if (empty($attendanceData)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'មិនមានទិន្នន័យវត្តមានសម្រាប់បញ្ជូនទេ។'
            ], 422);
        }

        // =====================================================
        // Get User IDs From Session
        // =====================================================

        $userIds = session()->get(
            'attendance_user_ids',
            []
        );

        if (empty($userIds)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ។'
            ], 422);
        }

        // =====================================================
        // Validate All Users Exist In Submitted Data
        // =====================================================

        foreach ($userIds as $userId) {

            if (!isset($attendanceData[$userId])) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'សូមបំពេញការវាយតម្លៃមន្ត្រីទាំងអស់ជាមុនសិន។'
                ], 422);
            }
        }

        // =====================================================
        // Database Transaction
        // =====================================================

        DB::beginTransaction();

        try {

            foreach ($userIds as $userId) {

                $attendance = $attendanceData[$userId];

                // =================================================
                // Security Check
                // =================================================
                // The evaluatee must belong to the current assignment.

                $evaluatee = $this->getEligibleUsers($evaluationPeriod)
                    ->firstWhere('user_id', (int) $userId);

                if (!$evaluatee) {
                    throw new \Exception(
                        'មន្ត្រីមិនត្រឹមត្រូវ ឬមិនស្ថិតក្នុងវិសាលភាពដែលអ្នកទទួលខុសត្រូវ។'
                    );
                }

                // =================================================
                // Prevent Duplicate Evaluation
                // =================================================

                $existingEvaluation = Evaluation::query()
                    ->where(
                        'evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    )
                    ->where(
                        'evaluatee_id',
                        $userId
                    )
                    ->where(
                        'evaluation_type',
                        'attendance'
                    )
                    ->first();

                if ($existingEvaluation) {

                    throw new \Exception(
                        "មន្ត្រី {$evaluatee->name_kh} បានវាយតម្លៃវត្តមានរួចហើយ។"
                    );
                }

                // =================================================
                // Attendance Values
                // =================================================

                $approvedLeaveDays =
                    (float) ($attendance['approved_leave_days'] ?? 0);

                $unapprovedLeaveDays =
                    (float) ($attendance['unapproved_leave_days'] ?? 0);

                $lateHours =
                    (float) ($attendance['late_hours'] ?? 0);

                $leaveEarlyHours =
                    (float) ($attendance['leave_early_hours'] ?? 0);

                // =================================================
                // Overtime Hours
                // IMPORTANT:
                // This is stored only.
                // It does NOT affect attendance calculation.
                // =================================================

                $overtimeHours =
                    (float) ($attendance['overtime_hours'] ?? 0);

                $overtimeHours = max(0, $overtimeHours);

                // =================================================
                // Perfect Attendance
                // =================================================

                $perfectAttendance =
                    (bool) ($attendance['perfectAttendance'] ?? false);

                // =================================================
                // Calculate Attendance Percent
                // =================================================

                if ($perfectAttendance) {

                    $attendancePercent = 100;

                } else {

                    $attendancePercent =
                        $this->calculateAttendancePercent([
                            'approved_leave_days' =>
                                $approvedLeaveDays,

                            'unapproved_leave_days' =>
                                $unapprovedLeaveDays,

                            'late_hours' =>
                                $lateHours,

                            'leave_early_hours' =>
                                $leaveEarlyHours,
                        ]);
                }

                // =================================================
                // Calculate Attendance Score
                // =================================================

                $attendanceScore =
                    $this->calculateAttendanceScore(
                        $attendancePercent
                    );

                // =================================================
                // Create Evaluation
                // =================================================

                $evaluation = Evaluation::create([
                    'evaluation_period_id' =>
                        $evaluationPeriod->evaluation_period_id,

                    'evaluator_id' =>
                        $user->user_id,

                    'evaluatee_id' =>
                        $userId,

                    'evaluation_type' =>
                        'attendance',

                    'evaluation_status' =>
                        'submitted',

                    'submitted_at' =>
                        now(),

                    'created_by' =>
                        $user->user_id,

                    'updated_by' =>
                        $user->user_id,
                ]);

                // =================================================
                // Create Attendance
                // =================================================

                EvaluationAttendance::create([
                    'evaluation_id' =>
                        $evaluation->evaluation_id,

                    'approved_leave_count' =>
                        $approvedLeaveDays,

                    'unapproved_leave_count' =>
                        $unapprovedLeaveDays,

                    'late_hours' =>
                        $lateHours,

                    'leave_early_hours' =>
                        $leaveEarlyHours,

                    'overtime_hours' =>
                        $overtimeHours,

                    'attendance_percent' =>
                        $attendancePercent,

                    'attendance_score' =>
                        $attendanceScore,
                ]);
            }

            // =====================================================
            // Commit
            // =====================================================

            DB::commit();

            // =====================================================
            // Clear Temporary Session
            // =====================================================

            session()->forget([
                'attendance_user_ids',
                'attendance_current_index',
                'attendance_evaluation_period_id',
                'attendance_assignment_id',
                'attendance_office_id',
            ]);

            return response()->json([
                'success' => true,
                'message' =>
                    'ការវាយតម្លៃវត្តមានត្រូវបានរក្សាទុកដោយជោគជ័យ។'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Attendance evaluation submit error',
                [
                    'user_id' => $user->user_id,
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    $e->getMessage(),
            ], 422);
        }
    }
    /**
     * Display submitted attendance evaluations.
     */
    public function view($user, Request $request)
    {
        // Only Department Admin
        if ($user->role !== 'office_admin') {
            abort(403);
        }

        // -------------------------------------------------
        // Evaluation Period
        // -------------------------------------------------

        $evaluationPeriodId =
            session()->get(
                'attendance_evaluation_period_id'
            );

        if (!$evaluationPeriodId) {
            abort(
                404,
                'មិនមានព័ត៌មានវគ្គវាយតម្លៃទេ។'
            );
        }

        $evaluationPeriod =
            EvaluationPeriod::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriodId
                )
                ->first();

        if (!$evaluationPeriod) {
            abort(
                404,
                'មិនមានវគ្គវាយតម្លៃនេះទេ។'
            );
        }

        // -------------------------------------------------
        // Office / Department
        // -------------------------------------------------

        $officeId =
            $request->query('office');

        $departmentId =
            $request->query('department');


        // -------------------------------------------------
        // Get Users
        // -------------------------------------------------

        $usersQuery =
            User::query()
                ->where(
                    'department_id',
                    $user->department_id
                )
                ->where(
                    'status',
                    'active'
                )
                ->where(
                    'is_leader',
                    false
                )
                ->whereHas('evaluationPeriodUsers', function ($query) use ($evaluationPeriod) {
                    $query->where(
                        'evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    );
                });


        // -------------------------------------------------
        // Office
        // -------------------------------------------------

        if ($officeId) {

            $office =
                Office::query()
                    ->where(
                        'office_id',
                        $officeId
                    )
                    ->where(
                        'department_id',
                        $user->department_id
                    )
                    ->firstOrFail();

            $usersQuery->where(
                'office_id',
                $office->office_id
            );

        } else {

            // -------------------------------------------------
            // Department Without Office
            // -------------------------------------------------

            $office = null;

            $usersQuery->whereNull(
                'office_id'
            );
        }


        $users =
            $usersQuery
                ->orderBy('name_kh')
                ->get();


        // -------------------------------------------------
        // Get Submitted Attendance Evaluations
        // -------------------------------------------------

        $evaluations =
            Evaluation::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->where(
                    'evaluation_type',
                    'attendance'
                )
                ->where(
                    'evaluation_status',
                    'submitted'
                )
                ->whereIn(
                    'evaluatee_id',
                    $users->pluck('user_id')
                )
                ->with('attendance')
                ->get()
                ->keyBy('evaluatee_id');


        // -------------------------------------------------
        // Return View
        // -------------------------------------------------

        return view(
            'evaluations.attendance.view',
            compact(
                'users',
                'evaluations',
                'evaluationPeriod',
                'office'
            )
        );
    }
}
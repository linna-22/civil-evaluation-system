<?php

namespace App\Services;

use App\Models\Department;
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
     * Get active employees in the current office who belong to the
     * evaluation-period participant snapshot.
     */
    private function getEligibleUsers($user, EvaluationPeriod $evaluationPeriod)
    {
        return User::query()
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
            ->orderBy('name_kh')
            ->get();
    }

    /**
     * Display attendance evaluation offices.
     */
    public function index($user)
    {
        // =====================================================
        // Only Office Admin
        // =====================================================

        if ($user->role !== 'office_admin') {
            abort(403);
        }

        // =====================================================
        // Office Admin must have an office
        // =====================================================

        if (!$user->office_id) {
            abort(404, 'មិនមានការិយាល័យសម្រាប់អ្នកប្រើប្រាស់នេះទេ។');
        }

        // =====================================================
        // Get Open Evaluation Period
        // =====================================================

        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        // =====================================================
        // No Open Evaluation Period
        // =====================================================

        if (!$evaluationPeriod) {
            return view(
                'evaluations.attendance.index',
                [
                    'office' => null,
                    'users' => collect(),
                    'evaluationPeriod' => null,
                ]
            );
        }

        // =====================================================
        // Get Office Admin's Own Office
        // =====================================================

        $office = Office::query()
            ->where('office_id', $user->office_id)
            ->where('department_id', $user->department_id)
            ->first();

        if (!$office) {
            abort(404, 'មិនមានការិយាល័យសម្រាប់អ្នកប្រើប្រាស់នេះទេ។');
        }

        // =====================================================
        // Get Eligible Users in Own Office
        // =====================================================

        $users = $this->getEligibleUsers(
            $user,
            $evaluationPeriod
        );

        // =====================================================
        // Get Submitted User IDs
        // =====================================================

        $submittedUserIds = Evaluation::query()
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
            ->pluck('evaluatee_id')
            ->toArray();

        // =====================================================
        // Check Whether All Users Are Submitted
        // =====================================================

        $allUsersSubmitted =
            $users->isNotEmpty() &&
            count($submittedUserIds) === $users->count();

        // =====================================================
        // Return View
        // =====================================================

        return view(
            'evaluations.attendance.index',
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
     * Get currently open evaluation period.
     */
    public function getOpenEvaluationPeriod()
    {
        return EvaluationPeriod::query()
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
    }

    /**
     * Create attendance evaluation.
     */
    public function create($user)
    {
        // =====================================================
        // Only Office Admin
        // =====================================================

        if ($user->role !== 'office_admin') {
            abort(403);
        }

        // =====================================================
        // Office Admin must have an office
        // =====================================================

        if (!$user->office_id) {
            abort(
                404,
                'មិនមានការិយាល័យសម្រាប់អ្នកប្រើប្រាស់នេះទេ។'
            );
        }

        // =====================================================
        // Get Open Evaluation Period
        // =====================================================

        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            abort(
                404,
                'បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃដែលកំពុងបើកទេ។'
            );
        }

        // =====================================================
        // Get Office Admin's Own Office
        // =====================================================

        $officeModel = Office::query()
            ->where('office_id', $user->office_id)
            ->where('department_id', $user->department_id)
            ->first();

        if (!$officeModel) {
            abort(
                404,
                'មិនមានការិយាល័យសម្រាប់អ្នកប្រើប្រាស់នេះទេ។'
            );
        }

        // =====================================================
        // Get All Eligible Users In Own Office
        // =====================================================

        $users = $this->getEligibleUsers(
            $user,
            $evaluationPeriod
        );

        // =====================================================
        // No Users
        // =====================================================

        if ($users->isEmpty()) {
            abort(
                404,
                'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ។'
            );
        }

        // =====================================================
        // Store Temporary Evaluation Information
        // =====================================================

        session([
            'attendance_user_ids' =>
                $users->pluck('user_id')->values()->toArray(),

            'attendance_current_index' => 0,

            'attendance_evaluation_period_id' =>
                $evaluationPeriod->evaluation_period_id,

            'attendance_office_id' =>
                $officeModel->office_id,
        ]);

        // =====================================================
        // Return Create Page
        // =====================================================

        return view(
            'evaluations.attendance.create',
            compact(
                'users',
                'officeModel',
                'evaluationPeriod'
            )
        );
    }
    /**
     * Display attendance evaluation preview.
     */
    public function preview($user)
    {
        // Only Department Admin

        if ($user->role !== 'office_admin') {
            abort(403);
        }
        // Get Evaluation Period From Session
        $evaluationPeriodId =
            session()->get(
                'attendance_evaluation_period_id'
            );
        if (!$evaluationPeriodId) {
            abort(404, 'មិនមានព័ត៌មានវគ្គវាយតម្លៃទេ។');
        }
        // Get Evaluation Period
        $evaluationPeriod = EvaluationPeriod::query()
            ->where('evaluation_period_id', $evaluationPeriodId)
            ->where('status', 'open')
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->first();
        if (!$evaluationPeriod) {
            abort(404, 'វគ្គវាយតម្លៃនេះមិនទាន់បើក ឬបានបិទរួចហើយ។');
        }
        // Get User IDs From Session
        $userIds = session()->get('attendance_user_ids', []);
        if (empty($userIds)) {
            abort(404, 'មិនមានមន្ត្រីសម្រាប់បង្ហាញការវាយតម្លៃទេ។');
        }


        // Get Users    
        $users = User::query()
            ->whereIn('user_id', $userIds)
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
            ->orderBy('name_kh')
            ->get();
        // Return Preview
        return view(
            'evaluations.attendance.preview',
            compact(
                'users',
                'evaluationPeriod'
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
        // Only Office Admin
        // =====================================================

        if ($user->role !== 'office_admin') {
            abort(403);
        }

        // =====================================================
        // Office Admin must have an office
        // =====================================================

        if (!$user->office_id) {
            return response()->json([
                'success' => false,
                'message' => 'មិនមានការិយាល័យសម្រាប់អ្នកប្រើប្រាស់នេះទេ។'
            ], 422);
        }

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

                $evaluatee = User::query()
                    ->where('user_id', $userId)
                    ->where(
                        'department_id',
                        $user->department_id
                    )
                    ->where(
                        'office_id',
                        $user->office_id
                    )
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
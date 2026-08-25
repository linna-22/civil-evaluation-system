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
     * Display attendance evaluation offices.
     */
    public function index($user)
    {
        // =====================================================
        // Only Department Admin
        // =====================================================

        if ($user->role !== 'department_admin') {
            abort(403);
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
                    'offices' => collect(),
                    'evaluationPeriod' => null,
                ]
            );

        }


        // =====================================================
        // Get Offices + Active User Count
        // =====================================================

        $offices = Office::query()

            ->where(
                'department_id',
                $user->department_id
            )

            ->withCount([
                'users' => function ($query) {

                    $query
                        ->where('status', 'active')
                        ->where('is_leader', false);

                }
            ])

            ->orderBy('office_name_kh')

            ->get();


        // =====================================================
        // Department Has No Offices
        // =====================================================

        if ($offices->isEmpty()) {

            return redirect()->route(
                'evaluations.attendance.department.users',
                $user->department_id
            );

        }


        // =====================================================
        // Get All Submitted Evaluatee IDs
        // =====================================================

        $submittedUserIds = Evaluation::query()

            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'evaluation_status',
                'submitted'
            )
            ->where('evaluation_type', 'attendance')
            ->whereHas('evaluatee', function ($query) use ($user) {
                $query
                    ->where(
                        'department_id',
                        $user->department_id
                    )
                    ->where('status', 'active')
                    ->where('is_leader', false);
            })
            ->pluck('evaluatee_id');
        // =====================================================
        // Get Submitted Users Per Office
        // =====================================================

        $submittedPerOffice = User::query()
            ->whereIn('user_id', $submittedUserIds)
            ->where(
                'department_id',
                $user->department_id
            )
            ->where('status', 'active')
            ->where('is_leader', false)
            ->selectRaw('office_id, COUNT(*) as submitted_count')
            ->groupBy('office_id')
            ->pluck('submitted_count', 'office_id');


        // =====================================================
        // Attach Status
        // =====================================================

        $offices->each(function ($office) use ($submittedPerOffice) {

            $submittedCount =
                (int) ($submittedPerOffice[$office->office_id] ?? 0);

            $totalCount =
                (int) $office->users_count;


            $office->submitted_users_count =
                $submittedCount;

            $office->evaluation_completed =
                $totalCount > 0 &&
                $submittedCount === $totalCount;

        });


        // =====================================================
        // Return View
        // =====================================================

        return view(
            'evaluations.attendance.index',
            compact(
                'offices',
                'evaluationPeriod'
            )
        );
    }
    /**
     * Display users without office.
     */
    public function usersByDepartment($user, Department $department)
    {
        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }

        // Department must belong to logged-in admin
        if ($department->department_id !== $user->department_id) {
            abort(403);
        }

        // Get Users Without Office
        $users = $department->users()
            ->whereNull('office_id')
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();

        // Get Current Attendance Evaluation Period
        $evaluationPeriodId = session()->get(
            'attendance_evaluation_period_id'
        );

        // Get Submitted Attendance Evaluations
        $evaluatedUserIds = [];

        if ($evaluationPeriodId) {

            $evaluatedUserIds = Evaluation::query()
                ->where('evaluation_period_id', $evaluationPeriodId)
                ->where('evaluation_type', 'attendance')
                ->where('evaluation_status', 'submitted')
                ->whereIn(
                    'evaluatee_id',
                    $users->pluck('user_id')
                )
                ->pluck('evaluatee_id')
                ->toArray();
        }

        // No Office
        $office = null;

        return view(
            'evaluations.attendance.users',
            compact(
                'department',
                'office',
                'users',
                'evaluatedUserIds'
            )
        );
    }


    /**
     * Display users under an office.
     */
    public function usersByOffice($user, Office $office)
    {
        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }

        // Office must belong to admin's department
        if ($office->department_id !== $user->department_id) {
            abort(403);
        }

        // Get Department
        $department = $office->department;

        // Get Users
        $users = $office->users()
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();

        // Get Current Attendance Evaluation Period
        $evaluationPeriodId = session()->get(
            'attendance_evaluation_period_id'
        );

        // Get Submitted Attendance Evaluations
        $evaluatedUserIds = [];

        if ($evaluationPeriodId) {

            $evaluatedUserIds = Evaluation::query()
                ->where('evaluation_period_id', $evaluationPeriodId)
                ->where('evaluation_type', 'attendance')
                ->where('evaluation_status', 'submitted')
                ->whereIn(
                    'evaluatee_id',
                    $users->pluck('user_id')
                )
                ->pluck('evaluatee_id')
                ->toArray();
        }

        return view(
            'evaluations.attendance.users',
            compact(
                'department',
                'office',
                'users',
                'evaluatedUserIds'
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
    public function create($user, ?int $office = null)
    {
        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }
        // Get Open Evaluation Period

        $evaluationPeriod = $this->getOpenEvaluationPeriod();
        if (!$evaluationPeriod) {
            abort(
                404,
                'បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃដែលកំពុងបើកទេ។'
            );
        }
        // Get Users
        if ($office) {
            // Office
            $officeModel = Office::query()
                ->where('office_id', $office)
                ->where(
                    'department_id',
                    $user->department_id
                )
                ->first();
            if (!$officeModel) {
                abort(403);
            }
            $department = $officeModel->department;
            $users = $officeModel->users()
                ->where('status', 'active')
                ->where('is_leader', false)
                ->orderBy('name_kh')
                ->get();
        } else {

            // Users Without Office
            $department = Department::query()
                ->where('department_id', $user->department_id)
                ->firstOrFail();
            $users = $department->users()
                ->whereNull('office_id')
                ->where('status', 'active')
                ->where('is_leader', false)
                ->orderBy('name_kh')
                ->get();
            $officeModel = null;
        }

        // No Users
        if ($users->isEmpty()) {
            abort(404, 'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ។');
        }

        // Store Temporary Evaluation Information
        session([
            'attendance_user_ids' =>
                $users->pluck('user_id')->values()->toArray(),
            'attendance_current_index' => 0,
            'attendance_evaluation_period_id' => $evaluationPeriod->evaluation_period_id,
        ]);
        // Return Create Page
        return view(
            'evaluations.attendance.create',
            compact(
                'users',
                'department',
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

        if ($user->role !== 'department_admin') {
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
            ->where('status', 'active')
            ->where('is_leader', false)
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
        // Only Department Admin
        if ($user->role !== 'department_admin') {
            abort(403);
        }
        // Evaluation Period
        $evaluationPeriodId =
            session()->get(
                'attendance_evaluation_period_id'
            );
        if (!$evaluationPeriodId) {
            return response()->json([
                'success' => false,
                'message' =>
                    'មិនមានព័ត៌មានវគ្គវាយតម្លៃទេ។'
            ], 404);
        }
        $evaluationPeriod = EvaluationPeriod::query()
            ->where('evaluation_period_id', $evaluationPeriodId)
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
        // Attendance Data
        $attendanceData = $data['attendance'] ?? [];
        if (empty($attendanceData)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'មិនមានទិន្នន័យវត្តមានសម្រាប់បញ្ជូនទេ។'
            ], 422);
        }
        // Get User IDs From Session
        $userIds = session()->get('attendance_user_ids', []);
        if (empty($userIds)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ។'
            ], 422);
        }
        // Validate All Users
        foreach ($userIds as $userId) {
            if (!isset($attendanceData[$userId])) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'សូមបំពេញការវាយតម្លៃមន្ត្រីទាំងអស់ជាមុនសិន។'
                ], 422);
            }
        }
        // Database Transaction
        DB::beginTransaction();
        try {
            foreach ($userIds as $userId) {
                $data = $attendanceData[$userId];
                // Attendance Values
                $approvedLeaveDays = (float) ($data['approved_leave_days'] ?? 0);
                $unapprovedLeaveDays = (float) ($data['unapproved_leave_days'] ?? 0);
                $lateHours = (float) ($data['late_hours'] ?? 0);
                $leaveEarlyHours = (float) ($data['leave_early_hours'] ?? 0);
                // Perfect Attendance
                $perfectAttendance = (bool) ($data['perfectAttendance'] ?? false);
                // Calculate Attendance Percent
                if ($perfectAttendance) {
                    $attendancePercent = 100;
                } else {
                    $attendancePercent = $this->calculateAttendancePercent([
                        'approved_leave_days' => $approvedLeaveDays,
                        'unapproved_leave_days' => $unapprovedLeaveDays,
                        'late_hours' => $lateHours,
                        'leave_early_hours' => $leaveEarlyHours,
                    ]);
                }
                // Calculate Attendance Score
                $attendanceScore = $this->calculateAttendanceScore($attendancePercent);
                // Create Evaluation
                $evaluation = Evaluation::create([
                    'evaluation_period_id' => $evaluationPeriod->evaluation_period_id,
                    'evaluator_id' => $user->user_id,
                    'evaluatee_id' => $userId,
                    'evaluation_type' => 'attendance',
                    'evaluation_status' => 'submitted',
                    'submitted_at' => now(),
                    'created_by' => $user->user_id,
                    'updated_by' => $user->user_id,
                ]);
                // Create Attendance
                EvaluationAttendance::create([
                    'evaluation_id' => $evaluation->evaluation_id,
                    'approved_leave_count' => $approvedLeaveDays,
                    'unapproved_leave_count' => $unapprovedLeaveDays,
                    'late_hours' => $lateHours,
                    'leave_early_hours' => $leaveEarlyHours,
                    'attendance_percent' => $attendancePercent,
                    'attendance_score' => $attendanceScore,
                ]);
            }
            // Commit
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'ការវាយតម្លៃវត្តមានត្រូវបានរក្សាទុកដោយជោគជ័យ។'
            ]);
        } catch (\Throwable $e) {
            // Rollback
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
                    'មានបញ្ហាក្នុងការរក្សាទុកការវាយតម្លៃ។'
            ], 500);
        }
    }
    /**
     * Display submitted attendance evaluations.
     */
    public function view($user, Request $request)
    {
        // Only Department Admin
        if ($user->role !== 'department_admin') {
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
                );


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
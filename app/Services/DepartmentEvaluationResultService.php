<?php

namespace App\Services;

use App\Models\EvaluationPeriod;
use App\Models\Evaluation;
use App\Models\EvaluationSummary;
use App\Models\EvaluationWorkPerformance;
use App\Models\EvaluationAttendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DepartmentEvaluationResultService
{
    /**
     * Get all closed evaluation periods.
     */
    public function getClosedPeriods(): Collection
    {
        return EvaluationPeriod::query()
            ->where('status', 'closed')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
    }


    /**
     * Get evaluation results for users
     * in the Department Admin's department
     * for a specific evaluation period.
     */
    public function getDepartmentResults(
        User $admin,
        EvaluationPeriod $evaluationPeriod,
        Request $request
    ): LengthAwarePaginator {

        $results = EvaluationSummary::query()
            ->with([
                'evaluationPeriodUser.user',
                'evaluationPeriodUser.evaluationPeriod',
            ])
            ->whereHas(
                'evaluationPeriodUser',
                function ($query) use ($admin, $evaluationPeriod) {

                    $query
                        ->where(
                            'evaluation_period_id',
                            $evaluationPeriod->evaluation_period_id
                        )
                        ->whereHas(
                            'user',
                            function ($userQuery) use ($admin) {

                                $userQuery
                                    ->where(
                                        'department_id',
                                        $admin->department_id
                                    )
                                    ->whereNotIn('role', [
                                        'super_admin',
                                        'evaluation_admin',
                                        'department_admin',
                                    ]);
                            }
                        );
                }
            )

            // ==========================================================
            // Office Filter
            // ==========================================================
            ->when(
                $request->office_id,
                function ($query) use ($request) {

                    $query->whereHas(
                        'evaluationPeriodUser.user',
                        function ($userQuery) use ($request) {

                            $userQuery->where(
                                'office_id',
                                $request->office_id
                            );
                        }
                    );
                }
            )

            // ==========================================================
            // Search
            // ==========================================================
            ->when(
                $request->search,
                function ($query) use ($request) {

                    $search = $request->search;

                    $query->whereHas(
                        'evaluationPeriodUser.user',
                        function ($userQuery) use ($search) {

                            $userQuery->where(function ($q) use ($search) {

                                $q->where(
                                    'name_kh',
                                    'like',
                                    "%{$search}%"
                                )
                                    ->orWhere(
                                        'name_en',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'id_code',
                                        'like',
                                        "%{$search}%"
                                    );
                            });
                        }
                    );
                }
            )

            // ==========================================================
            // Highest score first
            // ==========================================================
            ->orderByDesc('total_score')

            // ==========================================================
            // Pagination
            // ==========================================================
            ->paginate(
                $request->input('per_page', 10)
            );


        // ==========================================================
        // Add Overtime Hours
        // ==========================================================

        $results->getCollection()->transform(
            function ($result) use ($evaluationPeriod) {

                $userId =
                    $result->evaluationPeriodUser?->user_id;


                // ------------------------------------------------------
                // No user
                // ------------------------------------------------------
    
                if (!$userId) {

                    $result->overtime_hours = 0;

                    return $result;
                }


                // ------------------------------------------------------
                // Find Attendance Evaluation
                // ------------------------------------------------------
    
                $attendanceEvaluation = Evaluation::query()
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


                // ------------------------------------------------------
                // No Attendance Evaluation
                // ------------------------------------------------------
    
                if (!$attendanceEvaluation) {

                    $result->overtime_hours = 0;

                    return $result;
                }


                // ------------------------------------------------------
                // Get Attendance Data
                // ------------------------------------------------------
    
                $attendance = EvaluationAttendance::query()
                    ->where(
                        'evaluation_id',
                        $attendanceEvaluation->evaluation_id
                    )
                    ->first();


                // ------------------------------------------------------
                // Attach Overtime Hours
                // ------------------------------------------------------
    
                $result->overtime_hours = (float) (
                    $attendance?->overtime_hours ?? 0
                );


                return $result;
            }
        );


        return $results;
    }

    /**
     * Get one user's evaluation result
     * inside the Department Admin's department.
     */
    public function getUserResult(
        User $departmentAdmin,
        EvaluationPeriod $evaluationPeriod,
        User $user
    ): ?EvaluationSummary {

        return EvaluationSummary::query()

            ->with([
                'evaluationPeriodUser.user',
                'evaluationPeriodUser.evaluationPeriod',
            ])

            ->whereHas(
                'evaluationPeriodUser',
                function ($query) use ($departmentAdmin, $evaluationPeriod, $user) {

                    $query
                        ->where(
                            'evaluation_period_id',
                            $evaluationPeriod->evaluation_period_id
                        )
                        ->where(
                            'user_id',
                            $user->user_id
                        )

                        ->whereHas(
                            'user',
                            function ($userQuery) use ($departmentAdmin) {

                                $userQuery
                                    ->where(
                                        'department_id',
                                        $departmentAdmin->department_id
                                    )

                                    ->whereNotIn('role', [
                                        'super_admin',
                                        'evaluation_admin',
                                        'department_admin',
                                    ]);

                                // ->where('is_leader', 0);
                
                            }
                        );

                }
            )->first();
    }
    /**
     * Get one employee's submitted Work Performance evaluation for editing.
     *
     * Editing is intentionally available only after the evaluation period
     * has been closed, and the employee must belong to the Department Admin's
     * department and the selected evaluation period.
     */
    public function getWorkPerformanceForEdit(
        User $departmentAdmin,
        EvaluationPeriod $evaluationPeriod,
        User $user
    ): ?Evaluation {

        if ($evaluationPeriod->status !== 'closed') {
            abort(403, 'ការកែប្រែអាចធ្វើបានតែបន្ទាប់ពីបិទវគ្គវាយតម្លៃប៉ុណ្ណោះ។');
        }

        if ($user->department_id !== $departmentAdmin->department_id) {
            abort(403, 'Unauthorized.');
        }

        if (
            in_array($user->role, [
                'super_admin',
                'evaluation_admin',
                'department_admin',
            ], true)
        ) {
            abort(403, 'Unauthorized.');
        }

        return Evaluation::query()
            ->where('evaluation_period_id', $evaluationPeriod->evaluation_period_id)
            ->where('evaluatee_id', $user->user_id)
            ->where('evaluation_type', 'work_performance')
            ->where('evaluation_status', 'submitted')
            ->with([
                'evaluatee',
                'workPerformance',
            ])
            ->first();
    }


    /**
     * Update an employee's Work Performance evaluation and recalculate the
     * employee's combined summary.
     */
    public function updateWorkPerformance(
        User $departmentAdmin,
        EvaluationPeriod $evaluationPeriod,
        User $user,
        array $performances
    ): void {

        $evaluation = $this->getWorkPerformanceForEdit(
            $departmentAdmin,
            $evaluationPeriod,
            $user
        );

        if (!$evaluation) {
            abort(404, 'Work Performance evaluation not found.');
        }

        DB::transaction(function () use ($evaluation, $evaluationPeriod, $performances, $departmentAdmin) {

            // Normalize the submitted rows and ignore completely empty rows.
            $validRows = [];

            foreach ($performances as $row) {
                $activity = trim((string) ($row['activity'] ?? ''));
                $indicator = trim((string) ($row['indicator'] ?? ''));
                $achievement = $row['achievement_percent'] ?? null;

                if (
                    $activity === '' &&
                    $indicator === '' &&
                    ($achievement === null || $achievement === '')
                ) {
                    continue;
                }

                $validRows[] = [
                    'id' => !empty($row['work_performance_id'])
                        ? (int) $row['work_performance_id']
                        : null,
                    'activity' => $activity,
                    'indicator' => $indicator,
                    'achievement_percent' => (float) $achievement,
                ];
            }

            // The original submission distributes 100% weight equally
            // across all entered activities. Keep exactly the same rule.
            $count = count($validRows);
            $weight = $count > 0 ? 100 / $count : 0;

            $existingRows = $evaluation->workPerformance
                ->keyBy('work_performance_id');

            $keptIds = [];

            foreach ($validRows as $row) {
                $score = round(
                    ($row['achievement_percent'] * $weight) / 100,
                    2
                );

                if ($row['id'] !== null) {
                    $workPerformance = $existingRows->get($row['id']);

                    // Never allow a row from another evaluation to be edited.
                    if (!$workPerformance) {
                        abort(403, 'Unauthorized.');
                    }

                    $workPerformance->update([
                        'activity' => $row['activity'],
                        'indicator' => $row['indicator'],
                        'achievement_percent' => $row['achievement_percent'],
                        'score' => $score,
                    ]);

                    $keptIds[] = $workPerformance->work_performance_id;
                } else {
                    $created = EvaluationWorkPerformance::create([
                        'evaluation_id' => $evaluation->evaluation_id,
                        'activity' => $row['activity'],
                        'indicator' => $row['indicator'],
                        'achievement_percent' => $row['achievement_percent'],
                        'score' => $score,
                    ]);

                    $keptIds[] = $created->work_performance_id;
                }
            }

            // Rows removed from the edit form are removed from the evaluation.
            $evaluation->workPerformance()
                ->when(
                    !empty($keptIds),
                    fn($query) => $query->whereNotIn('work_performance_id', $keptIds)
                )
                ->when(
                    empty($keptIds),
                    fn($query) => $query
                )
                ->delete();

            $evaluation->update([
                'updated_by' => $departmentAdmin->user_id,
            ]);

            // Recalculate only this employee's summary.
            app(EvaluationSummaryService::class)
                ->calculateForUser(
                    $evaluationPeriod,
                    $evaluation->evaluatee_id
                );
        });
    }


    /**
     * Get one employee's submitted Attendance evaluation for editing.
     */
    public function getAttendanceForEdit(
        User $departmentAdmin,
        EvaluationPeriod $evaluationPeriod,
        User $user
    ): ?Evaluation {

        if ($evaluationPeriod->status !== 'closed') {
            abort(403, 'ការកែប្រែអាចធ្វើបានតែបន្ទាប់ពីបិទវគ្គវាយតម្លៃប៉ុណ្ណោះ។');
        }

        if ($user->department_id !== $departmentAdmin->department_id) {
            abort(403, 'Unauthorized.');
        }

        if (
            in_array($user->role, [
                'super_admin',
                'evaluation_admin',
                'department_admin',
            ], true)
        ) {
            abort(403, 'Unauthorized.');
        }

        return Evaluation::query()
            ->where('evaluation_period_id', $evaluationPeriod->evaluation_period_id)
            ->where('evaluatee_id', $user->user_id)
            ->where('evaluation_type', 'attendance')
            ->where('evaluation_status', 'submitted')
            ->with([
                'evaluatee',
                'attendance',
            ])
            ->first();
    }


    /**
     * Update one employee's submitted Attendance evaluation and
     * recalculate the employee's combined summary.
     */
    public function updateAttendance(
        User $departmentAdmin,
        EvaluationPeriod $evaluationPeriod,
        User $user,
        array $data
    ): void {

        $evaluation = $this->getAttendanceForEdit(
            $departmentAdmin,
            $evaluationPeriod,
            $user
        );

        if (!$evaluation) {
            abort(404, 'Attendance evaluation not found.');
        }

        if (!$evaluation->attendance) {
            abort(404, 'Attendance record not found.');
        }

        DB::transaction(function () use ($evaluation, $evaluationPeriod, $data, $departmentAdmin) {

            $approvedLeaveDays = max(
                0,
                (float) ($data['approved_leave_days'] ?? 0)
            );

            $unapprovedLeaveDays = max(
                0,
                (float) ($data['unapproved_leave_days'] ?? 0)
            );

            $lateHours = max(
                0,
                (float) ($data['late_hours'] ?? 0)
            );

            $leaveEarlyHours = max(
                0,
                (float) ($data['leave_early_hours'] ?? 0)
            );

            $overtimeHours = max(
                0,
                (float) ($data['overtime_hours'] ?? 0)
            );

            $perfectAttendance = filter_var(
                $data['perfect_attendance'] ?? false,
                FILTER_VALIDATE_BOOLEAN
            );

            if ($perfectAttendance) {
                $attendancePercent = 100;
                $approvedLeaveDays = 0;
                $unapprovedLeaveDays = 0;
                $lateHours = 0;
                $leaveEarlyHours = 0;
            } else {
                $approvedHours = $approvedLeaveDays * 8 * 0.5;
                $unapprovedHours = $unapprovedLeaveDays * 8;

                $deductionHours =
                    $approvedHours
                    + $unapprovedHours
                    + $lateHours
                    + $leaveEarlyHours;

                $deductionPercent = $deductionHours / 1.76;

                $attendancePercent = max(
                    0,
                    round(100 - $deductionPercent, 2)
                );
            }

            if ($attendancePercent < 80) {
                $attendanceScore = 0;
            } elseif ($attendancePercent < 90) {
                $attendanceScore = 5;
            } elseif ($attendancePercent < 95) {
                $attendanceScore = 10;
            } elseif ($attendancePercent < 100) {
                $attendanceScore = 15;
            } else {
                $attendanceScore = 20;
            }

            $evaluation->attendance->update([
                'approved_leave_count' => $approvedLeaveDays,
                'unapproved_leave_count' => $unapprovedLeaveDays,
                'late_hours' => $lateHours,
                'leave_early_hours' => $leaveEarlyHours,
                'overtime_hours' => $overtimeHours,
                'attendance_percent' => $attendancePercent,
                'attendance_score' => $attendanceScore,
            ]);

            $evaluation->update([
                'updated_by' => $departmentAdmin->user_id,
            ]);

            app(EvaluationSummaryService::class)
                ->calculateForUser(
                    $evaluationPeriod,
                    $evaluation->evaluatee_id
                );
        });
    }


    /**
     * Get all evaluation results for export.
     *
     * Returns all matching employees without pagination.
     * Search filter is applied when provided.
     */
    public function getDepartmentResultsForExport(
        User $admin,
        EvaluationPeriod $evaluationPeriod,
        Request $request
    ): Collection {

        return EvaluationSummary::query()

            ->with([
                'evaluationPeriodUser.user',
                'evaluationPeriodUser.evaluationPeriod',
            ])

            ->whereHas(
                'evaluationPeriodUser',
                function ($query) use ($admin, $evaluationPeriod) {

                    $query
                        ->where(
                            'evaluation_period_id',
                            $evaluationPeriod->evaluation_period_id
                        )

                        ->whereHas(
                            'user',
                            function ($userQuery) use ($admin) {

                                $userQuery
                                    ->where(
                                        'department_id',
                                        $admin->department_id
                                    )

                                    // Only normal users
                                    ->whereNotIn('role', [
                                        'super_admin',
                                        'evaluation_admin',
                                        'department_admin',
                                    ]);

                                // Exclude leaders
                                // ->where('is_leader', 0);
                            }
                        );
                }
            )
            // ==========================================================
            // Office Filter
            // ==========================================================
            ->when(
                $request->office_id,
                function ($query) use ($request) {
                    $query->whereHas(
                        'evaluationPeriodUser.user',
                        function ($userQuery) use ($request) {
                            $userQuery->where(
                                'office_id',
                                $request->office_id
                            );
                        }
                    );
                }
            )
            // Search
            ->when(
                $request->search,
                function ($query) use ($request) {

                    $search = $request->search;

                    $query->whereHas(
                        'evaluationPeriodUser.user',
                        function ($userQuery) use ($search) {

                            $userQuery->where(function ($q) use ($search) {

                                $q->where(
                                    'name_kh',
                                    'like',
                                    "%{$search}%"
                                )

                                    ->orWhere(
                                        'name_en',
                                        'like',
                                        "%{$search}%"
                                    )

                                    ->orWhere(
                                        'id_code',
                                        'like',
                                        "%{$search}%"
                                    );

                            });

                        }
                    );

                }
            )

            // Highest score first
            ->orderByDesc('total_score')

            // IMPORTANT:
            // No paginate() here.
            ->get();
    }
    public function updateRemark(
        EvaluationSummary $evaluationSummary,
        ?string $remarks
    ): void {
        $user = auth()->user();

        if ($user->role !== 'department_admin') {
            abort(403, 'Unauthorized.');
        }

        $evaluationPeriodUser =
            $evaluationSummary->evaluationPeriodUser;

        if (!$evaluationPeriodUser) {
            abort(404, 'Evaluation record not found.');
        }

        $employee = $evaluationPeriodUser->user;

        if (!$employee) {
            abort(404, 'User not found.');
        }

        // Department admin can only update
        // users in their own department.
        if ($employee->department_id !== $user->department_id) {
            abort(403, 'Unauthorized.');
        }

        // Only normal users can appear in department results.
        if (
            $employee->role !== 'user' ||
            $employee->is_leader != 0
        ) {
            abort(403, 'Unauthorized.');
        }

        $evaluationSummary->update([
            'remarks' => $remarks !== null
                ? trim($remarks)
                : null,
        ]);
    }
}
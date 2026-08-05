<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Evaluation;
use App\Models\EvaluationAttendance;
use App\Models\EvaluationBehavior;
use App\Models\EvaluationWorkPerformance;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EvaluationService
{
    public function store(array $data): Evaluation
    {
        return DB::transaction(function () use ($data) {

            // Step 1
            // Create Evaluation
            $evaluation = Evaluation::create([
                'user_id' => Auth::id(),
                'evaluation_month' => $data['evaluation_month'],
                'evaluation_year' => $data['evaluation_year'],
                'evaluation_status' => 'submitted',
                'submitted_at' => now(),
                'created_by' => Auth::id(),

            ]);
            Log::info($evaluation->toArray());

            // Step 2
            // Store Work Performance
            $this->storeWorkPerformance(
                $evaluation,
                $data['performances']
            );

            // Step 3
            // Attendance (Next)
            $this->storeAttendance(
                $evaluation,
                $data
            );
            Log::info('Attendance stored successfully for evaluation ID: ' . $evaluation->evaluation_id);

            // Step 4
            // Behavior (Next)
            $this->storeBehavior(
                $evaluation,
                $data
            );
            Log::info('Behavior stored successfully for evaluation ID: ' . $evaluation->evaluation_id);

            // Step 5
            $this->updateTotalScore($evaluation);

            return $evaluation;

        });
    }

    /**
     * Store Work Performance
     */
    private function storeWorkPerformance(
        Evaluation $evaluation,
        array $performances
    ): void {

        $totalScore = 0;

        foreach ($performances as $performance) {

            $score = $this->calculateRowScore(
                $performance['achievement_percent']
            );

            EvaluationWorkPerformance::create([

                'evaluation_id' => $evaluation->evaluation_id,
                'activity' => $performance['activity'],
                'indicator' => $performance['indicator'],
                'achievement_percent' => $performance['achievement_percent'],
                'score' => $score,

            ]);

            $totalScore += $score;

        }

        $evaluation->update([

            'work_performance_score' => $this->calculateWorkPerformanceScore(
                $totalScore
            )

        ]);

    }
    /**
     * Store Attendance
     */
    private function storeAttendance(
        Evaluation $evaluation,
        array $data
    ): void {

        // Perfect attendance
        if (
            $data['approved_leave_days'] == 0 &&
            $data['unapproved_leave_days'] == 0 &&
            $data['late_hours'] == 0 &&
            $data['leave_early_hours'] == 0
        ) {

            $attendancePercent = 100;
            $attendanceScore = 20;

        } else {

            $attendancePercent = $this->calculateAttendancePercent($data);
            $attendanceScore = $this->calculateAttendanceScore(
                $attendancePercent
            );

        }

        EvaluationAttendance::create([
            'evaluation_id' => $evaluation->evaluation_id,
            'approved_leave_count' => $data['approved_leave_days'],
            'unapproved_leave_count' => $data['unapproved_leave_days'],
            'late_hours' => $data['late_hours'],
            'leave_early_hours' => $data['leave_early_hours'],
            'attendance_percent' => $attendancePercent,
            'attendance_score' => $attendanceScore,
        ]);

        $evaluation->update([
            'attendance_score' => $attendanceScore

        ]);

    }
    private function storeBehavior(
        Evaluation $evaluation,
        array $data
    ): void {
        $totalScore =
            $data['discipline']
            + $data['responsibility']
            + $data['professional_ethics']
            + $data['work_performance']
            + $data['self_development']
            + $data['initiative_creativity']
            + $data['teamwork']
            + $data['interpersonal_skill']
            + $data['work_under_pressure']
            + $data['leadership'];

        EvaluationBehavior::create([
            'evaluation_id' => $evaluation->evaluation_id,
            'discipline' => $data['discipline'],
            'responsibility' => $data['responsibility'],
            'professional_ethics' => $data['professional_ethics'],
            'work_performance' => $data['work_performance'],
            'self_development' => $data['self_development'],
            'initiative_creativity' => $data['initiative_creativity'],
            'teamwork' => $data['teamwork'],
            'interpersonal_skill' => $data['interpersonal_skill'],
            'work_under_pressure' => $data['work_under_pressure'],
            'leadership' => $data['leadership'],
            'total_score' => $totalScore,
        ]);
        $evaluation->update([
            'behavior_score' => $totalScore,
        ]);
    }

    /**
     * Calculate Row Score
     */
    private function calculateRowScore(
        float $achievementPercent
    ): float {

        return round(
            ($achievementPercent * 20) / 100,
            2
        );
    }

    /**
     * Calculate Work Performance Score
     */
    private function calculateWorkPerformanceScore(
        float $totalScore
    ): float {
        if ($totalScore > 0 && $totalScore <= 60) {
            return 0;
        }
        if ($totalScore > 60 && $totalScore <= 70) {
            return 15;
        }
        if ($totalScore > 70 && $totalScore <= 80) {
            return 30;
        }
        if ($totalScore > 80 && $totalScore <= 90) {
            return 45;
        }
        if ($totalScore > 90 && $totalScore <= 100) {
            return 60;
        }
        return 0;
    }

    /**
     * Calculate Attendance Percent
     */
    private function calculateAttendancePercent(array $data): float
    {
        // Approved leave only deducts 50%
        $approvedHours = $data['approved_leave_days'] * 8 * 0.5;
        // Full deduction
        $unapprovedHours = $data['unapproved_leave_days'] * 8;
        $lateHours = $data['late_hours'];
        $leaveEarlyHours = $data['leave_early_hours'];
        $deductionHours = $approvedHours + $unapprovedHours + $lateHours + $leaveEarlyHours;
        // 1% = 1.76 hours
        $deductionPercent = $deductionHours / 1.76;

        $attendancePercent =
            100 - $deductionPercent;

        return max(
            0,
            round($attendancePercent, 2)
        );

    }
    /**
     * Calculate Attendance Score
     */
    private function calculateAttendanceScore(float $attendancePercent): float
    {
        return round(($attendancePercent * 20) / 100, 2);
    }

    /**
     * Update Evaluation Total Score
     */
    private function updateTotalScore(Evaluation $evaluation): void
    {

        $totalScore =
            (float) $evaluation->work_performance_score +
            (float) $evaluation->attendance_score +
            (float) $evaluation->behavior_score;

        $evaluation->total_score = $totalScore;
        $evaluation->save();
    }
    // Get org
    public function getOrganizations(): array
    {
        return Organization::query()
            ->orderBy('org_name_kh')
            ->pluck('org_name_kh', 'organization_id')
            ->toArray();
    }
    public function getDepartmentsByOrganization($organizationId): array
    {
        return Department::query()
            ->where('organization_id', $organizationId)
            ->orderBy('department_name_kh')
            ->pluck('department_name_kh', 'department_id')
            ->toArray();
    }
    // Get department by org
    public function getDepartments($user): array
    {
        $query = Department::query();

        if ($user->role === 'organization_admin') {

            $query->where('organization_id', $user->organization_id);

        }

        return $query
            ->orderBy('department_name_kh')
            ->pluck('department_name_kh', 'department_id')
            ->toArray();
    }

    // public function getEvaluationList(array $filters, User $user): LengthAwarePaginator
    // {
    //     $query = Evaluation::query()
    //         ->with([
    //             'user.organization',
    //             'user.department',
    //         ]);

    //     // ============================================
    //     // Role Permission
    //     // ============================================

    //     if ($user->role === 'organization_admin') {

    //         $query->whereHas('user', function ($q) use ($user) {

    //             $q->where(
    //                 'organization_id',
    //                 $user->organization_id
    //             );

    //         });

    //     } elseif ($user->role === 'department_admin') {

    //         $query->whereHas('user', function ($q) use ($user) {

    //             $q->where(
    //                 'department_id',
    //                 $user->department_id
    //             );

    //         });

    //     }

    //     // ============================================
    //     // Search
    //     // ============================================

    //     if (!empty($filters['search'])) {

    //         $search = trim($filters['search']);

    //         $query->whereHas('user', function ($q) use ($search) {

    //             $q->where('id_code', 'like', "%{$search}%")
    //                 ->orWhere('name_kh', 'like', "%{$search}%")
    //                 ->orWhere('name_en', 'like', "%{$search}%")
    //                 ->orWhere('username', 'like', "%{$search}%");

    //         });

    //     }

    //     // ============================================
    //     // Organization (Super Admin Only)
    //     // ============================================

    //     if (
    //         $user->role === 'super_admin' &&
    //         !empty($filters['organization'])
    //     ) {

    //         $query->whereHas('user', function ($q) use ($filters) {

    //             $q->where(
    //                 'organization_id',
    //                 $filters['organization']
    //             );

    //         });

    //     }

    //     // ============================================
    //     // Department
    //     // ============================================

    //     if (!empty($filters['department'])) {

    //         $query->whereHas('user', function ($q) use ($filters) {

    //             $q->where(
    //                 'department_id',
    //                 $filters['department']
    //             );


    //         });

    //     }

    //     // ============================================
    //     // Month
    //     // ============================================

    //     if (!empty($filters['month'])) {

    //         $query->where(
    //             'evaluation_month',
    //             $filters['month']
    //         );

    //     }

    //     // ============================================
    //     // Year
    //     // ============================================

    //     if (!empty($filters['year'])) {

    //         $query->where(
    //             'evaluation_year',
    //             $filters['year']
    //         );

    //     }

    //     // ============================================
    //     // Sort
    //     // ============================================

    //     return $query
    //         ->latest('submitted_at')
    //         ->paginate(10)
    //         ->withQueryString();
    // }


    public function getEvaluationList(
        array $filters,
        User $user
    ): LengthAwarePaginator {

        return $this
            ->buildEvaluationQuery(
                $filters,
                $user
            )
            ->latest('submitted_at')
            ->paginate(10)
            ->withQueryString();

    }
    public function buildEvaluationQuery(
        array $filters,
        User $user
    ) {
        $query = Evaluation::query()
            ->with([
                'user.organization',
                'user.department',
            ]);

        // ============================================
        // Role Permission
        // ============================================

        if ($user->role === 'organization_admin') {

            $query->whereHas('user', function ($q) use ($user) {

                $q->where(
                    'organization_id',
                    $user->organization_id
                );

            });

        } elseif ($user->role === 'department_admin') {

            $query->whereHas('user', function ($q) use ($user) {

                $q->where(
                    'department_id',
                    $user->department_id
                );

            });

        }

        // ============================================
        // Search
        // ============================================

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->whereHas('user', function ($q) use ($search) {

                $q->where('id_code', 'like', "%{$search}%")
                    ->orWhere('name_kh', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");

            });

        }

        // ============================================
        // Organization
        // ============================================

        if (
            $user->role === 'super_admin' &&
            !empty($filters['organization'])
        ) {

            $query->whereHas('user', function ($q) use ($filters) {

                $q->where(
                    'organization_id',
                    $filters['organization']
                );

            });

        }

        // ============================================
        // Department
        // ============================================

        if (!empty($filters['department'])) {

            $query->whereHas('user', function ($q) use ($filters) {

                $q->where(
                    'department_id',
                    $filters['department']
                );

            });

        }

        // ============================================
        // Month
        // ============================================

        if (!empty($filters['month'])) {

            $query->where(
                'evaluation_month',
                $filters['month']
            );

        }

        // ============================================
        // Year
        // ============================================

        if (!empty($filters['year'])) {

            $query->where(
                'evaluation_year',
                $filters['year']
            );

        }

        return $query;
    }

}



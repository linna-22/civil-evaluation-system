<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\EvaluationDataEntryAssignment;
use App\Models\EvaluationPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class WorkPerformanceEvaluationService
{
    /**
     * Get the evaluation period that is currently open and within its dates.
     */
    public function getOpenEvaluationPeriod(): ?EvaluationPeriod
    {
        return EvaluationPeriod::query()
            ->where('status', 'open')
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->latest('evaluation_period_id')
            ->first();
    }

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
     * - Active users of ANY role
     * - is_leader is NOT used as a filter
     * - Must be a participant in this evaluation period
     *
     * DEPARTMENT assignment:
     * - Same department
     * - No office (office_id IS NULL)
     * - Active users of ANY role
     * - Excludes department_admin from the evaluatee list
     * - Must be a participant in this evaluation period
     *
     * This means department-level data entry does NOT
     * see employees belonging to offices.
     */
    public function getEligibleUsers(
        ?EvaluationPeriod $evaluationPeriod = null
    ): Collection {
        $evaluationPeriod ??= $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod || !auth()->check()) {
            return new Collection();
        }

        /*
        |--------------------------------------------------------------------------
        | Get the EXACT assignment of the logged-in user
        |--------------------------------------------------------------------------
        */

        $assignment = $this->getCurrentAssignment(
            $evaluationPeriod
        );

        if (!$assignment) {
            return new Collection();
        }

        /*
        |--------------------------------------------------------------------------
        | Base query
        |--------------------------------------------------------------------------
        |
        | User MUST:
        | - be active
        | - belong to this evaluation period
        |
        */

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

        /*
        |--------------------------------------------------------------------------
        | OFFICE ASSIGNMENT
        |--------------------------------------------------------------------------
        */

        if ($assignment->scope === 'office') {

            if (!$assignment->department_id || !$assignment->office_id) {
                return new Collection();
            }

            return $query
                ->where(
                    'department_id',
                    $assignment->department_id
                )
                ->where(
                    'office_id',
                    $assignment->office_id
                )
                ->orderBy('name_kh')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT ASSIGNMENT
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Department-level means office_id IS NULL.
        |
        | It does NOT mean:
        |     office_id != assigned office
        |
        | It means:
        |     office_id IS NULL
        |
        */

        if ($assignment->scope === 'department') {

            return $query
                ->where(
                    'department_id',
                    $assignment->department_id
                )
                ->whereNull('office_id')
                ->where(function ($query) {
                    $query->whereNull('role')
                        ->orWhere('role', '<>', 'department_admin');
                })
                ->orderBy('name_kh')
                ->get();
        }

        return new Collection();
    }

    /**
     * Find one evaluatee and verify that the logged-in
     * data-entry user is responsible for them.
     */
    public function getEligibleUser(
        EvaluationPeriod $evaluationPeriod,
        int $userId
    ): ?User {
        return $this
            ->getEligibleUsers($evaluationPeriod)
            ->firstWhere('user_id', $userId);
    }

    /**
     * Start a new work-performance evaluation session.
     */
    public function startEvaluation(): void
    {
        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            abort(
                404,
                'បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃដែលកំពុងបើកទេ។'
            );
        }

        $assignment = $this->getCurrentAssignment(
            $evaluationPeriod
        );

        if (!$assignment) {
            abort(
                403,
                'អ្នកមិនទាន់ត្រូវបានកំណត់ជាអ្នកបញ្ចូលទិន្នន័យសម្រាប់វគ្គវាយតម្លៃនេះទេ។'
            );
        }

        $users = $this->getEligibleUsers(
            $evaluationPeriod
        );

        if ($users->isEmpty()) {
            abort(
                404,
                'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ។'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Store the exact evaluation period
        |--------------------------------------------------------------------------
        */

        session()->put(
            'work_performance_evaluation_period_id',
            $evaluationPeriod->evaluation_period_id
        );

        /*
        |--------------------------------------------------------------------------
        | Store the exact data-entry assignment
        |--------------------------------------------------------------------------
        |
        | Important because one user may have different assignments
        | across different evaluation periods.
        |
        */

        session()->put(
            'work_performance_assignment_id',
            $assignment->evaluation_data_entry_assignment_id
        );

        /*
        |--------------------------------------------------------------------------
        | Store only users allowed by this assignment
        |--------------------------------------------------------------------------
        */

        session()->put(
            'work_performance_user_ids',
            $users
                ->pluck('user_id')
                ->values()
                ->toArray()
        );

        session()->put(
            'work_performance_current_index',
            0
        );
    }

    /**
     * Get the current user being evaluated.
     */
    public function getCurrentUser(): ?User
    {
        $userIds = session()->get(
            'work_performance_user_ids',
            []
        );

        $currentIndex = $this->getCurrentIndex();

        if (!isset($userIds[$currentIndex])) {
            return null;
        }

        return User::find(
            $userIds[$currentIndex]
        );
    }

    /**
     * Get current zero-based index.
     */
    public function getCurrentIndex(): int
    {
        return (int) session()->get(
            'work_performance_current_index',
            0
        );
    }

    /**
     * Get current user number for display.
     */
    public function getCurrentUserNumber(): int
    {
        return $this->getCurrentIndex() + 1;
    }

    /**
     * Get total users in the current session.
     */
    public function getTotalUsers(): int
    {
        return count(
            session()->get(
                'work_performance_user_ids',
                []
            )
        );
    }

    /**
     * Get current evaluation period ID from session.
     */
    public function getCurrentEvaluationPeriodId(): ?int
    {
        return session()->get(
            'work_performance_evaluation_period_id'
        );
    }

    /**
     * Get submitted work-performance evaluations
     * for the supplied users.
     */
    public function getSubmittedUserIds(
        array $userIds
    ): array {
        $evaluationPeriod =
            $this->getOpenEvaluationPeriod();

        if (
            !$evaluationPeriod ||
            empty($userIds)
        ) {
            return [];
        }

        return Evaluation::query()
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
                $userIds
            )
            ->pluck('evaluatee_id')
            ->toArray();
    }

    /**
     * Check whether all assigned users have submitted.
     */
    public function allUsersSubmitted(
        array $userIds
    ): bool {
        if (empty($userIds)) {
            return false;
        }

        $submittedUserIds =
            $this->getSubmittedUserIds(
                $userIds
            );

        return count($submittedUserIds)
            === count($userIds);
    }
}
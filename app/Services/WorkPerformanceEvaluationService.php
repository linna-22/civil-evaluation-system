<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class WorkPerformanceEvaluationService
{
    /*
    |--------------------------------------------------------------------------
    | Get Open Evaluation Period
    |--------------------------------------------------------------------------
    |
    | Get the evaluation period that is currently open and within
    | its start and end dates.
    |
    */

    public function getOpenEvaluationPeriod(): ?EvaluationPeriod
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
            ->latest('evaluation_period_id')
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Get Eligible Users
    |--------------------------------------------------------------------------
    |
    | Get all active officers that the logged-in Department Admin
    | is allowed to evaluate.
    |
    */

    public function getEligibleUsers(): Collection
    {
        $admin = auth()->user();
        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return collect();
        }

        return User::query()
            ->where('department_id', $admin->department_id)
            ->where('office_id', $admin->office_id)
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


    /*
    |--------------------------------------------------------------------------
    | Start Evaluation
    |--------------------------------------------------------------------------
    |
    | Start a new evaluation session.
    |
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

    $users = $this->getEligibleUsers();

    if ($users->isEmpty()) {
        abort(
            404,
            'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ'
        );
    }

    session()->put(
        'work_performance_evaluation_period_id',
        $evaluationPeriod->evaluation_period_id
    );

    session()->put(
        'work_performance_office_id',
        auth()->user()->office_id
    );

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


    /*
    |--------------------------------------------------------------------------
    | Get Current User
    |--------------------------------------------------------------------------
    */

    public function getCurrentUser(): ?User
    {
        $userIds =
            session()->get(
                'work_performance_user_ids',
                []
            );
        $currentIndex = $this->getCurrentIndex();
        if (!isset($userIds[$currentIndex])) {
            return null;
        }
        return User::find($userIds[$currentIndex]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Current Index
    |--------------------------------------------------------------------------
    |
    | We use zero-based index internally.
    |
    | User 1 = 0
    | User 2 = 1
    | User 3 = 2
    |
    */

    public function getCurrentIndex(): int
    {
        return (int) session()->get(
            'work_performance_current_index',
            0
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Current User Number
    |--------------------------------------------------------------------------
    |
    | This is the number we display in the UI.
    |
    | User 1 = 1
    | User 2 = 2
    | User 3 = 3
    |
    */

    public function getCurrentUserNumber(): int
    {
        return $this->getCurrentIndex() + 1;
    }


    /*
    |--------------------------------------------------------------------------
    | Get Total Users
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | Get Current Evaluation Period ID
    |--------------------------------------------------------------------------
    */

    public function getCurrentEvaluationPeriodId(): ?int
    {
        return session()->get(
            'work_performance_evaluation_period_id'
        );
    }
    /*
|--------------------------------------------------------------------------
| Get Submitted Evaluation For View
|--------------------------------------------------------------------------
|
| Get one submitted work performance evaluation
| with its employee, evaluation period and activities.
|
*/

    public function getSubmittedUserIds(array $userIds): array
    {
        $evaluationPeriod = $this->getOpenEvaluationPeriod();
        if (!$evaluationPeriod || empty($userIds)) {
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
     * Check whether all users have submitted
     * their evaluation for the current period.
     */
    public function allUsersSubmitted(
        array $userIds
    ): bool {

        if (empty($userIds)) {
            return false;
        }

        $submittedUserIds =
            $this->getSubmittedUserIds($userIds);

        return count($submittedUserIds)
            === count($userIds);
    }
}
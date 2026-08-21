<?php

namespace App\Services;

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

    public function getEligibleUsers(?int $officeId = null): Collection
    {
        $admin = auth()->user();

        $query = User::query()
            ->where('department_id', $admin->department_id)
            ->where('status', 'active')
            ->where('is_leader', false);

        /*
        |--------------------------------------------------------------------------
        | If an office is selected
        |--------------------------------------------------------------------------
        */

        if ($officeId !== null) {

            $query->where('office_id', $officeId);

        } else {

            /*
            |--------------------------------------------------------------------------
            | No office selected
            | Only users directly under the department
            |--------------------------------------------------------------------------
            */

            $query->whereNull('office_id');

        }

        return $query
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

    public function startEvaluation(?int $officeId = null): void
    {
        /*
        |--------------------------------------------------------------------------
        | Make sure an evaluation period is open
        |--------------------------------------------------------------------------
        */

        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {

            abort(
                404,
                'បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃដែលកំពុងបើកទេ។'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Get eligible users
        |--------------------------------------------------------------------------
        */

        $users = $this->getEligibleUsers($officeId);

        if ($users->isEmpty()) {

            abort(
                404,
                'មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Store evaluation period
        |--------------------------------------------------------------------------
        */

        session()->put(
            'work_performance_evaluation_period_id',
            $evaluationPeriod->evaluation_period_id
        );


        /*
        |--------------------------------------------------------------------------
        | Store selected office
        |--------------------------------------------------------------------------
        */

        session()->put(
            'work_performance_office_id',
            $officeId
        );


        /*
        |--------------------------------------------------------------------------
        | Store selected users
        |--------------------------------------------------------------------------
        */

        session()->put(
            'work_performance_user_ids',
            $users
                ->pluck('user_id')
                ->values()
                ->toArray()
        );


        /*
        |--------------------------------------------------------------------------
        | Start from first user
        |--------------------------------------------------------------------------
        */

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
}
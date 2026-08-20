<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class WorkPerformanceEvaluationService
{
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

        return User::query()
            ->where('department_id', $admin->department_id)
            ->where('status', 'active')
            ->where('is_leader', false)
            ->orderBy('name_kh')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Start Evaluation
    |--------------------------------------------------------------------------
    |
    | Start a new evaluation session by storing the IDs of all
    | eligible users in the session.
    |
    */

    public function startEvaluation(): void
    {
        $users = $this->getEligibleUsers();

        session()->put(
            'work_performance_user_ids',
            $users->pluck('user_id')->values()->toArray()
        );

        // Start from the first user
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
        $userIds = session()->get(
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
}
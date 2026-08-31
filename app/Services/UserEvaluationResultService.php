<?php

namespace App\Services;

use App\Models\EvaluationPeriod;
use App\Models\EvaluationSummary;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserEvaluationResultService
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
     * Get logged-in user's result
     * for a specific evaluation period.
     */
   public function getResultForPeriod(
    User $user,
    EvaluationPeriod $evaluationPeriod
): ?EvaluationSummary {

    return EvaluationSummary::query()
        ->with([
            'evaluationPeriodUser.user',
            'evaluationPeriodUser.evaluationPeriod',
        ])
        ->whereHas('evaluationPeriodUser', function ($query) use (
            $user,
            $evaluationPeriod
        ) {
            $query
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->where(
                    'user_id',
                    $user->user_id
                );
        })
        ->first();
}
}
<?php

namespace App\Services;

use App\Models\EvaluationPeriod;
use App\Models\User;
use App\Models\EvaluationPeriodUser;

class EvaluationReviewService
{
    /**
     * Get the current open evaluation period.
     */
    public function getOpenEvaluationPeriod()
    {
        return EvaluationPeriod::query()
            ->where('status', 'open')
            ->first();
    }

    /**
     * Get the department of the currently authenticated
     * department admin.
     */
    public function getDepartmentId()
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'department_admin') {
            abort(403);
        }

        return $user->department_id;
    }

    public function getDepartmentUsers()
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'department_admin') {
            abort(403);
        }

        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return collect();
        }

        return User::query()
            ->where('status', 'active')
            ->where('organization_id', $user->organization_id)
            ->where('department_id', $user->department_id)
            ->whereHas('evaluationPeriodUsers', function ($query) use ($evaluationPeriod) {
                $query->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                );
            })
            ->orderBy('name_kh')
            ->get();
    }
}
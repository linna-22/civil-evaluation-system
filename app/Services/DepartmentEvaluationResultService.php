<?php

namespace App\Services;

use App\Models\EvaluationPeriod;
use App\Models\EvaluationSummary;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
                                    ->where('role', 'user')
                                    // Exclude leaders
                                    ->where('is_leader', 0);
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

            // Pagination
            ->paginate(
                $request->input('per_page', 10)
            );
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

                                    ->where('role', 'user')

                                    ->where('is_leader', 0);

                            }
                        );

                }
            )->first();
    }
}
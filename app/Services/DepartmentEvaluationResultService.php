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
    public function getDepartmentResults(User $admin, EvaluationPeriod $evaluationPeriod, Request $request): LengthAwarePaginator
    {
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

        // The remark action must follow the same employee scope as the
        // department result list above. Do not restrict this to role=user
        // because the result query may also contain other allowed roles
        // (for example office_admin or organization_admin).
        if (in_array($employee->role, [
            'super_admin',
            'evaluation_admin',
            'department_admin',
        ], true)) {
            abort(403, 'Unauthorized.');
        }

        $evaluationPeriod = $evaluationSummary->evaluationPeriodUser?->evaluationPeriod;

        if (!$evaluationPeriod || $evaluationPeriod->status !== 'closed') {
            abort(403, 'Unauthorized.');
        }

        $evaluationSummary->update([
            'remarks' => $remarks !== null
                ? trim($remarks)
                : null,
        ]);
    }
}
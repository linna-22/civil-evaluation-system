<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Organization;
use App\Models\User;

class EvaluationReportService
{
    public function __construct(
        protected EvaluationService $evaluationService
    ) {
    }

    public function getReportData(
        array $filters,
        User $user
    ): array {

        $evaluations = $this->evaluationService
            ->buildEvaluationQuery(
                $filters,
                $user
            )
            ->latest('submitted_at')
            ->get();

        $organization = null;
        $department = null;
        $leader = null;

        $organizationId = $user->role === 'super_admin'
            ? ($filters['organization'] ?? null)
            : $user->organization_id;

        // Organization
        if ($organizationId) {

            $organization = Organization::find($organizationId);

            $leader = User::where('organization_id', $organizationId)
                ->where('is_leader', 1)
                ->where('status', 'active')
                ->first();

        }

        // Department
        if (!empty($filters['department'])) {

            $department = Department::find(
                $filters['department']
            );

        }

        return [

            'evaluations' => $evaluations,

            'filters' => $filters,

            'organization' => $organization,

            'department' => $department,

            'user' => $user,
            'reportDate' => now(),
            'leader' => $leader,

        ];

    }
}
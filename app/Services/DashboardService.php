<?php

namespace App\Services;
use App\Models\Department;
use App\Models\Organization;
use App\Models\User;

class DashboardService
{
    public function statistics(): array
    {
        return [

            'users' => User::count(),

            'organizations' => Organization::count(),

            'departments' => Department::count(),

            'evaluations' => 0,

        ];
    }
}
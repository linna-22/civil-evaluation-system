<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::where(
            'organization_code',
            'MLVT'
        )->first();

        Department::updateOrCreate(
            [
                'organization_id' => $organization->organization_id,
                'department_code' => 'ADMIN',
            ],
            [
                'department_name' => 'System Administration',
                'status' => 'active',
            ]
        );
    }
}
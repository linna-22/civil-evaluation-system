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
            'org_code',
            'GDAF'
        )->first();

        Department::updateOrCreate(
            [
                'organization_id' => $organization->organization_id,
                'department_code' => 'SGAF',
            ],
            [
                'department_name_kh' => 'លេខាធិការដ្ឋាននៃអគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ',
                'department_name_en' => 'Secretariat of the General Department of Administration and Finance',
                'desc' => null,
                'status' => 'active',
            ]
        );
    }
}
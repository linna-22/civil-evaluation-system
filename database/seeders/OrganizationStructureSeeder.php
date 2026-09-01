<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Department;
use App\Models\Office;
use Illuminate\Database\Seeder;

class OrganizationStructureSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Organization 1
        |--------------------------------------------------------------------------
        */

        $organization1 = Organization::create([
            'org_code' => '3100000010',
            'org_name_kh' => 'អគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ',
            'org_name_en' => 'General Department of Administration and Finance',
            'desc' => 'ទិន្នន័យសម្រាប់ប្រព័ន្ធ',
            'status' => 'active',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Organization 1 - Department 1 with no office
        |--------------------------------------------------------------------------
        */

       Department::create([
            'organization_id' => $organization1->organization_id,
            'department_code' => '3101000100',
            'department_name_kh' => 'លេខាធិការដ្ឋាន នៃអគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ',
            'department_name_en' => 'Secretariat of the General Department of Administration and Finance',
            'desc' => 'លេខាធិការដ្ឋាន នៃអគ្គនាយកដ្ឋានរដ្ឋបាលនិងហិរញ្ញវត្ថុ',
            'status' => 'active',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Organization 1 - Department 1 - Office 2
        |--------------------------------------------------------------------------
        */
        $department1 = Department::create([
            'organization_id' => $organization1->organization_id,
            'department_code' => '3101010000',
            'department_name_kh' => 'នាយកដ្ឋានរដ្ឋបាល និងបុគ្គលិក',
            'department_name_en' => 'Department of Administration and Personnel',
            'desc' => 'នាយកដ្ឋានរដ្ឋបាល និងបុគ្គលិក',
            'status' => 'active',
        ]);

        Office::create([
            'department_id' => $department1->department_id,
            'office_code' => '3101010100',
            'office_name_kh' => 'ការិយាល័យរដ្ឋបាល',
            'office_name_en' => 'Administrative office',
            'desc' => 'ការិយាល័យរដ្ឋបាល',
            'status' => 'active',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Organization 1 - Department 1 - Office 2
        |--------------------------------------------------------------------------
        */

        Office::create([
            'department_id' => $department1->department_id,
            'office_code' => '3101010200',
            'office_name_kh' => 'ការិយាល័យបុគ្គលិក',
            'office_name_en' => 'Personnel Office',
            'desc' => 'ការិយាល័យបុគ្គលិក',
            'status' => 'active',
        ]);
    }
}
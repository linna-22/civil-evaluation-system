<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Department;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [

            // =====================================================
            // General Department of Administration and Finance
            // =====================================================

            [
                'org_code' => 'GDAF',
                'org_name_kh' => 'អគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ',
                'org_name_en' => 'General Department of Administration and Finance',
                'desc' => null,
                'status' => 'active',

                'departments' => [

                    [
                        'department_code' => 'SGAF',
                        'department_name_kh' => 'លេខាធិការដ្ឋាននៃអគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ',
                        'department_name_en' => 'Secretariat of the General Department of Administration and Finance',
                    ],
                    [
                        'department_code' => 'DAP',
                        'department_name_kh' => 'នាយកដ្ឋានរដ្ឋបាល និងបុគ្គលិក',
                        'department_name_en' => 'Department of Administration and Personnel',
                    ],
                    [
                        'department_code' => 'DPSL',
                        'department_name_kh' => 'នាយកដ្ឋានផែនការ ស្ថិតិ និងនីតិកម្ម',
                        'department_name_en' => 'Department of Planning, Statistics and Legislation',
                    ],

                ],

            ],

            // =====================================================
            // General Department of Labour
            // =====================================================

            [
                'org_code' => 'GDL',
                'org_name_kh' => 'អគ្គនាយកដ្ឋានការងារ',
                'org_name_en' => 'General Department of Labour',
                'desc' => null,
                'status' => 'active',

                'departments' => [

                    [
                        'department_code' => 'SGDL',
                        'department_name_kh' => 'លេខាធិការដ្ឋាននៃអគ្គនាយកដ្ឋានការងារ',
                        'department_name_en' => 'Secretariat of the General Department of Labor',
                    ],

                    [
                        'department_code' => 'DLI',
                        'department_name_kh' => 'នាយកដ្ឋានអធិការកិច្ចការងារ',
                        'department_name_en' => 'Department of Labor Inspection',
                    ],
                ],
            ],
        ];

        foreach ($organizations as $item) {

            // Create Organization
            $organization = Organization::updateOrCreate(

                [
                    'org_code' => $item['org_code'],
                ],

                [
                    'org_name_kh' => $item['org_name_kh'],
                    'org_name_en' => $item['org_name_en'],
                    'desc' => $item['desc'],
                    'status' => $item['status'],
                ]

            );

            // Create Departments
            foreach ($item['departments'] as $department) {

                Department::updateOrCreate(

                    [
                        'organization_id' => $organization->organization_id,
                        'department_code' => $department['department_code'],
                    ],

                    [
                        'department_name_kh' => $department['department_name_kh'],
                        'department_name_en' => $department['department_name_en'],
                        'desc' => null,
                        'status' => 'active',
                    ]

                );

            }

        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationTestSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Organizations
        |--------------------------------------------------------------------------
        */

        $organizations = [

            [
                'org_code' => 'ORG001',
                'org_name_kh' => 'អង្គភាពសាកល្បងទី១',
                'org_name_en' => 'Test Organization 1',
            ],

            [
                'org_code' => 'ORG002',
                'org_name_kh' => 'អង្គភាពសាកល្បងទី២',
                'org_name_en' => 'Test Organization 2',
            ],

        ];


        foreach ($organizations as $orgData) {

            /*
            |--------------------------------------------------------------------------
            | Create Organization
            |--------------------------------------------------------------------------
            */

            $organization = Organization::create([
                'org_code' => $orgData['org_code'],
                'org_name_kh' => $orgData['org_name_kh'],
                'org_name_en' => $orgData['org_name_en'],
                'desc' => 'ទិន្នន័យសម្រាប់សាកល្បងប្រព័ន្ធ',
                'status' => 'active',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Organization Leader
            |--------------------------------------------------------------------------
            */

            User::create([
                'organization_id' => $organization->organization_id,
                'department_id' => null,
                'office_id' => null,
                'id_code' => $orgData['org_code'] . '-L01',
                'name_kh' => 'ថ្នាក់ដឹកនាំ ' . $organization->org_code,
                'name_en' => 'Leader ' . $organization->org_code,
                'username' => strtolower($orgData['org_code']) . '_leader',
                'gender' => 'male',
                'phone' => '012000001',
                'email' => strtolower($orgData['org_code']) . '_leader@example.com',
                'position' => 'ថ្នាក់ដឹកនាំ',
                'is_leader' => true,
                'password' => 'password',
                'role' => 'organization_admin',
                'status' => 'active',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Department 1
            | Has Offices
            |--------------------------------------------------------------------------
            */

            $department1 = Department::create([
                'organization_id' => $organization->organization_id,
                'department_code' => $orgData['org_code'] . '-D01',
                'department_name_kh' => 'នាយកដ្ឋានរដ្ឋបាល',
                'department_name_en' => 'Administration Department',
                'desc' => 'នាយកដ្ឋានសម្រាប់សាកល្បងដែលមានការិយាល័យ',
                'status' => 'active',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Office 1
            |--------------------------------------------------------------------------
            */

            $office1 = Office::create([
                'department_id' => $department1->department_id,

                'office_code' => $orgData['org_code'] . '-O01',

                'office_name_kh' => 'ការិយាល័យរដ្ឋបាល',
                'office_name_en' => 'Administration Office',

                'desc' => 'ការិយាល័យសម្រាប់សាកល្បង',

                'status' => 'active',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Office 2
            |--------------------------------------------------------------------------
            */

            $office2 = Office::create([
                'department_id' => $department1->department_id,

                'office_code' => $orgData['org_code'] . '-O02',

                'office_name_kh' => 'ការិយាល័យបុគ្គលិក',
                'office_name_en' => 'Personnel Office',

                'desc' => 'ការិយាល័យសម្រាប់សាកល្បង',

                'status' => 'active',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Users - Office 1
            |--------------------------------------------------------------------------
            */

            $this->createUsers(
                $organization,
                $department1,
                $office1,
                $orgData['org_code'] . '-O01',
                3
            );


            /*
            |--------------------------------------------------------------------------
            | Users - Office 2
            |--------------------------------------------------------------------------
            */

            $this->createUsers(
                $organization,
                $department1,
                $office2,
                $orgData['org_code'] . '-O02',
                3
            );


            /*
            |--------------------------------------------------------------------------
            | Department 2
            | NO Offices
            |--------------------------------------------------------------------------
            */

            $department2 = Department::create([
                'organization_id' => $organization->organization_id,

                'department_code' => $orgData['org_code'] . '-D02',

                'department_name_kh' => 'លេខាធិការដ្ឋាន',
                'department_name_en' => 'Secretariat',

                'desc' => 'នាយកដ្ឋានដែលមិនមានការិយាល័យ',

                'status' => 'active',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Users directly under Department 2
            |--------------------------------------------------------------------------
            */

            $this->createUsers(
                $organization,
                $department2,
                null,
                $orgData['org_code'] . '-D02',
                3
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Create Normal Users
    |--------------------------------------------------------------------------
    */

    private function createUsers(
        Organization $organization,
        Department $department,
        ?Office $office,
        string $prefix,
        int $count
    ): void {

        for ($i = 1; $i <= $count; $i++) {

            User::create([
                'organization_id' => $organization->organization_id,
                'department_id' => $department->department_id,
                'office_id' => $office?->office_id,
                'id_code' => $prefix . '-U0' . $i,
                'name_kh' => 'មន្ត្រីសាកល្បង ' . $i,
                'name_en' => 'Test User ' . $i,
                'username' => strtolower($prefix) . '_user' . $i,
                'gender' => $i % 2 === 0
                    ? 'female'
                    : 'male',
                'phone' => '012' . rand(100000, 999999),
                'email' => strtolower($prefix) . '_user' . $i . '@example.com',
                'position' => 'មន្ត្រី',
                'is_leader' => false,
                'password' => 'password',
                'role' => 'user',
                'status' => 'active',
            ]);
        }
    }
}
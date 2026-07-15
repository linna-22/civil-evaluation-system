<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\Department;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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

        $department = Department::where(
            'department_code',
            'ADMIN'
        )->first();

        User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'organization_id' => $organization->organization_id,

                'department_id' => $department->department_id,

                'employee_code' => 'SYS-0001',

                'name_kh' => 'អឿ លីណា',
                'name_en' => 'Oeu Lina',

                'username' => 'admin',

                'gender' => 'female',

                'phone' => '012345678',

                'email' => 'linaoeu567556@gmail.com',

                'position' => 'Administrator',

                'password' => 'admin123',

                'role' => 'super_admin',

                'status' => 'active',
            ]
        );
    }
}
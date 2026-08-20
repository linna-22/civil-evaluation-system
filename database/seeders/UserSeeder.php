<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\Department;
use App\Models\Office;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'organization_id' => null,

                'department_id' => null,
                'office_id' => null,
                'id_code' => 'SYS-0001',
                'name_kh' => 'អឿ លីណា',
                'name_en' => 'Oeu Lina',
                'username' => 'admin',
                'gender' => 'female',
                'phone' => '012345678',
                'email' => 'linaoeu567556@gmail.com',
                'position' => 'Administrator',
                'is_leader' => '0',
                'password' => bcrypt('lina@123'),
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );
    }
}
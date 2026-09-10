<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class EvaluationAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::updateOrCreate(
            [
                'username' => 'evaluation_admin',
            ],
            [
                'organization_id' => null,
                'department_id' => null,
                'office_id' => null,
                'id_code' => null,
                'name_kh' => 'អ្នកកំណត់ការវាយតម្លៃ',
                'name_en' => 'Evaluation Admin',
                'username' => 'evaluation_admin',
                'gender' => 'female',
                'phone' => '012345678',
                'email' => 'evaluationadmin@gmail.com',
                'position' => 'មន្ត្រី',
                'is_leader' => '0',
                'password' => bcrypt('evaluation@123'),
                'role' => 'evaluation_admin',
                'status' => 'active',
            ]
        );
    }
}
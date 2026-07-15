<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::updateOrCreate(
            [
                'organization_code' => 'MLVT',
            ],
            [
                'organization_name' => 'Ministry of Labour and Vocational Training',
                'status' => 'active',
            ]
        );
    }
}
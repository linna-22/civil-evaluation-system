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
                'org_code' => 'GDAF',
            ],
            [
                'org_name_kh' => 'អគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ',
                'org_name_en' => 'General Department of Administration and Finance',
                'desc' => null,
                'status' => 'active',
            ]
        );
    }
}
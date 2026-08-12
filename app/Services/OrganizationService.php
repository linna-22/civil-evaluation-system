<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class OrganizationService
{
    public function store(array $data): Organization
    {
        return Organization::create([

            'org_code' => strtoupper($data['code']),
            'org_name_kh' => $data['name_kh'],
            'org_name_en' => $data['name_en'],
            'desc' => $data['description'],
            'status' => $data['status'],
            'created_by' => auth()->id(),

        ]);
    }

    public function getData(Request $request)
    {
        $query = Organization::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('org_code', 'like', "%{$search}%")
                    ->orWhere('org_name_kh', 'like', "%{$search}%")
                    ->orWhere('org_name_en', 'like', "%{$search}%");

            });

        }

        return $query
            ->orderBy('organization_id', 'asc')
            ->paginate($request->get('per_page', 5));
    }

    public function find(Organization $organization): Organization
    {
        return $organization;
    }
    public function update(
        Organization $organization,
        array $data
    ): Organization {

        $organization->update([

            'org_code' => strtoupper($data['code']),
            'org_name_kh' => $data['name_kh'],
            'org_name_en' => $data['name_en'],
            'desc' => $data['description'],
            'status' => $data['status'],
            'updated_by' => auth()->id(),

        ]);

        return $organization->refresh();

    }
    public function getActive()
    {
        return Organization::query()
            ->where('status', 'active')
            ->orderBy('org_name_kh')
            ->get([
                'organization_id',
                'org_name_kh'
            ]);
    }
        public function getActiveOrganizations()
        {
            return Organization::where('status', 'active')
                ->orderBy('org_name_kh')
                ->get();
        }

}
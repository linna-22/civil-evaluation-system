<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class DepartmentService
{
    public function store(array $data): Department
    {
        return Department::create([

            'organization_id' => $data['organization_id'],
            'department_name_kh' => $data['name_kh'],
            'department_name_en' => $data['name_en'],
            'department_code' => strtoupper($data['code']),
            'desc' => $data['description'],
            'status' => $data['status'],
            'created_by' => auth()->id(),

        ]);
    }
    public function getData(Request $request)
    {
        $query = Department::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('department_code', 'like', "%{$search}%")
                    ->orWhere('department_name_kh', 'like', "%{$search}%")
                    ->orWhere('department_name_en', 'like', "%{$search}%");

            });

        }

        return $query
            ->orderBy('department_id', 'asc')
            ->paginate($request->get('per_page', 5));
    }
    public function find(Department $department): Department
    {
        return $department;
    }
    public function update(Department $department, array $data): Department
    {

        $department->update([

            'organization_id' => $data['organization_id'],
            'department_code' => strtoupper($data['code']),
            'department_name_kh' => $data['name_kh'],
            'department_name_en' => $data['name_en'],
            'desc' => $data['description'],
            'status' => $data['status'],
            'updated_by' => auth()->id(),

        ]);

        return $department->refresh();

    }
    public function delete(Department $department): void
    {
        $department->delete();
    }

    public function getByOrganization($organizationId)
{
    return Department::query()

        ->where(
            'organization_id',
            $organizationId
        )

        ->where(
            'status',
            'active'
        )

        ->orderBy('department_name_kh')

        ->get([
            'department_id',
            'department_name_kh'
        ]);
}
}
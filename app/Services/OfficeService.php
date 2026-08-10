<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Office;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class OfficeService
{
    public function store(array $data): Office
    {
        return Office::create([

            'department_id' => $data['department_id'],
            'office_name_kh' => $data['name_kh'],
            'office_name_en' => $data['name_en'],
            'office_code' => strtoupper($data['code']),
            'desc' => $data['description'],
            'status' => $data['status'],
            'created_by' => auth()->id(),

        ]);
    }
    public function getData(Request $request)
    {
        $query = Office::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('office_code', 'like', "%{$search}%")
                    ->orWhere('office_name_kh', 'like', "%{$search}%")
                    ->orWhere('office_name_en', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderBy('office_id', 'asc')
            ->paginate($request->get('per_page', 5));
    }
    public function find(Office $office): Office
    {
        return $office;
    }
    public function update(Office $office, array $data): Office
    {

        $office->update([

            'department_id' => $data['department_id'],
            'office_code' => strtoupper($data['code']),
            'office_name_kh' => $data['name_kh'],
            'office_name_en' => $data['name_en'],
            'desc' => $data['description'],
            'status' => $data['status'],
            'updated_by' => auth()->id(),

        ]);

        return $office->refresh();

    }
    public function delete(Office $office): void
    {
        $office->update([
            'deleted_by' => auth()->user()->user_id,
        ]);

        $office->delete();
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\Organization;
use App\Services\DepartmentService;
use App\Services\OrganizationService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('departments.index');
    }

    public function data(Request $request, DepartmentService $service)
    {
        $department = $service->getData($request);

        return response()->json([
            'success' => true,
            'message' => 'Department loaded successfully.',
            'data' => $department,
        ]);
    }
    public function create(OrganizationService $organizationService)
    {
        return view('departments.create', [
            'department' => null,
            'organizations' => $organizationService->getActiveOrganizations(),
        ]);
    }
    public function store(DepartmentRequest $request, DepartmentService $service)
    {
        $service->store(

            $request->validated()

        );

        return redirect()

            ->route('departments.index')

            ->with(
                'success',
                'នាយកដ្ឋានត្រូវបានបង្កើតដោយជោគជ័យ'
            );
    }
    public function edit(Department $department, OrganizationService $organizationService)
    {
        return view(
            'departments.edit',
            [
                'department' => $department,
                'organizations' => $organizationService->getActiveOrganizations(),
            ]
        );
    }
    public function update(
        DepartmentRequest $request,
        Department $department,
        DepartmentService $service
    ) {
        $service->update(
            $department,
            $request->validated()
        );

        return redirect()
            ->route('departments.index')
            ->with(
                'success',
                'នាយកដ្ឋានត្រូវបានកែប្រែដោយជោគជ័យ'
            );
    }
    public function byOrganization(Organization $organization, DepartmentService $service) 
    {
        return response()->json(
            $service->getByOrganization($organization->organization_id)
        );
    }
    public function destroy(Department $department, DepartmentService $service)
    {
        $service->delete($department);
        return response()->json([
            'success' => true,
            'message' => 'នាយកដ្ឋានត្រូវបានលុបដោយជោគជ័យ',
        ]);
    }

}

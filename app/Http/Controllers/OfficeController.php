<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfficeRequest;
use App\Models\Office;
use App\Services\DepartmentService;
use App\Services\OfficeService;
use App\Services\OrganizationService;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        return view('offices.index');
    }

    public function data(Request $request, OfficeService $service)
    {
        $office = $service->getData($request);

        return response()->json([
            'success' => true,
            'message' => 'Office loaded successfully.',
            'data' => $office,
        ]);
    }
    public function create(DepartmentService $departmentService)
    {
        return view('offices.create', [
            'office' => null,
            'departments' => $departmentService->getActiveDepartments(),
        ]);
    }
    public function store(OfficeRequest $request, OfficeService $service)
    {
        $service->store($request->validated());
        return redirect()
            ->route('offices.index')
            ->with('success', 'ការិយាល័យត្រូវបានបង្កើតដោយជោគជ័យ');
    }

    public function edit(Office $office, DepartmentService $departmentService)
    {
        return view(
            'offices.edit',
            [
                'office' => $office,
                'departments' => $departmentService->getActiveDepartments(),
            ]
        );
    }
    public function update(OfficeRequest $request, Office $office, OfficeService $service)
    {
        // dd($request->all());
        $service->update($office, $request->validated());
        return redirect()
            ->route('offices.index')
            ->with('success', 'ការិយាល័យត្រូវបានកែប្រែដោយជោគជ័យ');
    }
    public function destroy(Office $office, OfficeService $service)
    {
        $service->delete($office);
        return response()->json([
            'success' => true,
            'message' => 'ការិយាល័យត្រូវបានលុបដោយជោគជ័យ',
        ]);
    }
    public function getByDepartment(
        $departmentId,
        OfficeService $officeService
    ) {
        return response()->json(
            $officeService->getByDepartment($departmentId)
        );
    }
}

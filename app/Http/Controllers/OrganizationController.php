<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\Request;
use App\Imports\OrganizationsImport;
use Maatwebsite\Excel\Facades\Excel;

class OrganizationController extends Controller
{
    public function index()
    {
        return view('organizations.index');
    }

    public function data(Request $request, OrganizationService $service)
    {
        $organizations = $service->getData($request);

        return response()->json([
            'success' => true,
            'message' => 'Organizations loaded successfully.',
            'data' => $organizations,
        ]);
    }
    public function create()
    {
        return view('organizations.create');
    }
    public function store(OrganizationRequest $request, OrganizationService $service)
    {
        // dd($request->all());
        $service->store(

            $request->validated()

        );

        return redirect()

            ->route('organizations.index')

            ->with(
                'success',
                'អង្គភាពត្រូវបានបង្កើតដោយជោគជ័យ'
            );
    }
    public function edit(Organization $organization)
    {
        return view(
            'organizations.edit',
            compact('organization')
        );
    }
    public function update(
        OrganizationRequest $request,
        Organization $organization,
        OrganizationService $service
    ) {
        $service->update(
            $organization,
            $request->validated()
        );

        return redirect()
            ->route('organizations.index')
            ->with(
                'success',
                'អង្គភាពត្រូវបានកែប្រែដោយជោគជ័យ'
            );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
        ]);

        $import = new OrganizationsImport();

        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('organizations.index')
            ->with(
                'success',
                "បាននាំចូលទិន្នន័យអង្គភាពចំនួន {$import->importedCount} ដោយជោគជ័យ។"
            );
    }

}

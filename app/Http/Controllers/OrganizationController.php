<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        return view('organizations.index');
    }

    // public function data(Request $request, OrganizationService $service) 
    // {
    //     return response()->json(
    //         $service->getData($request)
    //     );
    // }
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
}

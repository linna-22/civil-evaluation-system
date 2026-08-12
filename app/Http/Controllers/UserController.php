<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\OrganizationRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\OfficeService;
use App\Services\OrganizationService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function data(Request $request, UserService $service)
    {
        $users = $service->getData($request);

        return response()->json([
            'success' => true,
            'message' => 'users loaded successfully.',
            'data' => $users,
        ]);
    }
    public function create(OrganizationService $organizationService)
    {
        $organizations = $organizationService->getActive();

        return view('users.create', compact('organizations'));
    }
    public function store(UserRequest $request, UserService $service)
    {
        // dd($request->all());
        Log::info($request->all());
        $service->store($request->validated());
        return redirect()
            ->route('users.index')
            ->with('success', 'អ្នកប្រើប្រាស់ត្រូវបានបង្កើតដោយជោគជ័យ');
    }
    public function edit(User $user, OrganizationService $organizationService)
    {
        $organizations = $organizationService->getActive();
        return view(
            'users.edit',
            compact('user', 'organizations')
        );
    }
    public function update(UpdateUserRequest $request, User $user, UserService $service)
    {
        // Log::info('User update started.');
        $service->update(
            $user,
            $request->validated()
        );

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'អ្នកប្រើប្រាស់ត្រូវបានកែប្រែដោយជោគជ័យ'
            );
    }
    public function changePassword()
    {
        $user = auth()->user();

        return view(
            'users.change-password',
            compact('user')
        );
    }

    public function updatePassword(ChangePasswordRequest $request, UserService $service)
    {
        $service->changePassword(
            auth()->user(),
            $request->validated()
        );

        return redirect()
            ->route('dashboard')
            ->with('success', 'ពាក្យសម្ងាត់ត្រូវបានផ្លាស់ប្ដូរដោយជោគជ័យ');
    }

    public function profile()
    {
        $user = auth()->user()->load([
            'organization',
            'department',
        ]);

        return view(
            'users.profile',
            compact('user')
        );
    }
    public function getOffices(
        $departmentId,
        OfficeService $officeService
    ) {
        return response()->json(
            $officeService->getByDepartment($departmentId)
        );
    }
}

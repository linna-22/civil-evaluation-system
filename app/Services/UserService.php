<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationPeriodUser;
use Illuminate\Support\Facades\DB;

class UserService
{
    private EvaluationPeriodService $evaluationPeriodService;

    public function __construct(EvaluationPeriodService $evaluationPeriodService)
    {
        $this->evaluationPeriodService = $evaluationPeriodService;
    }
    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([

                'organization_id' => $data['organization_id'],
                'department_id' => $data['department_id'],
                'office_id' => $data['office_id'],
                'name_kh' => $data['name_kh'],
                'name_en' => $data['name_en'],
                'username' => $data['username'],
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'position' => $data['position'],
                'id_code' => $data['id_code'],
                'is_leader' => $data['is_leader'],
                'password' => $data['password'],
                'role' => $data['role'],
                'status' => $data['status'],
                'created_by' => auth()->id(),
            ]);

            // ==========================================
            // Assign to Open Evaluation Period
            // ==========================================

            if ($user->status === 'active') {

                $this->evaluationPeriodService
                    ->assignUserToOpenPeriods($user);
            }

            return $user->refresh();
        });
    }

    public function getData(Request $request)
    {
        $query = User::with([
            'organization',
            'department',
        ]);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('id_code', 'like', "%{$search}%")
                    ->orWhere('name_kh', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%");

            });

        }

        return $query
            ->orderBy('user_id', 'asc')
            ->paginate($request->get('per_page', 5));
    }

    public function find(User $user): User
    {
        return $user;
    }
    public function update(User $user, array $data): User {
        // Keep the old status before updating
        $oldStatus = $user->status;
        return DB::transaction(function () use ($user, $data, $oldStatus) {
            // ==========================================
            // Update User
            // ==========================================
            $user->update([
                'organization_id' => $data['organization_id'],
                'department_id' => $data['department_id'],
                'office_id' => $data['office_id'],
                'name_kh' => $data['name_kh'],
                'name_en' => $data['name_en'],
                'username' => $data['username'],
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'position' => $data['position'],
                'id_code' => $data['id_code'],
                'is_leader' => $data['is_leader'],
                'role' => $data['role'],
                'status' => $data['status'],
                'updated_by' => auth()->id(),
            ]);

            // ==========================================
            // Inactive → Active
            // ==========================================

            if ($oldStatus === 'inactive' && $user->status === 'active') 
            {
                $this->evaluationPeriodService->assignUserToOpenPeriods($user);
            }

            return $user->refresh();

        });
    }
    public function changePassword(User $user, array $data): void
    {
        // Verify current password
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវ។',
            ]);
        }

        // Update password
        $user->update([
            'password' => $data['password'],
            'updated_by' => auth()->id(),
        ]);

        // Log activity (never log the password)
        Log::info('User password changed.', [
            'user_id' => $user->user_id,
            'username' => $user->username,
            'changed_by' => auth()->id(),
        ]);
    }

}
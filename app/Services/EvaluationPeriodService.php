<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationPeriodUser;
use App\Models\EvaluationDataEntryAssignment;
use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\EvaluationSummaryService;

class EvaluationPeriodService
{
    /**
     * Get evaluation periods for DataTable.
     */
    public function getData(Request $request)
    {
        $query = EvaluationPeriod::query()->orderBy('evaluation_period_id', 'desc');


        // ==========================================
        // Search
        // ==========================================

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_kh', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('year', 'like', "%{$search}%");
            });
        }

        // ==========================================
        // Order
        // ==========================================

        return $query
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(
                $request->get('per_page', 5)
            );
    }


    /**
     * Find an evaluation period.
     */
    public function find(EvaluationPeriod $evaluationPeriod): EvaluationPeriod
    {
        return $evaluationPeriod;
    }
    public function assignActiveUsers(
        EvaluationPeriod $evaluationPeriod
    ): void {

        $activeUserIds = User::query()
            ->where('status', 'active')
            ->where('role', '!=', 'super_admin')
            ->where('role', '!=', 'evaluation_admin')
            ->pluck('user_id');

        if ($activeUserIds->isEmpty()) {
            return;
        }

        $now = now();

        $participants = $activeUserIds
            ->map(function ($userId) use ($evaluationPeriod, $now) {

                return [
                    'evaluation_period_id'
                    => $evaluationPeriod->evaluation_period_id,

                    'user_id'
                    => $userId,

                    'created_at' => $now,

                    'updated_at' => $now,
                ];

            })
            ->toArray();

        EvaluationPeriodUser::insertOrIgnore(
            $participants
        );
    }
    public function assignUserToOpenPeriods(User $user): void
    {
        if ($user->status !== 'active') {
            return;
        }

        if (
            in_array($user->role, [
                'super_admin',
                'evaluation_admin',
            ], true)
        ) {
            return;
        }

        $openPeriods = EvaluationPeriod::query()
            ->where('status', 'open')
            ->whereHas('departments', function ($query) use ($user) {
                $query->where(
                    'department_id',
                    $user->department_id
                );
            })
            ->get();

        if ($openPeriods->isEmpty()) {
            return;
        }

        $now = now();

        $participants = $openPeriods
            ->map(function ($period) use ($user, $now) {
                return [
                    'evaluation_period_id'
                    => $period->evaluation_period_id,

                    'user_id' => $user->user_id,

                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->toArray();

        EvaluationPeriodUser::insertOrIgnore($participants);
    }
    /**
     * Get participating departments, their offices and existing data-entry assignments.
     */
    public function getDataEntryAssignmentData(EvaluationPeriod $evaluationPeriod): array
    {
        $evaluationPeriod->load([
            'departments.department.offices.users',
            'departments.department.users',
            'dataEntryAssignments.department',
            'dataEntryAssignments.office',
            'dataEntryAssignments.user',
        ]);

        $departments = $evaluationPeriod->departments
            ->map(fn($item) => $item->department)
            ->filter()
            ->values();

        return [
            'departments' => $departments,
            'assignments' => $evaluationPeriod->dataEntryAssignments,
        ];
    }

    /**
     * Save one assignment for a participating department or office.
     */
    public function saveDataEntryAssignment(
        EvaluationPeriod $evaluationPeriod,
        array $data
    ): EvaluationDataEntryAssignment {
        if ($evaluationPeriod->status === 'closed') {
            throw ValidationException::withMessages([
                'evaluation_period' => 'វគ្គវាយតម្លៃដែលបានបិទ មិនអាចកំណត់អ្នកបញ្ចូលទិន្នន័យបានទេ។',
            ]);
        }

        return DB::transaction(function () use ($evaluationPeriod, $data) {
            $participating = $evaluationPeriod->departments()
                ->where('department_id', $data['department_id'])
                ->exists();

            if (!$participating) {
                throw ValidationException::withMessages([
                    'department_id' => 'នាយកដ្ឋាននេះមិនបានចូលរួមក្នុងវគ្គវាយតម្លៃនេះទេ។',
                ]);
            }

            $officeId = $data['scope'] === 'office' ? ($data['office_id'] ?? null) : null;

            if ($data['scope'] === 'office') {
                if (!$officeId) {
                    throw ValidationException::withMessages([
                        'office_id' => 'សូមជ្រើសរើសការិយាល័យ។',
                    ]);
                }

                $officeBelongs = Office::query()
                    ->where('office_id', $officeId)
                    ->where('department_id', $data['department_id'])
                    ->where('status', 'active')
                    ->exists();

                if (!$officeBelongs) {
                    throw ValidationException::withMessages([
                        'office_id' => 'ការិយាល័យមិនស្ថិតក្រោមនាយកដ្ឋានដែលបានជ្រើសរើសទេ។',
                    ]);
                }
            }

            $user = User::query()
                ->where('user_id', $data['user_id'])
                ->where('status', 'active')
                ->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'user_id' => 'អ្នកបញ្ចូលទិន្នន័យត្រូវតែជាអ្នកប្រើប្រាស់ដែលសកម្ម។',
                ]);
            }

            if ((int) $user->department_id !== (int) $data['department_id']) {
                throw ValidationException::withMessages([
                    'user_id' => 'អ្នកបញ្ចូលទិន្នន័យត្រូវស្ថិតនៅក្នុងនាយកដ្ឋានដែលបានជ្រើសរើស។',
                ]);
            }

            if ($data['scope'] === 'office' && (int) $user->office_id !== (int) $officeId) {
                throw ValidationException::withMessages([
                    'user_id' => 'អ្នកបញ្ចូលទិន្នន័យត្រូវស្ថិតនៅក្នុងការិយាល័យដែលបានជ្រើសរើស។',
                ]);
            }

            // One assignment per scope. For department scope office_id is NULL,
            // so we explicitly query NULL rather than relying on the DB unique index.
            $assignment = EvaluationDataEntryAssignment::query()
                ->where('evaluation_period_id', $evaluationPeriod->evaluation_period_id)
                ->where('department_id', $data['department_id'])
                ->when(
                    $officeId === null,
                    fn($q) => $q->whereNull('office_id'),
                    fn($q) => $q->where('office_id', $officeId)
                )
                ->first();

            if ($assignment) {
                $assignment->update([
                    'scope' => $data['scope'],
                    'user_id' => $user->user_id,
                ]);

                return $assignment->refresh();
            }

            return EvaluationDataEntryAssignment::create([
                'evaluation_period_id' => $evaluationPeriod->evaluation_period_id,
                'department_id' => $data['department_id'],
                'office_id' => $officeId,
                'user_id' => $user->user_id,
                'scope' => $data['scope'],
            ]);
        });
    }

    /**
     * Delete one assignment belonging to the selected period.
     */
    public function deleteDataEntryAssignment(
        EvaluationPeriod $evaluationPeriod,
        EvaluationDataEntryAssignment $assignment
    ): void {
        if ($evaluationPeriod->status === 'closed') {
            throw ValidationException::withMessages([
                'evaluation_period' => 'វគ្គវាយតម្លៃដែលបានបិទ មិនអាចកែប្រែការកំណត់បានទេ។',
            ]);
        }

        if ((int) $assignment->evaluation_period_id !== (int) $evaluationPeriod->evaluation_period_id) {
            abort(404);
        }

        $assignment->delete();
    }

    /**
     * Create an evaluation period
     * and automatically assign active users.
     */
    // public function store(array $data): EvaluationPeriod
    // {
    //     return DB::transaction(function () use ($data) {

    //         // ==========================================
    //         // Prevent duplicate month / year
    //         // ==========================================

    //         $exists = EvaluationPeriod::query()
    //             ->where('month', $data['month'])
    //             ->where('year', $data['year'])
    //             ->exists();

    //         if ($exists) {

    //             throw ValidationException::withMessages([
    //                 'month' =>
    //                     'វគ្គវាយតម្លៃសម្រាប់ខែ និងឆ្នាំនេះមានរួចហើយ។',
    //             ]);

    //         }


    //         // ==========================================
    //         // Create Evaluation Period
    //         // ==========================================

    //         $evaluationPeriod = EvaluationPeriod::create([

    //             'name_kh' => $data['name_kh'],
    //             'name_en' => $data['name_en'],
    //             'month' => $data['month'],
    //             'year' => $data['year'],
    //             'start_date' => $data['start_date'],
    //             'end_date' => $data['end_date'],
    //             'status' => 'open',
    //             'created_by' => auth()->id(),
    //             'open_at' => now(),
    //         ]);


    //         // ==========================================
    //         // Get Active Users
    //         // ==========================================

    //         $activeUsers = User::query()
    //             ->where('status', 'active')
    //             ->pluck('user_id');


    //         // ==========================================
    //         // Assign Active Users
    //         // ==========================================

    //         $participants = $activeUsers
    //             ->map(function ($userId) use ($evaluationPeriod) {

    //                 return [
    //                     'evaluation_period_id'
    //                     => $evaluationPeriod->evaluation_period_id,
    //                     'user_id'
    //                     => $userId,
    //                     'created_at'
    //                     => now(),
    //                     'updated_at'
    //                     => now(),
    //                 ];
    //             })
    //             ->toArray();

    //         if (!empty($participants)) {
    //             EvaluationPeriodUser::insert($participants);
    //         }

    //         return $evaluationPeriod->refresh();
    //     });
    // }
    public function store(array $data): EvaluationPeriod
    {
        return DB::transaction(function () use ($data) {

            // ==========================================
            // Prevent duplicate month / year
            // ==========================================

            $exists = EvaluationPeriod::query()
                ->where('month', $data['month'])
                ->where('year', $data['year'])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'month' =>
                        'វគ្គវាយតម្លៃសម្រាប់ខែ និងឆ្នាំនេះមានរួចហើយ។',
                ]);
            }

            // ==========================================
            // Create Evaluation Period
            // ==========================================

            $evaluationPeriod = EvaluationPeriod::create([
                'name_kh' => $data['name_kh'],
                'name_en' => $data['name_en'],
                'month' => $data['month'],
                'year' => $data['year'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => 'open',
                'created_by' => auth()->id(),
                'open_at' => now(),
            ]);

            // ==========================================
            // Save Participating Departments
            // ==========================================

            $departmentIds = collect($data['department_ids'])
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            foreach ($departmentIds as $departmentId) {
                $evaluationPeriod->departments()->create([
                    'department_id' => $departmentId,
                ]);
            }

            // ==========================================
            // Get Active Users From Participating
            // Departments
            // ==========================================

            $activeUsers = User::query()
                ->where('status', 'active')
                ->whereIn('department_id', $departmentIds)
                ->whereNotIn('role', [
                    'super_admin',
                    'evaluation_admin',
                ])
                ->get(['user_id']);

            // ==========================================
            // Add Users To Evaluation Period
            // ==========================================

            if ($activeUsers->isNotEmpty()) {

                $now = now();

                $participants = $activeUsers
                    ->map(function ($user) use ($evaluationPeriod, $now) {
                        return [
                            'evaluation_period_id'
                            => $evaluationPeriod->evaluation_period_id,

                            'user_id'
                            => $user->user_id,

                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    })
                    ->toArray();

                EvaluationPeriodUser::insertOrIgnore(
                    $participants
                );
            }

            return $evaluationPeriod->refresh();
        });
    }

    /**
     * Update an evaluation period.
     */
    public function update(EvaluationPeriod $evaluationPeriod, array $data): EvaluationPeriod
    {
        return DB::transaction(function () use ($evaluationPeriod, $data) {
            // ==========================================
            // Closed Period Protection
            // ==========================================

            if ($evaluationPeriod->status === 'closed') {

                throw ValidationException::withMessages([
                    'evaluation_period' =>
                        'វគ្គវាយតម្លៃដែលបានបិទ មិនអាចកែប្រែបានទេ។',
                ]);

            }


            // ==========================================
            // Check Duplicate Month / Year
            // ==========================================

            $exists = EvaluationPeriod::query()
                ->where('month', $data['month'])
                ->where('year', $data['year'])
                ->where(
                    'evaluation_period_id',
                    '!=',
                    $evaluationPeriod->evaluation_period_id
                )
                ->exists();

            if ($exists) {

                throw ValidationException::withMessages([
                    'month' =>
                        'វគ្គវាយតម្លៃសម្រាប់ខែ និងឆ្នាំនេះមានរួចហើយ។',
                ]);

            }


            // ==========================================
            // Update Evaluation Period
            // ==========================================

            $evaluationPeriod->update([
                'name_kh' => $data['name_kh'],
                'name_en' => $data['name_en'],
                'month' => $data['month'],
                'year' => $data['year'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);
            // ==========================================
            // Sync Participating Departments
            // ==========================================

            $departmentIds = collect($data['department_ids'])
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            $evaluationPeriod->departments()->delete();

            foreach ($departmentIds as $departmentId) {
                $evaluationPeriod->departments()->create([
                    'department_id' => $departmentId,
                ]);
            }

            // ==========================================
            // Sync Evaluation Period Users
            // ==========================================

            $activeUsers = User::query()
                ->where('status', 'active')
                ->whereIn('department_id', $departmentIds)
                ->whereNotIn('role', [
                    'super_admin',
                    'evaluation_admin',
                ])
                ->pluck('user_id');

            $evaluationPeriod->periodUsers()->delete();

            if ($activeUsers->isNotEmpty()) {

                $now = now();

                $participants = $activeUsers
                    ->map(function ($userId) use ($evaluationPeriod, $now) {
                        return [
                            'evaluation_period_id'
                            => $evaluationPeriod->evaluation_period_id,

                            'user_id' => $userId,

                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    })
                    ->toArray();

                EvaluationPeriodUser::insertOrIgnore($participants);
            }

            return $evaluationPeriod->refresh();

        });
    }

    /**
     * Close an evaluation period.
     */
    // public function close(EvaluationPeriod $evaluationPeriod, EvaluationSummaryService $summaryService): EvaluationPeriod 
    // {
    //     return DB::transaction(function () use ($evaluationPeriod, $summaryService) {

    //         // ==========================================
    //         // Check Already Closed
    //         // ==========================================

    //         if ($evaluationPeriod->status === 'closed') {

    //             throw ValidationException::withMessages([
    //                 'evaluation_period' =>
    //                     'វគ្គវាយតម្លៃនេះបានបិទរួចហើយ។',
    //             ]);

    //         }


    //         // ==========================================
    //         // Calculate All Evaluation Summaries
    //         // ==========================================

    //         $summaryService->calculate(
    //             $evaluationPeriod
    //         );


    //         // ==========================================
    //         // Close Evaluation Period
    //         // ==========================================

    //         $evaluationPeriod->update([

    //             'status' => 'closed',

    //             'closed_by' => auth()->id(),

    //             'close_type' => 'manual',

    //             'close_at' => now(),

    //         ]);


    //         // ==========================================
    //         // Return Updated Period
    //         // ==========================================

    //         return $evaluationPeriod->refresh();

    //     });
    // }
    /**
     * Close an evaluation period.
     */
    public function close(EvaluationPeriod $evaluationPeriod, EvaluationSummaryService $summaryService): EvaluationPeriod
    {
        return DB::transaction(function () use ($evaluationPeriod, $summaryService) {
            // ==========================================
            // Check Already Closed
            // ==========================================
            if ($evaluationPeriod->status === 'closed') {
                throw ValidationException::withMessages([
                    'evaluation_period' =>
                        'វគ្គវាយតម្លៃនេះបានបិទរួចហើយ។',
                ]);
            }
            // ==========================================
            // Check All Evaluations Completed
            // ==========================================
            $this->checkAllEvaluationsCompleted(
                $evaluationPeriod
            );
            // ==========================================
            // Calculate All Evaluation Summaries
            // ==========================================
            $summaryService->calculate(
                $evaluationPeriod
            );
            // ==========================================
            // Close Evaluation Period
            // ==========================================
            $evaluationPeriod->update([
                'status' => 'closed',
                'closed_by' => auth()->id(),
                'close_type' => 'manual',
                'close_at' => now(),
            ]);
            // ==========================================
            // Return Updated Period
            // ==========================================
            return $evaluationPeriod->refresh();
        });
    }
    /**
     * Check whether all required evaluations
     * have been completed before closing.
     */
    private function checkAllEvaluationsCompleted(
        EvaluationPeriod $evaluationPeriod
    ): void {

        // ==========================================
        // Evaluation period must still be open
        // ==========================================

        if ($evaluationPeriod->status !== 'open') {
            throw ValidationException::withMessages([
                'evaluation_period' =>
                    'វគ្គវាយតម្លៃនេះមិនទាន់បើក ឬបានបិទរួចហើយ។',
            ]);
        }


        // ==========================================
        // Get Evaluation Participants
        // ==========================================

        $periodUsers = EvaluationPeriodUser::query()
            ->with('user')
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->get();


        if ($periodUsers->isEmpty()) {
            throw ValidationException::withMessages([
                'evaluation_period' =>
                    'មិនមានមន្ត្រីក្នុងវគ្គវាយតម្លៃនេះទេ។',
            ]);
        }


        // ==========================================
        // Check Every Employee
        // ==========================================

        $missing = [];

        foreach ($periodUsers as $periodUser) {

            $user = $periodUser->user;

            // ------------------------------------------
            // Skip users who are not evaluation targets
            // ------------------------------------------
            //
            // department_admin is a result viewer/admin,
            // not a peer-evaluation target.
            //
            // Existing leader exclusion is preserved.
            //

            if (
                !$user ||
                $user->status !== 'active' ||
                $user->is_leader ||
                $user->role === 'department_admin'
            ) {
                continue;
            }


            // ==========================================
            // 1. Work Performance
            // ==========================================

            $workCompleted = Evaluation::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->where(
                    'evaluatee_id',
                    $user->user_id
                )
                ->where(
                    'evaluation_type',
                    'work_performance'
                )
                ->where(
                    'evaluation_status',
                    'submitted'
                )
                ->exists();


            // ==========================================
            // 2. Attendance
            // ==========================================

            $attendanceCompleted = Evaluation::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->where(
                    'evaluatee_id',
                    $user->user_id
                )
                ->where(
                    'evaluation_type',
                    'attendance'
                )
                ->where(
                    'evaluation_status',
                    'submitted'
                )
                ->exists();


            // ==========================================
            // 3. Behavior - Peer to Peer
            // ==========================================
            //
            // Every eligible peer in the same department
            // must evaluate this user.
            //
            // Rules match BehaviorEvaluationService:
            // - same organization
            // - same department
            // - active
            // - in this evaluation period
            // - department_admin excluded
            // - self excluded
            // - office does NOT matter
            //

            $expectedBehaviorCount = EvaluationPeriodUser::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->whereHas('user', function ($query) use ($user) {

                    $query
                        ->where('status', 'active')
                        ->where('role', '!=', 'department_admin')
                        ->where(
                            'user_id',
                            '!=',
                            $user->user_id
                        )
                        ->where(
                            'organization_id',
                            $user->organization_id
                        )
                        ->where(
                            'department_id',
                            $user->department_id
                        );

                    // Intentionally no office_id condition.
                })
                ->count();


            $behaviorCount = Evaluation::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->where(
                    'evaluatee_id',
                    $user->user_id
                )
                ->where(
                    'evaluation_type',
                    'behavior'
                )
                ->where(
                    'evaluation_status',
                    'submitted'
                )
                ->count();


            // ==========================================
            // Check Missing Evaluations
            // ==========================================

            $missingItems = [];

            if (!$workCompleted) {
                $missingItems[] = 'Work Performance';
            }

            if (!$attendanceCompleted) {
                $missingItems[] = 'Attendance';
            }

            // If there are no eligible peers, behavior is
            // not required for this user.
            if (
                $expectedBehaviorCount > 0 &&
                $behaviorCount < $expectedBehaviorCount
            ) {
                $missingItems[] =
                    "Peer Behavior ({$behaviorCount}/{$expectedBehaviorCount})";
            }


            // ==========================================
            // Store Missing Employee
            // ==========================================

            if (!empty($missingItems)) {

                $missing[] = [
                    'user' =>
                        $user->name_kh ?? $user->name_en,
                    'items' => $missingItems,
                ];
            }
        }


        // ==========================================
        // Cannot Close
        // ==========================================

        if (!empty($missing)) {

            $messages = collect($missing)
                ->map(function ($item) {

                    return $item['user']
                        . ': '
                        . implode(', ', $item['items']);

                })
                ->implode('; ');


            throw ValidationException::withMessages([
                'evaluation_period' =>
                    'មិនអាចបិទវគ្គវាយតម្លៃបានទេ។ '
                    . 'មន្ត្រីមួយចំនួនមិនទាន់បំពេញការវាយតម្លៃ៖ '
                    . $messages,
            ]);
        }
    }

}
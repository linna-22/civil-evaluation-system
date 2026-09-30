<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EvaluationDataEntryAssignment;
use App\Models\EvaluationPeriod;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EvaluationDataEntryAssignmentController extends Controller
{
    /**
     * Show data-entry assignment form.
     */
    public function edit(EvaluationPeriod $evaluationPeriod)
    {
        $evaluationPeriod->load([
            'departments.department',
            'dataEntryAssignments.user',
            'dataEntryAssignments.office',
        ]);

        $departmentIds = $evaluationPeriod->departments
            ->pluck('department_id')
            ->values();

        $departments = Department::query()
            ->whereIn('department_id', $departmentIds)
            ->with([
                'offices' => fn ($query) => $query
                    ->where('status', 'active')
                    ->with([
                        'users' => fn ($userQuery) => $userQuery
                            ->where('status', 'active')
                            ->orderBy('name_kh'),
                    ]),

                'users' => fn ($query) => $query
                    ->where('status', 'active')
                    ->orderBy('name_kh'),
            ])
            ->orderBy('department_name_kh')
            ->get();

        $assignments = $evaluationPeriod->dataEntryAssignments
            ->keyBy(function ($assignment) {
                return $assignment->office_id
                    ? "office:{$assignment->department_id}:{$assignment->office_id}"
                    : "department:{$assignment->department_id}";
            });

        return view(
            'evaluation-data-entry-assignments.edit',
            compact(
                'evaluationPeriod',
                'departments',
                'assignments'
            )
        );
    }

    /**
     * Save all data-entry assignments.
     */
    public function update(
        Request $request,
        EvaluationPeriod $evaluationPeriod
    ) {
        if ($evaluationPeriod->status !== 'open') {
            return back()->with(
                'error',
                'ការវាយតម្លៃដែលបានបិទ មិនអាចកែការកំណត់អ្នកបញ្ចូលទិន្នន័យបានទេ។'
            );
        }

        $validated = $request->validate([
            'assignments' => ['required', 'array'],
            'assignments.*.scope' => [
                'required',
                'in:department,office',
            ],
            'assignments.*.department_id' => [
                'required',
                'integer',
            ],
            'assignments.*.office_id' => [
                'nullable',
                'integer',
            ],
            'assignments.*.user_id' => [
                'required',
                'integer',
            ],
        ]);

        $participatingDepartmentIds = $evaluationPeriod
            ->departments()
            ->pluck('department_id')
            ->all();

        DB::transaction(function () use (
            $validated,
            $evaluationPeriod,
            $participatingDepartmentIds
        ) {
            $rows = [];
            $seenScopes = [];

            foreach ($validated['assignments'] as $row) {
                $scope = $row['scope'];
                $departmentId = (int) $row['department_id'];

                $officeId = isset($row['office_id'])
                    && $row['office_id'] !== null
                    ? (int) $row['office_id']
                    : null;

                $userId = (int) $row['user_id'];

                /*
                 * The department must participate
                 * in this evaluation period.
                 */
                if (! in_array(
                    $departmentId,
                    $participatingDepartmentIds,
                    true
                )) {
                    throw ValidationException::withMessages([
                        'assignments' =>
                            'មាននាយកដ្ឋានមួយដែលមិនបានចូលរួមក្នុងវគ្គវាយតម្លៃនេះ។',
                    ]);
                }

                /*
                 * Department-level data entry.
                 *
                 * One user handles department-level
                 * employees such as អនុប្រធាននាយកដ្ឋាន.
                 */
                if ($scope === 'department') {
                    if ($officeId !== null) {
                        throw ValidationException::withMessages([
                            'assignments' =>
                                'ការកំណត់កម្រិតនាយកដ្ឋានមិនត្រូវការិយាល័យទេ។',
                        ]);
                    }

                    $scopeKey = "department:{$departmentId}";

                    $user = User::query()
                        ->where('user_id', $userId)
                        ->where('department_id', $departmentId)
                        ->where('status', 'active')
                        ->first();

                    if (! $user) {
                        throw ValidationException::withMessages([
                            'assignments' =>
                                'អ្នកបញ្ចូលទិន្នន័យកម្រិតនាយកដ្ឋាន ត្រូវជាអ្នកប្រើប្រាស់សកម្មក្នុងនាយកដ្ឋាននោះ។',
                        ]);
                    }
                }

                /*
                 * Office-level data entry.
                 *
                 * One user is responsible for
                 * one office.
                 */
                else {
                    if ($officeId === null) {
                        throw ValidationException::withMessages([
                            'assignments' =>
                                'ការកំណត់កម្រិតការិយាល័យ ត្រូវជ្រើសរើសការិយាល័យ។',
                        ]);
                    }

                    $scopeKey = "office:{$departmentId}:{$officeId}";

                    $officeExists = Office::query()
                        ->where('office_id', $officeId)
                        ->where('department_id', $departmentId)
                        ->where('status', 'active')
                        ->exists();

                    $user = User::query()
                        ->where('user_id', $userId)
                        ->where('department_id', $departmentId)
                        ->where('office_id', $officeId)
                        ->where('status', 'active')
                        ->first();

                    if (! $officeExists || ! $user) {
                        throw ValidationException::withMessages([
                            'assignments' =>
                                'អ្នកបញ្ចូលទិន្នន័យការិយាល័យ ត្រូវជាអ្នកប្រើប្រាស់សកម្មក្នុងការិយាល័យដែលបានជ្រើសរើស។',
                        ]);
                    }
                }

                /*
                 * Prevent duplicate assignments
                 * for the same scope.
                 */
                if (isset($seenScopes[$scopeKey])) {
                    throw ValidationException::withMessages([
                        'assignments' =>
                            'មានការកំណត់ទិន្នន័យស្ទួនសម្រាប់វិសាលភាពមួយ។',
                    ]);
                }

                $seenScopes[$scopeKey] = true;

                $rows[] = [
                    'evaluation_period_id' =>
                        $evaluationPeriod->evaluation_period_id,

                    'department_id' => $departmentId,
                    'office_id' => $officeId,
                    'user_id' => $userId,
                    'scope' => $scope,

                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Every participating department must have one department-level
            // assignment, and every active office must have one office-level
            // assignment. The assigned user may have ANY role, as long as
            // they are active and belong to the required scope.
            foreach ($participatingDepartmentIds as $departmentId) {
                $departmentKey = "department:{$departmentId}";

                if (!isset($seenScopes[$departmentKey])) {
                    throw ValidationException::withMessages([
                        'assignments' =>
                            'នាយកដ្ឋាននីមួយៗត្រូវមានអ្នកបញ្ចូលទិន្នន័យកម្រិតនាយកដ្ឋាន។',
                    ]);
                }

                $officeIds = Office::query()
                    ->where('department_id', $departmentId)
                    ->where('status', 'active')
                    ->pluck('office_id');

                foreach ($officeIds as $officeId) {
                    $officeKey = "office:{$departmentId}:{$officeId}";

                    if (!isset($seenScopes[$officeKey])) {
                        throw ValidationException::withMessages([
                            'assignments' =>
                                'ការិយាល័យសកម្មនីមួយៗត្រូវមានអ្នកបញ្ចូលទិន្នន័យ។',
                        ]);
                    }
                }
            }

            /*
             * Replace the assignments for this
             * evaluation period with the submitted set.
             */
            EvaluationDataEntryAssignment::where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )->delete();

            if (! empty($rows)) {
                EvaluationDataEntryAssignment::insert($rows);
            }
        });

        return redirect()
            ->route(
                'evaluation-periods.data-entry.edit',
                $evaluationPeriod
            )
            ->with(
                'success',
                'ការកំណត់អ្នកបញ្ចូលទិន្នន័យត្រូវបានរក្សាទុកដោយជោគជ័យ។'
            );
    }
}

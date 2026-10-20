<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentsImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;

    public function collection(Collection $collection): void
    {
        // Remove completely empty rows.
        $rows = $collection->filter(function ($row) {
            return collect($row)->contains(function ($value) {
                return $value !== null && trim((string) $value) !== '';
            });
        })->values();

        if ($rows->isEmpty()) {
            throw ValidationException::withMessages([
                'file' => 'The Excel file contains no department records.',
            ]);
        }

        $departments = [];
        $seenKeys = [];
        $errors = [];
        $organizationCodes = [];

        // Normalize and validate every row before inserting.
        foreach ($rows as $index => $row) {
            $data = $row->toArray();

            $data['department_code'] = isset($data['department_code'])
                ? trim((string) $data['department_code'])
                : null;

            $data['department_name_kh'] = isset($data['department_name_kh'])
                ? trim((string) $data['department_name_kh'])
                : null;

            $data['department_name_en'] = isset($data['department_name_en'])
                ? trim((string) $data['department_name_en'])
                : null;

            $data['org_code'] = isset($data['org_code'])
                ? trim((string) $data['org_code'])
                : null;

            $data['status'] = isset($data['status'])
                ? strtolower(trim((string) $data['status']))
                : 'active';

            $validator = Validator::make($data, [
                'department_code' => [
                    'required',
                    'string',
                    'max:20',
                ],
                'department_name_kh' => [
                    'required',
                    'string',
                    'max:150',
                ],
                'department_name_en' => [
                    'nullable',
                    'string',
                    'max:150',
                ],
                'org_code' => [
                    'required',
                    'string',
                    'max:20',
                ],
                'status' => [
                    'required',
                    'in:active,inactive',
                ],
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $errors[] = 'Excel row ' . ($index + 2) . ': ' . $message;
                }

                continue;
            }

            // Track organization codes for database lookup.
            $organizationCodes[] = $data['org_code'];

            // Match the database's composite unique constraint.
            $key = $data['org_code'] . '|' . $data['department_code'];

            if (isset($seenKeys[$key])) {
                $errors[] = 'Excel row ' . ($index + 2)
                    . ': Duplicate department code '
                    . $data['department_code']
                    . ' for organization '
                    . $data['org_code'] . '.';

                continue;
            }

            $seenKeys[$key] = true;

            $departments[] = [
                'department_code' => $data['department_code'],
                'department_name_kh' => $data['department_name_kh'],
                'department_name_en' => $data['department_name_en'] ?: null,
                'org_code' => $data['org_code'],
                'status' => $data['status'],
                'excel_row' => $index + 2,
            ];
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        // Resolve organization codes to database IDs.
        $organizations = DB::table('organizations')
            ->whereIn('org_code', array_unique($organizationCodes))
            ->pluck('organization_id', 'org_code')
            ->all();

        foreach ($departments as $department) {
            $code = $department['org_code'];

            if (!isset($organizations[$code])) {
                $errors[] = 'Excel row ' . $department['excel_row']
                    . ': Organization code ' . $code
                    . ' does not exist in the database.';
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        // Reject departments that already exist for the same organization.
        foreach ($departments as $department) {
            $organizationId = $organizations[$department['org_code']];

            $exists = DB::table('departments')
                ->where('organization_id', $organizationId)
                ->where('department_code', $department['department_code'])
                ->exists();

            if ($exists) {
                $errors[] = 'Excel row ' . $department['excel_row']
                    . ': Department code '
                    . $department['department_code']
                    . ' already exists for organization '
                    . $department['org_code'] . '.';
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        $now = now();
        $userId = auth()->id();

        $insertRows = [];

        foreach ($departments as $department) {
            $insertRows[] = [
                'organization_id' => $organizations[$department['org_code']],
                'department_code' => $department['department_code'],
                'department_name_kh' => $department['department_name_kh'],
                'department_name_en' => $department['department_name_en'],
                'desc' => null,
                'status' => $department['status'],
                'created_by' => $userId,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
        }

        // Insert all departments atomically.
        DB::transaction(function () use ($insertRows) {
            foreach (array_chunk($insertRows, 500) as $chunk) {
                DB::table('departments')->insert($chunk);
            }
        });

        $this->importedCount = count($insertRows);
    }
}

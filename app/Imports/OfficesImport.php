<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OfficesImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;

    public function collection(Collection $collection): void
    {
        $rows = $collection->filter(function ($row) {
            return collect($row)->contains(
                fn ($value) => $value !== null && trim((string) $value) !== ''
            );
        })->values();

        if ($rows->isEmpty()) {
            throw ValidationException::withMessages([
                'file' => 'The Excel file contains no office records.',
            ]);
        }

        $offices = [];
        $seenCodes = [];
        $departmentCodes = [];
        $errors = [];

        foreach ($rows as $index => $row) {
            $data = $row->toArray();

            $data['office_code'] = isset($data['office_code'])
                ? strtolower(trim((string) $data['office_code']))
                : null;

            $data['office_name_kh'] = isset($data['office_name_kh'])
                ? trim((string) $data['office_name_kh'])
                : null;

            $data['office_name_en'] = isset($data['office_name_en'])
                ? trim((string) $data['office_name_en'])
                : null;

            $departmentCode = isset($data['department_code'])
                ? trim((string) $data['department_code'])
                : '';

            $data['department_code'] = in_array(
                strtolower($departmentCode),
                ['', 'null', 'n/a', 'na'],
                true
            ) ? null : strtolower($departmentCode);

            $data['status'] = isset($data['status'])
                && trim((string) $data['status']) !== ''
                    ? strtolower(trim((string) $data['status']))
                    : 'active';

            $validator = Validator::make($data, [
                'office_code' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^off[0-9]{4,}$/',
                ],
                'office_name_kh' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'office_name_en' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'department_code' => [
                    'nullable',
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
                    $errors[] = 'Excel row ' . ($index + 2)
                        . ': ' . $message;
                }

                continue;
            }

            $code = $data['office_code'];

            if (isset($seenCodes[$code])) {
                $errors[] = 'Excel row ' . ($index + 2)
                    . ': Duplicate office code ' . $code
                    . ' in this file.';

                continue;
            }

            $seenCodes[$code] = true;

            if ($data['department_code'] !== null) {
                $departmentCodes[] = $data['department_code'];
            }

            $offices[] = [
                'office_code' => $code,
                'office_name_kh' => $data['office_name_kh'],
                'office_name_en' => $data['office_name_en'],
                'department_code' => $data['department_code'],
                'status' => $data['status'],
                'excel_row' => $index + 2,
            ];
        }

        if ($errors) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        // Find departments by department_code.
        $departments = empty($departmentCodes)
            ? collect()
            : DB::table('departments')
                ->whereIn('department_code', array_unique($departmentCodes))
                ->whereNull('deleted_at')
                ->get([
                    'department_id',
                    'department_code',
                ])
                ->groupBy('department_code');

        // Validate department codes.
        foreach ($offices as $office) {
            if ($office['department_code'] === null) {
                continue;
            }

            $matches = $departments->get(
                $office['department_code'],
                collect()
            );

            if ($matches->isEmpty()) {
                $errors[] = 'Excel row ' . $office['excel_row']
                    . ': Department code '
                    . $office['department_code']
                    . ' does not exist in the database.';

                continue;
            }

            if ($matches->count() > 1) {
                $errors[] = 'Excel row ' . $office['excel_row']
                    . ': Department code '
                    . $office['department_code']
                    . ' matches multiple departments. '
                    . 'Please ensure department codes are unique.';
            }
        }

        // Check whether office codes already exist.
        $existingCodes = DB::table('offices')
            ->whereIn('office_code', array_keys($seenCodes))
            ->whereNull('deleted_at')
            ->pluck('office_code')
            ->all();

        foreach ($existingCodes as $code) {
            $errors[] = 'Office code ' . $code
                . ' already exists in the database.';
        }

        if ($errors) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        $now = now();
        $userId = auth()->id();
        $insertRows = [];

        foreach ($offices as $office) {
            $departmentId = null;

            if ($office['department_code'] !== null) {
                $departmentId = $departments
                    ->get($office['department_code'])
                    ->first()
                    ->department_id;
            }

            $insertRows[] = [
                'department_id' => $departmentId,
                'office_code' => $office['office_code'],
                'office_name_kh' => $office['office_name_kh'],
                'office_name_en' => $office['office_name_en'],
                'desc' => null,
                'status' => $office['status'],
                'created_by' => $userId,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
        }

        DB::transaction(function () use ($insertRows) {
            foreach (array_chunk($insertRows, 500) as $chunk) {
                DB::table('offices')->insert($chunk);
            }
        });

        $this->importedCount = count($insertRows);
    }
}

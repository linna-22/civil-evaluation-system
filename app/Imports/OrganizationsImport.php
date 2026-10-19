<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrganizationsImport implements ToCollection, WithHeadingRow
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
                'file' => 'The Excel file contains no organization records.',
            ]);
        }

        $organizations = [];
        $seenCodes = [];
        $errors = [];

        // Validate every row before inserting anything.
        foreach ($rows as $index => $row) {
            $data = $row->toArray();

            // Normalize codes to strings, preserving their textual format.
            $code = $data['org_code'] ?? null;

            if ($code !== null && is_numeric($code)) {
                $code = sprintf('%.0f', (float) $code);
            }

            $data['org_code'] = $code !== null
                ? trim((string) $code)
                : null;

            $data['org_name_kh'] = isset($data['org_name_kh'])
                ? trim((string) $data['org_name_kh'])
                : null;

            $data['org_name_en'] = isset($data['org_name_en'])
                ? trim((string) $data['org_name_en'])
                : null;

            $validator = Validator::make(
                $data,
                [
                    'org_code' => [
                        'required',
                        'string',
                        'max:20',
                        'regex:/^org[0-9]{4}$/',
                    ],
                    'org_name_kh' => [
                        'required',
                        'string',
                        'max:150',
                    ],
                    'org_name_en' => [
                        'nullable',
                        'string',
                        'max:150',
                    ],
                ],
                [
                    'org_code.regex' => 'The organization code must contain exactly 10 digits.',
                    'org_code.max' => 'The organization code must not exceed 20 characters.',
                ]
            );

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $errors[] = 'Excel row ' . ($index + 2) . ': ' . $message;
                }

                continue;
            }

            $code = $data['org_code'];

            // Reject duplicate codes within the uploaded file.
            if (isset($seenCodes[$code])) {
                $errors[] = 'Excel row ' . ($index + 2)
                    . ': Duplicate organization code ' . $code . '.';

                continue;
            }

            $seenCodes[$code] = true;

            $organizations[] = [
                'org_code' => $code,
                'org_name_kh' => $data['org_name_kh'],
                'org_name_en' => $data['org_name_en'] ?: null,
                'desc' => null,
                'status' => 'active',
                'created_by' => auth()->id(),
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Do not import anything if the Excel file contains invalid rows.
        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        // Reject codes already present in the database.
        $existingCodes = DB::table('organizations')
            ->whereIn('org_code', array_keys($seenCodes))
            ->pluck('org_code')
            ->map(fn($code) => (string) $code)
            ->all();

        if (!empty($existingCodes)) {
            throw ValidationException::withMessages([
                'file' => [
                    'These organization codes already exist in the database: '
                    . implode(', ', $existingCodes),
                ],
            ]);
        }

        // Insert the entire batch atomically.
        DB::transaction(function () use ($organizations) {
            DB::table('organizations')->insert($organizations);
        });

        $this->importedCount = count($organizations);
    }
}

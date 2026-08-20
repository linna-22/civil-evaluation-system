<?php

namespace App\Services;

use App\Helpers\KhmerHelper;
use PhpOffice\PhpWord\TemplateProcessor;

class WordExportService
{
    public function download(array $data)
    {
        $organization = $data['organization'];
        $filters = $data['filters'];
        $department = $data['department'];
        $leader = $data['leader'];
        $reportDate = $data['reportDate'];
        $evaluations = $data['evaluations'];
        $user = $data['user'];
        $templatePath = resource_path('templates/evaluation-template.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Word template not found.');
        }

        $template = new TemplateProcessor($templatePath);
        $template->setValue(
            'organization',
            $organization?->org_name_kh ?? 'គ្រប់អង្គភាព'
        );

        $template->setValue(
            'month',
            KhmerHelper::month(
                $filters['month']
            )
        );

        $template->setValue(
            'year',
            KhmerHelper::number(
                $filters['year']
            )
        );
        $template->cloneRow('no', $evaluations->count());
        foreach ($evaluations as $index => $evaluation) {

            $row = $index + 1;

            $template->setValue("no#{$row}", $row);

            $template->setValue(
                "employee_name#{$row}",
                $evaluation->user->name_kh
            );

            $template->setValue(
                "department#{$row}",
                $evaluation->user->department->department_name_kh ?? '-'
            );

            $template->setValue(
                "position#{$row}",
                $evaluation->user->position
            );

            $template->setValue(
                "score#{$row}",
                number_format($evaluation->total_score, 2)
            );

            $template->setValue(
                "submitted_at#{$row}",
                \App\Helpers\DateHelper::khmerDateTime($evaluation->submitted_at)
            );
        }
        $template->setValue(
            'report_date',
            KhmerHelper::lunarDate($reportDate)
            . PHP_EOL .
            'រាជធានីភ្នំពេញ '
            . KhmerHelper::fullDate($reportDate)
        );

        $template->setValue(
            'leader_name',
            $leader?->name_kh ?? '................................'
        );

        $fileName = 'Evaluation_Report_' . now()->format('Ymd_His') . '.docx';

        $savePath = storage_path('app/temp/' . $fileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $template->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }
}
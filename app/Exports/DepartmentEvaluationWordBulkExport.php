<?php

namespace App\Exports;

use App\Helpers\KhmerHelper;
use Illuminate\Support\Collection;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\Language;

class DepartmentEvaluationWordBulkExport
{
    public function __construct(
        protected $evaluationPeriod,
        protected Collection $results,
        protected $departmentAdmin
    ) {
    }

    public function download()
    {
        $phpWord = new PhpWord();

        /*
        |--------------------------------------------------------------------------
        | Document settings
        |--------------------------------------------------------------------------
        */

        $phpWord->getSettings()->setThemeFontLang(
            new Language('km-KH')
        );

        $section = $phpWord->addSection([
            'pageSizeW' => 11906,
            'pageSizeH' => 16838,
            'marginTop' => 850,
            'marginBottom' => 850,
            'marginLeft' => 850,
            'marginRight' => 850,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Fonts
        |--------------------------------------------------------------------------
        */

        $moulFont = [
            'name' => 'Moul',
            'size' => 12,
            'bold' => false,
        ];

        $siemreapFont = [
            'name' => 'Siemreap',
            'size' => 10,
        ];

        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        $headerTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 0,
        ]);

        $headerTable->addRow();

        /*
        |--------------------------------------------------------------------------
        | Left header
        |--------------------------------------------------------------------------
        */

        $leftCell = $headerTable->addCell(
            4500,
            [
                'valign' => 'top',
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
            ]
        );

        $logoPath = public_path('images/logo.png');

        if (file_exists($logoPath)) {
            $leftCell->addImage(
                $logoPath,
                [
                    'width' => 70,
                    'height' => 70,
                    'alignment' => Jc::CENTER,
                ]
            );
        }

        $leftCell->addText(
            'ក្រសួងការងារនិងបណ្តុះបណ្តាលវិជ្ជាជីវៈ',
            $moulFont,
            [
                'alignment' => Jc::CENTER,
                'spaceAfter' => 0,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Right header
        |--------------------------------------------------------------------------
        */

        $rightCell = $headerTable->addCell(
            4500,
            [
                'valign' => 'top',
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
            ]
        );

        $rightCell->addText(
            'ព្រះរាជាណាចក្រកម្ពុជា',
            $moulFont,
            [
                'alignment' => Jc::CENTER,
                'spaceAfter' => 0,
            ]
        );

        $rightCell->addText(
            'ជាតិ សាសនា ព្រះមហាក្សត្រ',
            $moulFont,
            [
                'alignment' => Jc::CENTER,
                'spaceAfter' => 0,
            ]
        );

        $taktaingPath = public_path('images/taktaing.png');

        if (file_exists($taktaingPath)) {
            $rightCell->addImage(
                $taktaingPath,
                [
                    'width' => 80,
                    'alignment' => Jc::CENTER,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Report title
        |--------------------------------------------------------------------------
        */

        $departmentName =
            $this->evaluationPeriod->department?->name_kh
            ?? $this->results->first()?->evaluationPeriodUser?->user?->department?->name_kh
            ?? '';

        $title = sprintf(
            "របាយការណ៍វាយតម្លៃផ្អែកលើសមិទ្ធកម្មមន្ត្រី\n%s ប្រចាំខែ%s ឆ្នាំ%s",
            $departmentName,
            KhmerHelper::month($this->evaluationPeriod->month),
            KhmerHelper::number($this->evaluationPeriod->year)
        );

        $section->addText(
            $title,
            [
                'name' => 'Moul',
                'size' => 14,
            ],
            [
                'alignment' => Jc::CENTER,
                'spaceBefore' => 300,
                'spaceAfter' => 300,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Evaluation table
        |--------------------------------------------------------------------------
        */

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Column widths
        |--------------------------------------------------------------------------
        */

        $widths = [
            550,
            1900,
            1000,
            1350,
            1050,
            1250,
            1300,
            1450,
        ];

        /*
        |--------------------------------------------------------------------------
        | Table header
        |--------------------------------------------------------------------------
        */

        $headers = [
            'ល.រ',
            'គោត្តមនាម និងនាម',
            'តួនាទី',
            'សមិទ្ធកម្មការងារ',
            'វត្តមាន',
            'អាកប្បកិរិយា',
            'ពិន្ទុវាយតម្លៃ',
            'មូលវិចារណ៍',
        ];

        $table->addRow();

        foreach ($headers as $index => $header) {

            $cell = $table->addCell(
                $widths[$index],
                [
                    'valign' => 'center',
                    'bgColor' => 'FFFFFF',
                ]
            );

            $cell->addText(
                $header,
                [
                    'name' => 'Moul',
                    'size' => 9,
                ],
                [
                    'alignment' => Jc::CENTER,
                    'spaceAfter' => 0,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Employee rows
        |--------------------------------------------------------------------------
        */

        foreach ($this->results as $index => $result) {

            $employee =
                $result->evaluationPeriodUser?->user;

            $table->addRow();

            $values = [
                KhmerHelper::number($index + 1),

                $employee?->name_kh ?? 'មិនមាន',

                $employee?->position ?? 'មិនមាន',

                number_format(
                    $result->work_performance_score ?? 0,
                    2
                ),

                number_format(
                    $result->attendance_score ?? 0,
                    2
                ),

                number_format(
                    $result->behavior_score ?? 0,
                    2
                ),

                number_format(
                    $result->total_score ?? 0,
                    2
                ) . '/100',

                $result->remarks ?? '',
            ];

            foreach ($values as $columnIndex => $value) {

                $cell = $table->addCell(
                    $widths[$columnIndex],
                    [
                        'valign' => 'center',
                    ]
                );

                $cell->addText(
                    $value,
                    [
                        'name' => 'Siemreap',
                        'size' => 10,
                        'bold' => $columnIndex === 6,
                    ],
                    [
                        'alignment' =>
                            $columnIndex === 1 ||
                            $columnIndex === 7
                                ? Jc::LEFT
                                : Jc::CENTER,

                        'spaceAfter' => 0,
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Date / evaluator
        |--------------------------------------------------------------------------
        */

        $section->addTextBreak(2);

        $signatureTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'cellMargin' => 0,
        ]);

        $signatureTable->addRow();

        $signatureTable->addCell(
            5600,
            [
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
            ]
        );

        $signatureCell = $signatureTable->addCell(
            5250,
            [
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'valign' => 'top',
            ]
        );

        $signatureCell->addText(
            KhmerHelper::lunarDate(now()),
            $siemreapFont,
            [
                'alignment' => Jc::CENTER,
                'spaceAfter' => 30,
            ]
        );

        $signatureCell->addText(
            'ត្រូវនឹង' . KhmerHelper::fullDate(now()),
            $siemreapFont,
            [
                'alignment' => Jc::CENTER,
                'spaceAfter' => 80,
            ]
        );

        $signatureCell->addText(
            'ប្រធាននាយកដ្ឋាន',
            $moulFont,
            [
                'alignment' => Jc::CENTER,
                'spaceAfter' => 1000,
            ]
        );

        $signatureCell->addText(
            $this->departmentAdmin->name_kh,
            $moulFont,
            [
                'alignment' => Jc::CENTER,
                'spaceAfter' => 0,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        $fileName =
            'evaluation-results-'
            . $this->evaluationPeriod->month
            . '-'
            . $this->evaluationPeriod->year
            . '.docx';

        $tempFile = tempnam(
            sys_get_temp_dir(),
            'evaluation_bulk_'
        );

        $writer = IOFactory::createWriter(
            $phpWord,
            'Word2007'
        );

        $writer->save($tempFile);

        return response()
            ->download($tempFile, $fileName)
            ->deleteFileAfterSend(true);
    }
}
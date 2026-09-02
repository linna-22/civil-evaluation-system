<?php

namespace App\Exports;

use App\Helpers\KhmerHelper;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\Language;

class DepartmentEvaluationWordExport
{
    public function __construct(
        protected $evaluationPeriod,
        protected $result,
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
            'pageSizeW' => 11906, // A4 width
            'pageSizeH' => 16838, // A4 height
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

            'borderTopSize' => 0,
            'borderTopColor' => 'FFFFFF',

            'borderBottomSize' => 0,
            'borderBottomColor' => 'FFFFFF',

            'borderLeftSize' => 0,
            'borderLeftColor' => 'FFFFFF',

            'borderRightSize' => 0,
            'borderRightColor' => 'FFFFFF',

            'borderInsideHSize' => 0,
            'borderInsideHColor' => 'FFFFFF',

            'borderInsideVSize' => 0,
            'borderInsideVColor' => 'FFFFFF',

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

                'borderTopSize' => 0,
                'borderBottomSize' => 0,
                'borderLeftSize' => 0,
                'borderRightSize' => 0,
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

                'borderTopSize' => 0,
                'borderBottomSize' => 0,
                'borderLeftSize' => 0,
                'borderRightSize' => 0,
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
            $this->result->evaluationPeriodUser?->user?->department?->name_kh
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
        | Employee data
        |--------------------------------------------------------------------------
        */

        $employee =
            $this->result->evaluationPeriodUser?->user;

        $table->addRow();

        $values = [
            KhmerHelper::number(1),
            $employee?->name_kh ?? 'មិនមាន',
            $employee?->position ?? 'មិនមាន',
            number_format(
                $this->result->work_performance_score ?? 0,
                2
            ),
            number_format(
                $this->result->attendance_score ?? 0,
                2
            ),
            number_format(
                $this->result->behavior_score ?? 0,
                2
            ),
            number_format(
                $this->result->total_score ?? 0,
                2
            ) . '/100',
            $this->result->remarks ?? 'មិនមាន',
        ];

        foreach ($values as $index => $value) {
            $cell = $table->addCell(
                $widths[$index],
                [
                    'valign' => 'center',
                ]
            );

            $cell->addText(
                $value,
                [
                    'name' => 'Siemreap',
                    'size' => 10,
                    'bold' => $index === 6,
                ],
                [
                    'alignment' =>
                        $index === 1 || $index === 7
                        ? Jc::LEFT
                        : Jc::CENTER,
                    'spaceAfter' => 0,
                ]
            );
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

            'borderTopSize' => 0,
            'borderBottomSize' => 0,
            'borderLeftSize' => 0,
            'borderRightSize' => 0,

            'borderInsideHSize' => 0,
            'borderInsideVSize' => 0,

            'cellMargin' => 0,
        ]);

        $signatureTable->addRow();

        /*
        |--------------------------------------------------------------------------
        | Empty left side
        | Same total width as evaluation table
        |--------------------------------------------------------------------------
        */

        $signatureTable->addCell(
            5600,
            [
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',

                'borderTopSize' => 0,
                'borderBottomSize' => 0,
                'borderLeftSize' => 0,
                'borderRightSize' => 0,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Right signature column
        |--------------------------------------------------------------------------
        */

        $signatureCell = $signatureTable->addCell(
            5250,
            [
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',

                'borderTopSize' => 0,
                'borderBottomSize' => 0,
                'borderLeftSize' => 0,
                'borderRightSize' => 0,

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
            'evaluation-result-'
            . ($employee?->name_en ?? 'user')
            . '-'
            . $this->evaluationPeriod->month
            . '-'
            . $this->evaluationPeriod->year
            . '.docx';

        $tempFile = tempnam(
            sys_get_temp_dir(),
            'evaluation_'
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
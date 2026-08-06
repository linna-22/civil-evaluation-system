<?php

namespace App\Http\Controllers;

use App\Services\EvaluationReportService;
use Illuminate\Http\Request;

class EvaluationReportController extends Controller
{
    public function __construct(
        protected EvaluationReportService $reportService
    ) {
    }

    public function preview(Request $request)
    {
        $data = $this->reportService
            ->getReportData(
                $request->all(),
                auth()->user()
            );

        return view(
            'reports.evaluation.preview',
            $data
        );
    }
}

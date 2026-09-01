<?php

namespace App\Http\Controllers\EvaluationResult;

use App\Http\Controllers\Controller;
use App\Models\EvaluationPeriod;
use App\Services\UserEvaluationResultService;
use Illuminate\View\View;

class UserEvaluationResultController extends Controller
{
    public function index(
        UserEvaluationResultService $service
    ): View {

        if (auth()->user()->role !== 'user') {
            abort(403);
        }
        $periods = $service->getClosedPeriods();

        return view(
            'evaluation-results.user.index',
            compact('periods')
        );
    }


    public function show(
        EvaluationPeriod $evaluationPeriod,
        UserEvaluationResultService $service
    ): View {

        $user = auth()->user();

        $result = $service->getResultForPeriod(
            $user,
            $evaluationPeriod
        );

        return view(
            'evaluation-results.user.show',
            compact(
                'result',
                'evaluationPeriod'
            )
        );
    }
}
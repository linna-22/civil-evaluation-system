<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationPeriodRequest;
use App\Models\EvaluationPeriod;
use App\Services\EvaluationPeriodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EvaluationPeriodController extends Controller
{
    /**
     * Display evaluation periods.
     */
    public function index()
    {
        return view('evaluation-periods.index');
    }
    /**
     * Get evaluation periods for DataTable.
     */
    public function data(
        Request $request,
        EvaluationPeriodService $service
    ) {
        $evaluationPeriods = $service->getData($request);

        return response()->json([
            'success' => true,
            'message' => 'Evaluation periods loaded successfully.',
            'data' => $evaluationPeriods,
        ]);
    }


    /**
     * Show create evaluation period form.
     */
    public function create()
    {
        return view('evaluation-periods.create');
    }


    /**
     * Store a new evaluation period.
     */
    public function store(EvaluationPeriodRequest $request, EvaluationPeriodService $service)
    {
        // dd($request->all());
        $service->store($request->validated());
        Log::info('sucess');
        return redirect()->route('evaluation-periods.index')->with(
            'success',
            'ការវាយតម្លៃត្រូវបានបង្កើតដោយជោគជ័យ'
        );
    }


    /**
     * Show edit evaluation period form.
     */
    public function edit(EvaluationPeriod $evaluationPeriod)
    {
        return view('evaluation-periods.edit', compact('evaluationPeriod'));
    }


    /**
     * Update evaluation period.
     */
    public function update(
        EvaluationPeriodRequest $request,
        EvaluationPeriod $evaluationPeriod,
        EvaluationPeriodService $service
    ) {
        $service->update(
            $evaluationPeriod,
            $request->validated()
        );

        return redirect()
            ->route('evaluation-periods.index')
            ->with(
                'success',
                'ការវាយតម្លៃត្រូវបានកែប្រែដោយជោគជ័យ'
            );
    }
    public function close(EvaluationPeriod $evaluationPeriod, EvaluationPeriodService $service)
    {
        $service->close($evaluationPeriod);

        return response()->json([
            'success' => true,
            'message' => 'វគ្គវាយតម្លៃត្រូវបានបិទដោយជោគជ័យ។',
        ]);
    }
    public function show(EvaluationPeriod $evaluationPeriod, EvaluationPeriodService $service) 
    {
        $evaluationPeriod = $service->find(
            $evaluationPeriod
        );

        return view(
            'evaluation-periods.show',
            compact('evaluationPeriod')
        );
    }

}
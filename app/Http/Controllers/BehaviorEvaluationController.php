<?php

namespace App\Http\Controllers;

use App\Http\Requests\BehaviorEvaluationRequest;
use App\Services\BehaviorEvaluationService;

class BehaviorEvaluationController extends Controller
{
    /**
     * Display the behavior evaluation page.
     */
    public function index()
    {
        return view('evaluations.behavior.index');
    }
    public function data(BehaviorEvaluationService $service)
    {
        $peers = $service->getPaginatedEligiblePeers(6);

        return response()->json([
            'data' => $peers,
        ]);
    }


    /**
     * Show behavior evaluation form.
     */
    public function create(BehaviorEvaluationService $service)
    {
        $peers = $service->getEligiblePeers();
        return view('evaluations.behavior.create', compact('peers'));
    }
    /**
     * Store a behavior evaluation.
     */
    public function store(BehaviorEvaluationRequest $request, BehaviorEvaluationService $service)
    {
        $service->store($request->validated());

        return response()->json([
            'success' => true,
            'message' =>
                'ការវាយតម្លៃឥរិយាបថត្រូវបានរក្សាទុកដោយជោគជ័យ។',
        ]);
    }

    public function preview()
    {
        return view('evaluations.behavior.preview');
    }
    public function view(BehaviorEvaluationService $service)
    {
        $evaluations = $service->getSubmittedEvaluations();
        return view(
            'evaluations.behavior.view',
            compact('evaluations')
        );
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\BehaviorEvaluationRequest;
use App\Services\BehaviorEvaluationService;

class BehaviorEvaluationController extends Controller
{
    /**
     * Display the behavior evaluation page.
     */
    public function index(
        BehaviorEvaluationService $service
    ) {
        $peers = $service->getEligiblePeers();

        return view('evaluations.behavior.index', compact('peers')
        );
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
        return redirect()
            ->route('evaluations.behavior.index')
            ->with(
                'success',
                'ការវាយតម្លៃឥរិយាបថត្រូវបានរក្សាទុកដោយជោគជ័យ។'
            );
    }

    public function preview(){
        return view('evaluations.behavior.preview');
    }
}
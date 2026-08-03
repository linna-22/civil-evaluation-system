<?php

namespace App\Http\Controllers;

use App\Constants\BehaviorCriteria;
use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Evaluation;
use App\Services\EvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    protected EvaluationService $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }
    public function index()
    {
        $user = Auth::user()->load([
            'department',
            'organization',
        ]);

        $currentEvaluation = Evaluation::where('user_id', $user->user_id)
            ->where('evaluation_month', now()->month)
            ->where('evaluation_year', now()->year)
            ->first();

        return view('evaluations.index', compact(
            'user',
            'currentEvaluation'
        ));
    }
    public function create()
    {
        $behaviorSections = BehaviorCriteria::SECTIONS;
        return view('evaluations.create', compact(
            'behaviorSections'
        ));
    }

    public function store(StoreEvaluationRequest $request)
    {
        $evaluation = $this->evaluationService->store(
            $request->validated()
        );
        return response()->json([
            'success' => true,
            'message' => 'ការវាយតម្លៃត្រូវបានរក្សាទុកដោយជោគជ័យ។',
            'redirect_url' => route('evaluations.index'),
            
        ]); 
    }
    public function history()
    {
        return view('evaluations.history');

    }
}

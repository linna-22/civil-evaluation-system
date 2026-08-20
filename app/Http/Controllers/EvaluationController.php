<?php

namespace App\Http\Controllers;

use App\Constants\BehaviorCriteria;
use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\Organization;
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
        $user = Auth::user()->load([
            'department',
            'organization',
        ]);
        $behaviorSections = BehaviorCriteria::SECTIONS;
        return view('evaluations.create', compact(
            'behaviorSections',
            'user'
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
    public function show(Evaluation $evaluation)
    {
        $evaluation->load([
            'user.organization',
            'user.department',
            'workPerformance',
            'attendance',
            'behavior',
        ]);

        return view('evaluations.show', compact('evaluation'));
    }
    public function history()
    {
        return view('evaluations.history');

    }
    public function list(Request $request)
    {
        $user = auth()->user();

        $filters = $request->only([
            'search',
            'organization',
            'department',
            'month',
            'year',
        ]);

        $evaluations = $this->evaluationService->getEvaluationList($filters, $user);

        $organizations = $this->evaluationService->getOrganizations();

        $departments = $this->evaluationService->getDepartments($user);

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('evaluations.partials.table-body', compact('evaluations'))->render(),
                'pagination' => view('evaluations.partials.pagination', compact('evaluations'))->render(),
            ]);
        }

        return view('evaluations.list', compact(
            'evaluations',
            'organizations',
            'departments'
        ));
    }
    public function departments(Request $request)
    {
        $departments = $this->evaluationService
            ->getDepartmentsByOrganization(
                $request->organization
            );

        return response()->json($departments);
    }
}

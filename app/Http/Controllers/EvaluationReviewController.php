<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Services\EvaluationReviewService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EvaluationReviewController extends Controller
{
    public function index(EvaluationReviewService $service)
    {
        $evaluationPeriod = $service->getOpenEvaluationPeriod();

        $departmentId = $service->getDepartmentId();

        return view('evaluations-review.index', compact(
            'evaluationPeriod',
            'departmentId'
        ));
    }

    public function data(
        EvaluationReviewService $service,
        Request $request
    ) {
        $evaluationPeriod = $service->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return response()->json([
                'data' => new LengthAwarePaginator(
                    collect(),
                    0,
                    6,
                    1,
                    [
                        'path' => $request->url(),
                        'query' => $request->query(),
                    ]
                ),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get all users in the department
        |--------------------------------------------------------------------------
        */

        $users = $service->getDepartmentUsers();

        /*
        |--------------------------------------------------------------------------
        | Get users who have submitted an evaluation
        |--------------------------------------------------------------------------
        */

        $submittedUserIds = Evaluation::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where('evaluation_status', 'submitted')
            ->whereIn(
                'evaluatee_id',
                $users->pluck('user_id')
            )
            ->pluck('evaluatee_id')
            ->unique();

        /*
        |--------------------------------------------------------------------------
        | Keep only users with submitted evaluations
        |--------------------------------------------------------------------------
        */

        $users = $users
            ->filter(function ($user) use ($submittedUserIds) {
                return $submittedUserIds->contains($user->user_id);
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        |
        | EvaluationReviewService returns a Collection, while our shared
        | DataTable expects a Laravel paginator response.
        |
        */

        $perPage = (int) $request->get('per_page', 6);

        if ($perPage < 1) {
            $perPage = 6;
        }

        $currentPage = (int) $request->get('page', 1);

        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $total = $users->count();

        $paginatedUsers = new LengthAwarePaginator(
            $users->forPage($currentPage, $perPage)->values(),
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Return paginator in the structure expected by DataTable
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'data' => $paginatedUsers,
        ]);
    }
}
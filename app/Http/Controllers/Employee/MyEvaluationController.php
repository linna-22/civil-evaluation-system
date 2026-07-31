<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class MyEvaluationController extends Controller
{
    public function __construct(
        protected EvaluationService $evaluationService
    ) {}

    public function index()
    {
        return view('employee.evaluations.index');

    }
}

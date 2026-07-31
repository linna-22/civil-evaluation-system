<?php

namespace App\Http\Controllers;

use App\Constants\BehaviorCriteria;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        return view('employee.evaluations.index');
    }
    public function create()
{
    $behaviorSections = BehaviorCriteria::SECTIONS;

    return view('evaluations.create', compact(
        'behaviorSections'
    ));
}
}

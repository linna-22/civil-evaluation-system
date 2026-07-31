<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
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

        return view('employee.dashboard', compact(
            'user',
            'currentEvaluation'
        ));
    }
}

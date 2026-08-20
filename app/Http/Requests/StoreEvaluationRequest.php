<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'evaluation_month' => [
                'required',
                'integer',
                'between:1,12',
            ],

            'evaluation_year' => [
                'required',
                'digits:4',
            ],

            'performances' => [
                'required',
                'array',
                'min:1',
                'max:5',
            ],

            'performances.*.activity' => [
                'required',
                'string',
            ],

            'performances.*.indicator' => [
                'required',
                'string',
            ],

            'performances.*.achievement_percent' => [
                'required',
                'numeric',
                'between:0,100',
            ],
            'approved_leave_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'unapproved_leave_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'late_hours' => [
                'required',
                'numeric',
                'min:0',
            ],

            'leave_early_hours' => [
                'required',
                'numeric',
                'min:0',
            ],
            'discipline' => ['required', 'integer', 'between:0,2'],
            'responsibility' => ['required', 'integer', 'between:0,2'],
            'professional_ethics' => ['required', 'integer', 'between:0,2'],

            'work_performance' => ['required', 'integer', 'between:0,2'],
            'self_development' => ['required', 'integer', 'between:0,2'],
            'initiative_creativity' => ['required', 'integer', 'between:0,2'],

            'teamwork' => ['required', 'integer', 'between:0,2'],
            'interpersonal_skill' => ['required', 'integer', 'between:0,2'],
            'work_under_pressure' => ['required', 'integer', 'between:0,2'],
            'leadership' => ['required', 'integer', 'between:0,2'],

        ];
    }
}

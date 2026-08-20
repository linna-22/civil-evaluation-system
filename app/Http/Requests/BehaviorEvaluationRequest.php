<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BehaviorEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized
     * to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }


    /**
     * Validation rules.
     */
    // public function rules(): array
    // {
    //     return [

    //         // ==========================================
    //         // Evaluatee
    //         // ==========================================

    //         'evaluatee_id' => [
    //             'required',
    //             'integer',
    //         ],


    //         // ==========================================
    //         // Behavior Evaluation
    //         // ==========================================

    //         'discipline' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],

    //         'responsibility' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],

    //         'professional_ethics' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],


    //         'work_performance' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],

    //         'self_development' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],

    //         'initiative_creativity' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],


    //         'teamwork' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],

    //         'interpersonal_skill' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],

    //         'work_under_pressure' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],

    //         'leadership' => [
    //             'required',
    //             'integer',
    //             'between:0,2',
    //         ],

    //     ];
    // }
    public function rules(): array
{
    return [

        'evaluations' => [
            'required',
            'array',
            'min:1',
        ],

        'evaluations.*.evaluatee_id' => [
            'required',
            'integer',
            'distinct',
        ],

        'evaluations.*.discipline' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.responsibility' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.professional_ethics' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.work_performance' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.self_development' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.initiative_creativity' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.teamwork' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.interpersonal_skill' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.work_under_pressure' => [
            'required',
            'integer',
            'between:0,2',
        ],

        'evaluations.*.leadership' => [
            'required',
            'integer',
            'between:0,2',
        ],

    ];
}


    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'evaluatee_id.required' =>
                'សូមជ្រើសរើសមន្ត្រីដែលត្រូវវាយតម្លៃ។',

            'evaluatee_id.integer' =>
                'មន្ត្រីដែលត្រូវវាយតម្លៃមិនត្រឹមត្រូវ។',


            'discipline.required' =>
                'សូមវាយតម្លៃការគោរពវិន័យការងារ។',

            'responsibility.required' =>
                'សូមវាយតម្លៃស្មារតីទទួលខុសត្រូវ។',

            'professional_ethics.required' =>
                'សូមវាយតម្លៃក្រមសីលធម៌វិជ្ជាជីវៈ។',


            'work_performance.required' =>
                'សូមវាយតម្លៃសមត្ថភាពបំពេញការងារ។',

            'self_development.required' =>
                'សូមវាយតម្លៃការអភិវឌ្ឍសមត្ថភាព។',

            'initiative_creativity.required' =>
                'សូមវាយតម្លៃគំនិតផ្តួចផ្តើម និងការច្នៃប្រឌិត។',


            'teamwork.required' =>
                'សូមវាយតម្លៃស្មារតីការងារជាក្រុម។',

            'interpersonal_skill.required' =>
                'សូមវាយតម្លៃទំនាក់ទំនងអន្តរបុគ្គល។',

            'work_under_pressure.required' =>
                'សូមវាយតម្លៃសមត្ថភាពធ្វើការក្រោមសម្ពាធ។',

            'leadership.required' =>
                'សូមវាយតម្លៃសមត្ថភាពភាពជាអ្នកដឹកនាំ.',

        ];
    }
}
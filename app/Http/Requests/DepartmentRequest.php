<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'organization_id' => [
                'required',
                'exists:organizations,organization_id',
            ],

            'code' => [
                'required',
                'max:50',
                'unique:departments,department_code',
            ],

            'name_kh' => [
                'required',
                'max:255',
            ],

            'name_en' => [
                'nullable',
                'max:255',
            ],

            'description' => [
                'nullable',
            ],

            'status' => [
                'required',
            ],

        ];
    }
}
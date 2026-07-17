<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name_kh' => [
                'required',
                'max:255',
            ],

            'name_en' => [
                'required',
                'max:255',
            ],

            'code' => [
                'required',
                'max:50',
                'unique:organizations,org_code',
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
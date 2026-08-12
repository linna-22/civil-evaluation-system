<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            ],
            'department_id' => [
                'required',
            ],
            'office_id' => [
                'required',
            ],
            'name_kh' => [
                'required',
                'max:255',
            ],

            'name_en' => [
                'required',
                'max:255',
            ],
            'username' => [
                'required',
                'max:255',
            ],

            'gender' => [
                'required',
                'max:30',
            ],

            'phone' => [
                'required',
            ],
            'email' => [
                'required',
            ],
            'position' => [
                'required',
            ],
            'role' => [
                'required',
            ],

            'status' => [
                'required',
            ],
            'id_code' => [
                'required',
                'max:255',
            ],
            'is_leader' => [
                'required',
            ],

        ];
    }
}
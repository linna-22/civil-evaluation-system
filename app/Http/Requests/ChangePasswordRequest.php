<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'current_password.required' =>
                'សូមបញ្ចូលពាក្យសម្ងាត់បច្ចុប្បន្ន។',

            'password.required' =>
                'សូមបញ្ចូលពាក្យសម្ងាត់ថ្មី។',

            'password.min' =>
                'ពាក្យសម្ងាត់ថ្មីត្រូវមានយ៉ាងតិច ៨ តួអក្សរ។',

            'password.confirmed' =>
                'ការបញ្ជាក់ពាក្យសម្ងាត់មិនត្រឹមត្រូវ។',

        ];
    }
}
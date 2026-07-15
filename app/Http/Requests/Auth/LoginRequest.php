<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [
            // 'login.required' => 'Please enter your username or email.',
            'login.required' => 'សូមបញ្ចូលឈ្មោះអ្នកប្រើប្រាស់ ឬ អ៊ីមែលរបស់អ្នក។',
            // 'password.required' => 'Please enter your password.',
            'password.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់របស់អ្នក។',
        ];
    }
}
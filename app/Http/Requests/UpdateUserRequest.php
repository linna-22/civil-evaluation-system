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
                'nullable',
                'required_if:is_leader,0',
            ],
            'office_id' => [
                'nullable',
                'required_if:is_leader,0',
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
    public function messages(): array
    {
        return [

            // ==========================================
            // Organization
            // ==========================================

            'organization_id.required' =>
                'សូមជ្រើសរើសអង្គភាព',


            // ==========================================
            // Department
            // ==========================================

            'department_id.required' =>
                'សូមជ្រើសរើសនាយកដ្ឋាន',

            'department_id.required_if' =>
                'សូមជ្រើសរើសនាយកដ្ឋាន',


            // ==========================================
            // Office
            // ==========================================

            'office_id.required' =>
                'សូមជ្រើសរើសការិយាល័យ',

            'office_id.required_if' =>
                'សូមជ្រើសរើសការិយាល័យ',


            // ==========================================
            // Name
            // ==========================================

            'name_kh.required' =>
                'សូមបញ្ចូលឈ្មោះជាភាសាខ្មែរ',

            'name_kh.max' =>
                'ឈ្មោះជាភាសាខ្មែរមិនអាចលើសពី ២៥៥ តួអក្សរ។',

            'name_en.required' =>
                'សូមបញ្ចូលឈ្មោះជាភាសាអង់គ្លេស',

            'name_en.max' =>
                'ឈ្មោះជាភាសាអង់គ្លេសមិនអាចលើសពី ២៥៥ តួអក្សរ',


            // ==========================================
            // Account
            // ==========================================

            'username.required' =>
                'សូមបញ្ចូលឈ្មោះអ្នកប្រើប្រាស់',

            'username.max' =>
                'ឈ្មោះអ្នកប្រើប្រាស់មិនអាចលើសពី ២៥៥ តួអក្សរ',

            // ==========================================
            // Personal Information
            // ==========================================

            'gender.required' =>
                'សូមជ្រើសរើសភេទ',

            'gender.max' =>
                'ទិន្នន័យភេទមិនត្រឹមត្រូវ',

            'phone.required' =>
                'សូមបញ្ចូលលេខទូរស័ព្ទ',

            'email.required' =>
                'សូមបញ្ចូលអុីមែល',


            // ==========================================
            // Position & Role
            // ==========================================

            'position.required' =>
                'សូមបញ្ចូលតួនាទី',

            'role.required' =>
                'សូមជ្រើសរើស Role',

            'status.required' =>
                'សូមជ្រើសរើសស្ថានភាព',


            // ==========================================
            // Employee ID
            // ==========================================

            'id_code.required' =>
                'សូមបញ្ចូលអត្តលេខមន្ត្រី',

            'id_code.max' =>
                'អត្តលេខមន្ត្រីមិនអាចលើសពី ២៥៥ តួអក្សរ',


            // ==========================================
            // Leader
            // ==========================================

            'is_leader.required' =>
                'សូមកំណត់ថាតើមន្ត្រីនេះជាអ្នកដឹកនាំឬអត់',

        ];
    }
}
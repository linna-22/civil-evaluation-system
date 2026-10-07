@extends('layouts.app')

@section('title', 'កំណត់អ្នកបញ្ចូលទិន្នន័យ')

@section('content')

{{-- Tom Select --}}
<link
    href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css"
    rel="stylesheet"
>

<style>
    /* =========================================================
       Tom Select
       ========================================================= */

    .data-entry-select .ts-wrapper {
        width: 100%;
    }

    .data-entry-select .ts-control {
        min-height: 44px;
        height: 44px;

        display: flex;
        align-items: center;

        border: 1px solid #d1d5db;
        border-radius: 10px;

        background: #ffffff;

        padding: 0 38px 0 12px;

        font-size: 14px;

        box-shadow: none;

        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .data-entry-select .ts-control:hover {
        border-color: #9ca3af;
    }

    .data-entry-select.focus .ts-control {
        border-color: #3b82f6;

        box-shadow:
            0 0 0 3px rgba(59, 130, 246, 0.10);
    }

    /* Selected user */

    .data-entry-select .item {
        display: flex;
        align-items: center;

        max-width: 100%;

        color: #374151;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Search input */

    .data-entry-select .ts-control input {
        min-width: 80px;

        font-size: 14px;

        color: #374151;
    }

    .data-entry-select .ts-control input::placeholder {
        color: #9ca3af;
    }

    /* =========================================================
       Floating Dropdown
       ========================================================= */

    .ts-dropdown {
        position: absolute !important;

        z-index: 99999 !important;

        margin-top: 5px;

        border: 1px solid #e5e7eb;

        border-radius: 10px;

        background: #ffffff;

        overflow: hidden;

        box-shadow:
            0 12px 30px rgba(0, 0, 0, 0.12),
            0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .ts-dropdown .ts-dropdown-content {
        max-height: 240px;

        overflow-y: auto;

        padding: 5px;
    }

    /* Search result */

    .ts-dropdown .option {
        padding: 9px 10px;

        border-radius: 7px;

        cursor: pointer;

        line-height: 1.35;
    }

    .ts-dropdown .option:hover,
    .ts-dropdown .option.active {
        background: #eff6ff;

        color: #1d4ed8;
    }

    /* User name */

    .ts-dropdown .option .user-name {
        font-size: 14px;

        font-weight: 500;

        color: #374151;
    }

    /* User position */

    .ts-dropdown .option .user-position {
        margin-top: 2px;

        font-size: 12px;

        color: #9ca3af;
    }

    /* No result */

    .ts-dropdown .no-results {
        padding: 12px;

        text-align: center;

        font-size: 13px;

        color: #9ca3af;
    }

    /* Clear button */

    .data-entry-select .clear-button {
        color: #9ca3af !important;

        font-size: 14px !important;
    }

    .data-entry-select .clear-button:hover {
        color: #ef4444 !important;
    }

    /* Office row */

    .office-row {
        transition: background-color 0.15s ease;
    }

    .office-row:hover {
        background-color: #fafafa;
    }
</style>

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <x-page-header
        title="កំណត់អ្នកបញ្ចូលទិន្នន័យ"
        description=""
    >
        <x-slot:actions>
            <x-action-btn
                href="{{ route('evaluation-periods.show', $evaluationPeriod) }}"
                variant="secondary"
                icon="arrow-left"
            >
                ត្រឡប់ក្រោយ
            </x-action-btn>
        </x-slot:actions>
    </x-page-header>


    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('evaluation-periods.data-entry.update', $evaluationPeriod) }}"
        class="space-y-6"
    >
        @csrf
        @method('PUT')


        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <div class="font-semibold mb-2">
                    សូមពិនិត្យព័ត៌មានខាងក្រោម៖
                </div>

                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Success --}}
        @if (session('success'))
            <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error --}}
        @if (session('error'))
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif


        {{-- Evaluation Period Information --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between gap-4 mb-6">

                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $evaluationPeriod->name_kh }}
                    </h2>

                    {{-- <p class="text-sm text-gray-500 mt-1">
                        {{ $evaluationPeriod->name_en }}
                        · ខែ {{ $evaluationPeriod->month }}
                        ឆ្នាំ {{ $evaluationPeriod->year }}
                    </p> --}}
                </div>

                <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">
                    កំពុងបើក
                </span>

            </div>


            {{-- Information --}}
            <div class="rounded-xl bg-blue-50 border border-blue-100 p-4 text-sm text-blue-800">

                <p class="font-semibold mb-1">
                    របៀបកំណត់អ្នកបញ្ចូលទិន្នន័យ
                </p>

                <p>
                    អ្នកកម្រិតនាយកដ្ឋានទទួលខុសត្រូវលើមន្ត្រីកម្រិតនាយកដ្ឋាន
                    ដូចជា អនុប្រធាននាយកដ្ឋាន។
                    អ្នកកម្រិតការិយាល័យម្នាក់ៗទទួលខុសត្រូវលើមន្ត្រី
                    ក្នុងការិយាល័យរបស់ខ្លួន។
                </p>

            </div>

        </div>


        {{-- Departments --}}
        @forelse ($departments as $departmentIndex => $department)

            @php
                $departmentAssignment =
                    $assignments->get("department:{$department->department_id}");
            @endphp

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

                {{-- Department Header --}}
                <div class="p-6 border-b bg-gray-50">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-2M9 7h6m-6 4h6m-6 4h6"
                                />
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $department->department_name_kh }}
                            </h3>

                            @if ($department->department_name_en)
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $department->department_name_en }}
                                </p>
                            @endif
                        </div>

                    </div>

                </div>


                <div class="p-6 space-y-8">


                    {{-- Department-level Data Entry --}}
                    <div>

                        <div class="mb-3">

                            <label class="block text-sm font-semibold text-gray-800">
                                អ្នកបញ្ចូលទិន្នន័យកម្រិតនាយកដ្ឋាន
                            </label>

                            <p class="text-xs text-gray-500 mt-1">
                                សម្រាប់មន្ត្រីកម្រិតនាយកដ្ឋាន ដូចជា អនុប្រធាននាយកដ្ឋាន។
                            </p>

                        </div>


                        <div class="data-entry-select">

                            <select
                                name="assignments[{{ $departmentIndex }}][user_id]"
                                class="js-user-select"
                                data-placeholder="ស្វែងរក និងជ្រើសរើសអ្នកបញ្ចូលទិន្នន័យ..."
                                required
                            >

                                <option value=""></option>

                                @foreach ($department->users as $user)

                                    <option
                                        value="{{ $user->user_id }}"
                                        data-position="{{ \App\Helpers\PositionHelper::label($user->position) }}"
                                        @selected(
                                            optional($departmentAssignment)->user_id == $user->user_id
                                        )
                                    >
                                        {{ $user->name_kh }}
                                        @if ($user->position)
                                            — {{ \App\Helpers\PositionHelper::label($user->position) }}
                                        @endif
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <input
                            type="hidden"
                            name="assignments[{{ $departmentIndex }}][scope]"
                            value="department"
                        >

                        <input
                            type="hidden"
                            name="assignments[{{ $departmentIndex }}][department_id]"
                            value="{{ $department->department_id }}"
                        >

                        <input
                            type="hidden"
                            name="assignments[{{ $departmentIndex }}][office_id]"
                            value=""
                        >

                    </div>


                    {{-- Office Data Entry --}}
                    <div>

                        <div class="flex items-center justify-between mb-4">

                            <div>

                                <h4 class="font-semibold text-gray-800">
                                    អ្នកបញ្ចូលទិន្នន័យតាមការិយាល័យ
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">
                                    ជ្រើសរើសអ្នកប្រើប្រាស់ម្នាក់សម្រាប់ការិយាល័យនីមួយៗ។
                                </p>

                            </div>

                            <span class="text-xs font-medium px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                                {{ $department->offices->count() }} ការិយាល័យ
                            </span>

                        </div>


                        <div class="overflow-x-auto border border-gray-200 rounded-xl">

                            <table class="w-full">

                                <thead class="bg-gray-50">

                                    <tr class="text-sm text-gray-600">

                                        <th class="px-4 py-3 text-left w-14">
                                            #
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            ការិយាល័យ
                                        </th>

                                        <th class="px-4 py-3 text-left min-w-[320px]">
                                            អ្នកបញ្ចូលទិន្នន័យ
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse ($department->offices as $officeIndex => $office)

                                        @php
                                            $rowIndex =
                                                $departmentIndex . '_' . $officeIndex;

                                            $officeAssignment =
                                                $assignments->get(
                                                    "office:{$department->department_id}:{$office->office_id}"
                                                );
                                        @endphp


                                        <tr class="border-t office-row">

                                            <td class="px-4 py-4 text-gray-500 align-top">
                                                {{ $officeIndex + 1 }}
                                            </td>


                                            <td class="px-4 py-4 align-top">

                                                <div class="font-medium text-gray-800">
                                                    {{ $office->office_name_kh }}
                                                </div>

                                                @if ($office->office_name_en)

                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $office->office_name_en }}
                                                    </div>

                                                @endif

                                            </td>


                                            <td class="px-4 py-4 align-top">

                                                <input
                                                    type="hidden"
                                                    name="assignments[{{ $rowIndex }}][scope]"
                                                    value="office"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="assignments[{{ $rowIndex }}][department_id]"
                                                    value="{{ $department->department_id }}"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="assignments[{{ $rowIndex }}][office_id]"
                                                    value="{{ $office->office_id }}"
                                                >


                                                <div class="data-entry-select">

                                                    <select
                                                        name="assignments[{{ $rowIndex }}][user_id]"
                                                        class="js-user-select"
                                                        data-placeholder="ស្វែងរកអ្នកបញ្ចូលទិន្នន័យ..."
                                                        required
                                                    >

                                                        <option value=""></option>

                                                        @foreach ($office->users as $user)

                                                            <option
                                                                value="{{ $user->user_id }}"
                                                                data-position="{{ \App\Helpers\PositionHelper::label($user->position) }}"
                                                                @selected(
                                                                    optional($officeAssignment)->user_id == $user->user_id
                                                                )
                                                            >
                                                                {{ $user->name_kh }}
                                                                @if ($user->position)
                                                                    — {{ \App\Helpers\PositionHelper::label($user->position) }}
                                                                @endif
                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                @if ($office->users->isEmpty())

                                                    <p class="text-xs text-red-500 mt-2">
                                                        មិនមានអ្នកប្រើប្រាស់សកម្មក្នុងការិយាល័យនេះទេ។
                                                    </p>

                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="3"
                                                class="px-4 py-8 text-center text-gray-400"
                                            >
                                                មិនមានការិយាល័យសកម្មក្នុងនាយកដ្ឋាននេះទេ។
                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-500">

                មិនមាននាយកដ្ឋានចូលរួមក្នុងវគ្គវាយតម្លៃនេះទេ។

            </div>

        @endforelse


        {{-- Bottom Actions --}}
        @if ($departments->isNotEmpty())

            <div class="flex justify-end gap-3 sticky bottom-4 z-40">

                <x-action-btn
                    href="{{ route('evaluation-periods.show', $evaluationPeriod) }}"
                    variant="secondary"
                    icon="x"
                >
                    បោះបង់
                </x-action-btn>

                <x-action-btn
                    type="submit"
                    variant="primary"
                    icon="save"
                >
                    រក្សាទុកការកំណត់ទាំងអស់
                </x-action-btn>

            </div>

        @endif

    </form>

</div>


{{-- Tom Select --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.js-user-select').forEach(function (select) {

            new TomSelect(select, {

                /*
                |--------------------------------------------------------------------------
                | Keep dropdown outside the table / overflow container
                |--------------------------------------------------------------------------
                */
                dropdownParent: 'body',

                /*
                |--------------------------------------------------------------------------
                | Placeholder
                |--------------------------------------------------------------------------
                */
                placeholder:
                    select.dataset.placeholder ||
                    'ស្វែងរកអ្នកប្រើប្រាស់...',

                allowEmptyOption: true,

                /*
                |--------------------------------------------------------------------------
                | Search
                |--------------------------------------------------------------------------
                */
                searchField: [
                    'text',
                    'position'
                ],

                /*
                |--------------------------------------------------------------------------
                | Sorting
                |--------------------------------------------------------------------------
                */
                sortField: {
                    field: 'text',
                    direction: 'asc'
                },

                /*
                |--------------------------------------------------------------------------
                | Limit results
                |--------------------------------------------------------------------------
                */
                maxOptions: 50,

                /*
                |--------------------------------------------------------------------------
                | Clear selected user
                |--------------------------------------------------------------------------
                */
                plugins: {
                    clear_button: {
                        title: 'សម្អាត'
                    }
                },

                /*
                |--------------------------------------------------------------------------
                | Render dropdown
                |--------------------------------------------------------------------------
                */
                render: {

                    option: function (data, escape) {

                        const position = data.position
                            ? `
                                <div class="user-position">
                                    ${escape(data.position)}
                                </div>
                              `
                            : '';

                        return `
                            <div>
                                <div class="user-name">
                                    ${escape(data.text)}
                                </div>

                                ${position}
                            </div>
                        `;
                    },

                    item: function (data, escape) {

                        return `
                            <div>
                                ${escape(data.text)}
                            </div>
                        `;
                    },

                    no_results: function () {

                        return `
                            <div class="no-results">
                                រកមិនឃើញអ្នកប្រើប្រាស់ទេ
                            </div>
                        `;
                    }
                }
            });

        });

    });
</script>

@endsection
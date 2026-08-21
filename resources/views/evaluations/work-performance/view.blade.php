@extends('layouts.app')

@section('title', 'លទ្ធផលវាយតម្លៃ')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- ==========================================
        Page Header
    =========================================== --}}

    <div class="mb-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-xl font-title text-gray-800">
                    លទ្ធផលវាយតម្លៃសមិទ្ធកម្មការងារ
                </h1>

                <p class="mt-1 text-sm text-gray-500">

                    @if ($officeModel)
                        {{ $officeModel->office_name_kh }}
                    @else
                        {{ $department->department_name_kh }}
                    @endif

                </p>

            </div>


            {{-- Back --}}

            <a
                href="{{ $officeModel
                    ? route(
                        'evaluations.work-performance.office.users',
                        ['office' => $officeModel->office_id]
                    )
                    : route(
                        'evaluations.work-performance.department.users',
                        ['department' => $department->department_id]
                    ) }}"
                class="inline-flex items-center gap-2
                       px-4 py-2
                       rounded-lg
                       border border-gray-300
                       text-gray-600
                       text-sm
                       font-medium
                       hover:bg-gray-50
                       transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                ត្រឡប់ក្រោយ
            </a>
        </div>
    </div>
    {{-- ==========================================
        Evaluation Period
    =========================================== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-5">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $evaluationPeriod->name_kh }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $department->department_name_kh }}
                        @if ($officeModel)
                            / {{ $officeModel->office_name_kh }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    {{-- ==========================================
        Result Table
    =========================================== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div
            class="px-6 py-4
                   border-b border-gray-200
                   flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-800">
                    បញ្ជីលទ្ធផលវាយតម្លៃ
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    មន្ត្រីដែលបានបញ្ជូនការវាយតម្លៃរួច
                </p>
            </div>
            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">
                <i data-lucide="users" class="w-4 h-4"></i>
                {{ $evaluations->count() }} នាក់
            </div>
        </div>
        @if ($evaluations->isEmpty())
            <div class="px-6 py-12 text-center">
                <i data-lucide="clipboard-x" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
                <p class="text-gray-500">
                    មិនទាន់មានការវាយតម្លៃដែលបានបញ្ជូនទេ
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left font-medium text-gray-600">
                                ល.រ
                            </th>
                            <th class="px-6 py-4 text-left font-medium text-gray-600">
                                ឈ្មោះមន្ត្រី
                            </th>
                            <th class="px-6 py-4 text-left font-medium text-gray-600">
                                តួនាទី
                            </th>

                            <th class="px-6 py-4 text-center font-medium text-gray-600">
                                ចំនួនសកម្មភាព
                            </th>

                            <th class="px-6 py-4 text-center font-medium text-gray-600">
                                ពិន្ទុសរុប
                            </th>
                            <th class="px-6 py-4 text-center font-medium text-gray-600">
                                ពិន្ទុវាយតម្លៃ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($evaluations as $index => $evaluation)
                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | Calculate Total Score
                                |--------------------------------------------------------------------------
                                |
                                | We do NOT store totalScore.
                                | We calculate it from each row's score.
                                |
                                */
                                $totalScore = $evaluation->workPerformance->sum('score');
                                /*
                                |--------------------------------------------------------------------------
                                | Evaluation Score
                                |--------------------------------------------------------------------------
                                */
                                if ($totalScore <= 60) {
                                    $evaluationScore = 0;
                                } elseif ($totalScore <= 70) {
                                    $evaluationScore = 15;
                                } elseif ($totalScore <= 80) {
                                    $evaluationScore = 30;
                                } elseif ($totalScore <= 90) {
                                    $evaluationScore = 45;
                                } else {
                                    $evaluationScore = 60;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Number --}}
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                {{-- Name --}}
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $evaluation->evaluatee->name_kh }}
                                    </p>
                                    @if ($evaluation->evaluatee->name_en)
                                        <p class="text-sm text-gray-500 mt-0.5">
                                            {{ $evaluation->evaluatee->name_en }}
                                        </p>
                                    @endif
                                </td>
                                {{-- Position --}}
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $evaluation->evaluatee->position ?? '-' }}
                                </td>
                                {{-- Activity Count --}}
                                <td class="px-6 py-4 text-center text-gray-600">
                                    {{ $evaluation->workPerformance->count() }}
                                </td>
                                {{-- Total Score --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-gray-800">
                                        {{ number_format($totalScore, 2) }}
                                    </span>
                                </td>
                                {{-- Evaluation Score --}}

                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-blue-600">
                                        {{ $evaluationScore }} / 60
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
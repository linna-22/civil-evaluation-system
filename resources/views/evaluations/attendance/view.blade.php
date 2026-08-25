@extends('layouts.app')

@section('title', 'លទ្ធផលវាយតម្លៃវត្តមាន')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-6">

    {{-- =====================================================
        Header
    ====================================================== --}}

    <div class="mb-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-xl font-title text-gray-800">
                    លទ្ធផលវាយតម្លៃវត្តមាន
                </h1>
            </div>
            {{-- Back --}}
            <button
                type="button"
                onclick="window.history.back()"
                class="inline-flex items-center gap-2
                       px-4 py-2
                       rounded-lg
                       border border-gray-300
                       text-gray-600
                       text-sm
                       font-medium
                       hover:bg-gray-50
                       cursor-pointer
                       transition">

                <i
                    data-lucide="arrow-left"
                    class="w-4 h-4">
                </i>

                ត្រឡប់ក្រោយ

            </button>

        </div>

    </div>


    {{-- =====================================================
        Evaluation Period
    ====================================================== --}}

    <div
        class="bg-blue-50
               border border-blue-200
               rounded-xl
               px-5 py-4
               mb-6">

        <div class="flex items-center gap-3">

            <div
                class="w-10 h-10
                       rounded-lg
                       bg-blue-100
                       text-blue-600
                       flex items-center justify-center">

                <i
                    data-lucide="calendar"
                    class="w-5 h-5">
                </i>

            </div>

            <div>
                <p class="text-sm text-blue-600 mt-0.5">
                    {{ $evaluationPeriod->name_kh ?? '-' }}
                </p>
            </div>
        </div>
    </div>


    {{-- =====================================================
        Summary
    ====================================================== --}}

    <div
        class="bg-white
               rounded-xl
               border border-gray-200
               shadow-sm
               mb-6">

        <div
            class="px-6 py-5
                   border-b border-gray-100">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        លទ្ធផលវាយតម្លៃវត្តមានរបស់មន្ត្រី
                    </h2>

                </div>

                <div
                    class="inline-flex
                           items-center
                           gap-2
                           px-3 py-1.5
                           rounded-full
                           bg-blue-50
                           text-blue-700
                           text-sm
                           font-medium">

                    <i
                        data-lucide="users"
                        class="w-4 h-4">
                    </i>

                    មន្ត្រីសរុប {{ $users->count() }} នាក់

                </div>

            </div>

        </div>


        {{-- =================================================
            Table
        ================================================== --}}

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="bg-gray-50 border-b border-gray-200">

                        <th class="px-5 py-4 text-left font-semibold text-gray-600">
                            ល.រ
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-600">
                            មន្ត្រី
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-gray-600">
                            ឈប់មានច្បាប់
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-gray-600">
                            ឈប់អត់ច្បាប់
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-gray-600">
                            មកយឺត
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-gray-600">
                            ចេញមុន
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-gray-600">
                            ភាគរយ
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-gray-600">
                            ពិន្ទុ
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($users as $index => $user)

                        @php

                            $evaluation =
                                $evaluations->get(
                                    $user->user_id
                                );

                            $attendance =
                                $evaluation?->attendance;

                        @endphp


                        <tr class="hover:bg-gray-50 transition">

                            {{-- Number --}}

                            <td class="px-5 py-4 text-gray-500">

                                {{ $index + 1 }}

                            </td>


                            {{-- User --}}

                            <td class="px-5 py-4">

                                <p class="font-medium text-gray-800">

                                    {{ $user->name_kh }}

                                </p>

                                <p class="text-xs text-gray-400 mt-1">

                                    {{ $user->id_code ?? '-' }}

                                </p>

                            </td>


                            {{-- Approved Leave --}}

                            <td class="px-5 py-4 text-center text-gray-600">

                                {{ $attendance?->approved_leave_count ?? 0 }}

                            </td>


                            {{-- Unapproved Leave --}}

                            <td class="px-5 py-4 text-center text-gray-600">

                                {{ $attendance?->unapproved_leave_count ?? 0 }}

                            </td>


                            {{-- Late --}}

                            <td class="px-5 py-4 text-center text-gray-600">

                                {{ $attendance?->late_hours ?? 0 }}

                            </td>


                            {{-- Leave Early --}}

                            <td class="px-5 py-4 text-center text-gray-600">

                                {{ $attendance?->leave_early_hours ?? 0 }}

                            </td>


                            {{-- Percentage --}}

                            <td class="px-5 py-4 text-center">

                                @if ($attendance)

                                    <span
                                        class="inline-flex
                                               items-center
                                               px-3 py-1
                                               rounded-full
                                               bg-blue-50
                                               text-blue-700
                                               font-semibold">

                                        {{ number_format(
                                            $attendance->attendance_percent,
                                            2
                                        ) }}%

                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Score --}}

                            <td class="px-5 py-4 text-center">

                                @if ($attendance)

                                    <span
                                        class="inline-flex
                                               items-center
                                               px-3 py-1
                                               rounded-full
                                               bg-green-50
                                               text-green-700
                                               font-bold">

                                        {{ $attendance->attendance_score }}

                                        <span class="ml-1 font-normal">
                                            / 20
                                        </span>

                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        -
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="8"
                                class="px-5 py-10
                                       text-center
                                       text-gray-500">
                                មិនមានទិន្នន័យវាយតម្លៃទេ។
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@extends('layouts.app')

@section('content')
    @php
        use App\Helpers\DateHelper;

        $user = $result?->evaluationPeriodUser?->user;

        $workScore = (float) ($result?->work_performance_score ?? 0);
        $attendanceScore = (float) ($result?->attendance_score ?? 0);
        $behaviorScore = (float) ($result?->behavior_score ?? 0);
        $totalScore = (float) ($result?->total_score ?? 0);

        $workPercent = min(($workScore / 60) * 100, 100);
        $attendancePercent = min(($attendanceScore / 20) * 100, 100);
        $behaviorPercent = min(($behaviorScore / 20) * 100, 100);
        $totalPercent = min($totalScore, 100);

        /*
    |--------------------------------------------------------------------------
    | Evaluation Comment
    |--------------------------------------------------------------------------
    */

        if ($totalScore >= 90) {
            $comment = 'ល្អប្រសើរ';
            $commentClass = 'text-emerald-700 bg-emerald-50 border-emerald-100';
        } elseif ($totalScore >= 80) {
            $comment = 'ល្អ';
            $commentClass = 'text-blue-700 bg-blue-50 border-blue-100';
        } elseif ($totalScore >= 70) {
            $comment = 'មធ្យម';
            $commentClass = 'text-amber-700 bg-amber-50 border-amber-100';
        } elseif ($totalScore >= 60) {
            $comment = 'ត្រូវការកែលម្អ';
            $commentClass = 'text-orange-700 bg-orange-50 border-orange-100';
        } else {
            $comment = 'ត្រូវការកែលម្អខ្លាំង';
            $commentClass = 'text-red-700 bg-red-50 border-red-100';
        }
    @endphp


    <div class="min-h-screen bg-slate-50">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <x-page-header title="លទ្ធផល{{ $evaluationPeriod->name_kh }}" description="">

                <x-slot:actions>

                    <x-action-btn href="{{ route('my-evaluation-results.index') }}" icon="arrow-left" variant="secondary">

                        ត្រឡប់ក្រោយ

                    </x-action-btn>

                </x-slot:actions>

            </x-page-header>

            @if ($result && $user)
                {{-- =====================================================
                USER INFORMATION + TOTAL
            ====================================================== --}}

                <div class="grid grid-cols-1 lg:grid-cols-3
                        gap-6 py-3">


                    {{-- User Information --}}

                    <div
                        class="lg:col-span-2
                           bg-white
                           rounded-2xl
                           border border-slate-200
                           shadow-sm
                           overflow-hidden">

                        <div class="px-6 py-5
                               border-b border-slate-100">

                            <div class="flex items-center gap-3">

                                <div
                                    class="w-11 h-11
                                       rounded-xl
                                       bg-blue-50
                                       text-blue-600
                                       flex items-center justify-center">

                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                                                       M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>

                                </div>

                                <div>
                                    <h2 class="font-bold text-slate-800">
                                        ព័ត៌មានមន្ត្រី
                                    </h2>
                                </div>

                            </div>

                        </div>


                        <div class="p-6">

                            <div
                                class="grid grid-cols-1
                                    sm:grid-cols-2
                                    gap-6">

                                <div>

                                    <p
                                        class="text-xs
                                          font-medium
                                          text-slate-400 mb-1">

                                        ឈ្មោះមន្ត្រី

                                    </p>

                                    <p class="font-semibold text-slate-800">

                                        {{ $user->name_kh }}

                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-xs
                                          font-medium
                                          text-slate-400 mb-1">

                                        តួនាទី

                                    </p>

                                    <p class="font-semibold text-slate-800">

                                        {{ $user->position ?? 'មិនទាន់មាន' }}

                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-xs
                                          font-medium
                                          text-slate-400 mb-1">

                                        វគ្គវាយតម្លៃ

                                    </p>

                                    <p class="font-semibold text-slate-800">

                                        {{ $evaluationPeriod->name_kh }}

                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-xs
                                          font-medium
                                          text-slate-400 mb-1">

                                        ស្ថានភាព

                                    </p>

                                    <span
                                        class="inline-flex
                                           items-center gap-1.5
                                           px-2.5 py-1
                                           rounded-lg
                                           bg-emerald-50
                                           text-emerald-700
                                           text-xs
                                           font-semibold">

                                        <span
                                            class="w-1.5 h-1.5
                                               rounded-full
                                               bg-emerald-500"></span>

                                        បានបញ្ចប់

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Total Score --}}

                    <div
                        class="relative overflow-hidden
                           rounded-2xl
                           bg-gradient-to-br
                           from-blue-600
                           to-blue-700
                           shadow-sm">

                        <div
                            class="absolute
                               -right-12 -top-12
                               w-40 h-40
                               rounded-full
                               bg-white/10">
                        </div>

                        <div
                            class="absolute
                               -right-16 -bottom-16
                               w-44 h-44
                               rounded-full
                               bg-white/3">
                        </div>


                        <div
                            class="relative
                               p-6 h-full
                               flex flex-col
                               justify-between">

                            <div>

                                <p class="text-sm
                                      text-blue-100">

                                    ពិន្ទុសរុប

                                </p>

                                <p class="text-xs
                                      text-blue-200 mt-1">

                                    លទ្ធផលសរុបនៃការវាយតម្លៃ

                                </p>

                            </div>


                            <div class="mt-8">

                                <div class="flex items-end gap-2">

                                    <span
                                        class="text-5xl
                                           sm:text-6xl
                                           font-bold
                                           tracking-tight
                                           text-white">

                                        {{ DateHelper::toKhmerNumber(number_format($totalScore, 2)) }}

                                    </span>

                                    <span
                                        class="text-sm
                                           text-blue-200
                                           mb-2">
                                        / ១០០
                                    </span>

                                </div>


                                <div
                                    class="mt-5
                                       h-2
                                       rounded-full
                                       bg-white/20
                                       overflow-hidden">

                                    <div class="h-full
                                           rounded-full
                                           bg-white"
                                        style="width: {{ $totalPercent }}%"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                SCORE TABLE
            ====================================================== --}}

                <div
                    class="bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       overflow-hidden">

                    {{-- Header --}}

                    <div class="px-6 py-5
                           border-b border-slate-100">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-10 h-10
                                   rounded-xl
                                   bg-blue-50
                                   text-blue-600
                                   flex items-center justify-center">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6
                                                   m2 9H7a2 2 0 01-2-2V5
                                                   a2 2 0 012-2h10a2 2 0 012 2v11
                                                   a2 2 0 01-2 2z" />
                                </svg>

                            </div>

                            <div>

                                <h2 class="font-bold text-slate-800">

                                    លម្អិតពិន្ទុការវាយតម្លៃ

                                </h2>

                                <p class="text-xs
                                      text-slate-400 mt-1">

                                    ពិន្ទុតាមផ្នែកនីមួយៗ

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Responsive Table --}}

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[650px]">

                            <thead>

                                <tr class="bg-slate-50
                                       border-b border-slate-100">

                                    <th
                                        class="px-6 py-4
                                           text-left
                                           text-xs
                                           font-semibold
                                           text-slate-500">

                                        ល.រ

                                    </th>

                                    <th
                                        class="px-6 py-4
                                           text-left
                                           text-xs
                                           font-semibold
                                           text-slate-500">

                                        ផ្នែកវាយតម្លៃ

                                    </th>

                                    <th
                                        class="px-6 py-4
                                           text-right
                                           text-xs
                                           font-semibold
                                           text-slate-500">

                                        ពិន្ទុទទួលបាន

                                    </th>

                                    <th
                                        class="px-6 py-4
                                           text-right
                                           text-xs
                                           font-semibold
                                           text-slate-500">

                                        ពិន្ទុអតិបរមា

                                    </th>

                                    <th
                                        class="px-6 py-4
                                           text-right
                                           text-xs
                                           font-semibold
                                           text-slate-500">

                                        ភាគរយ

                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-100">

                                {{-- Work Performance --}}

                                <tr class="hover:bg-slate-50
                                       transition">

                                    <td
                                        class="px-6 py-5
                                           text-sm
                                           text-slate-400">
                                        ១
                                    </td>

                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-slate-800">
                                                សមិទ្ធកម្មការងារ
                                            </span>
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-5
                                           text-right
                                           font-bold
                                           text-slate-800">

                                        {{ DateHelper::toKhmerNumber(number_format($workScore, 2)) }}

                                    </td>

                                    <td
                                        class="px-6 py-5
                                           text-right
                                           text-slate-500">

                                        ៦០

                                    </td>

                                    <td class="px-6 py-5
                                           text-right">

                                        <span
                                            class="font-semibold
                                               text-blue-600">

                                            {{ DateHelper::toKhmerNumber(number_format($workPercent, 1)) }}%

                                        </span>

                                    </td>

                                </tr>


                                {{-- Attendance --}}

                                <tr class="hover:bg-slate-50
                                       transition">

                                    <td
                                        class="px-6 py-5
                                           text-sm
                                           text-slate-400">
                                        ២
                                    </td>

                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-slate-800">
                                                វត្តមាន
                                            </span>

                                        </div>

                                    </td>

                                    <td
                                        class="px-6 py-5
                                           text-right
                                           font-bold
                                           text-slate-800">

                                        {{ DateHelper::toKhmerNumber(number_format($attendanceScore, 2)) }}

                                    </td>

                                    <td
                                        class="px-6 py-5
                                           text-right
                                           text-slate-500">

                                        ២០

                                    </td>

                                    <td class="px-6 py-5
                                           text-right">

                                        <span
                                            class="font-semibold
                                               text-sky-600">

                                            {{ DateHelper::toKhmerNumber(number_format($attendancePercent, 1)) }}%

                                        </span>

                                    </td>

                                </tr>


                                {{-- Behavior --}}

                                <tr class="hover:bg-slate-50
                                       transition">

                                    <td
                                        class="px-6 py-5
                                           text-sm
                                           text-slate-400">
                                        ៣
                                    </td>

                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-slate-800">
                                                ឥរិយាបថ
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right font-bold text-slate-800">
                                        {{ DateHelper::toKhmerNumber(number_format($behaviorScore, 2)) }}
                                    </td>
                                    <td class="px-6 py-5 text-right text-slate-500">
                                        ២០
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="font-semibold text-violet-600">
                                            {{ DateHelper::toKhmerNumber(number_format($behaviorPercent, 1)) }}%
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                            {{-- Total --}}
                            <tfoot>
                                <tr
                                    class="bg-blue-50
                                       border-t
                                       border-blue-100">
                                    <td colspan="2" class="px-6 py-5">

                                        <span
                                            class="font-bold
                                               text-blue-900">

                                            ពិន្ទុសរុប

                                        </span>

                                    </td>

                                    <td
                                        class="px-6 py-5
                                           text-right
                                           font-bold
                                           text-blue-700">

                                        {{ DateHelper::toKhmerNumber(number_format($totalScore, 2)) }}

                                    </td>

                                    <td
                                        class="px-6 py-5
                                           text-right
                                           font-medium
                                           text-blue-700">

                                        ១០០

                                    </td>

                                    <td
                                        class="px-6 py-5
                                           text-right
                                           font-bold
                                           text-blue-700">

                                        {{ DateHelper::toKhmerNumber(number_format($totalPercent, 1)) }}%

                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 border-t {{ $commentClass }}">
                                        <div class="flex items-center justify-between gap-4">

                                            <span class="font-semibold">
                                                លទ្ធផលវាយតម្លៃ៖
                                            </span>

                                            <span class="font-bold">
                                                {{ $comment }}
                                            </span>

                                        </div>
                                    </td>
                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>


                {{-- =====================================================
                CALCULATED INFO
            ====================================================== --}}

                <div
                    class="mt-5
                       flex flex-col
                       sm:flex-row
                       sm:items-center
                       sm:justify-between
                       gap-3">

                    <div class="flex items-center gap-2
                           text-sm text-slate-500">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2
                                           m6-2a9 9 0 11-18 0
                                           9 9 0 0118 0z" />
                        </svg>

                        <span>
                            ការវាយតម្លៃត្រូវបានបិទនៅ
                        </span>

                        <span class="font-medium text-slate-700">

                            {{ DateHelper::khmerDateTime($result->calculated_at) }}នាទី

                        </span>

                    </div>


                </div>
            @else
                {{-- =====================================================
                NO RESULT
            ====================================================== --}}

                <div class="flex items-center
                       justify-center
                       min-h-[500px]">

                    <div
                        class="bg-white
                           rounded-2xl
                           border border-slate-200
                           shadow-sm
                           max-w-md
                           w-full
                           p-10
                           text-center">

                        <div
                            class="w-16 h-16
                               mx-auto
                               rounded-2xl
                               bg-amber-50
                               text-amber-600
                               flex items-center
                               justify-center
                               mb-5">

                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v4m0 4h.01
                                               M10.29 3.86L1.82 18
                                               a2 2 0 001.71 3h16.94
                                               a2 2 0 001.71-3L13.71 3.86
                                               a2 2 0 00-3.42 0z" />
                            </svg>

                        </div>


                        <h2
                            class="text-xl
                               font-bold
                               text-slate-800">

                            មិនទាន់មានលទ្ធផល

                        </h2>


                        <p
                            class="text-sm
                               text-slate-500
                               mt-2
                               leading-6">

                            មិនទាន់មានលទ្ធផលការវាយតម្លៃ
                            សម្រាប់វគ្គនេះទេ។

                        </p>


                        <a href="{{ route('my-evaluation-results.index') }}"
                            class="inline-flex
                               items-center
                               justify-center
                               mt-6
                               px-5 py-2.5
                               rounded-xl
                               bg-blue-600
                               text-white
                               text-sm
                               font-semibold
                               hover:bg-blue-700
                               transition">

                            ត្រឡប់ក្រោយ

                        </a>

                    </div>

                </div>
            @endif

        </div>

    </div>
@endsection

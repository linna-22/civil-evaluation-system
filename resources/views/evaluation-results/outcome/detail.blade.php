@extends('layouts.app')

@section('title', $config['title'])

@section('content')

    @php
        // Finalization data is only available for Department Admins.
        // Keep safe defaults so the view never fails for other allowed roles.
        $departmentPeriod = $departmentPeriod ?? null;
        $isFinalized = $isFinalized ?? false;

        $monthNames = [
            1 => 'មករា',
            2 => 'កុម្ភៈ',
            3 => 'មីនា',
            4 => 'មេសា',
            5 => 'ឧសភា',
            6 => 'មិថុនា',
            7 => 'កក្កដា',
            8 => 'សីហា',
            9 => 'កញ្ញា',
            10 => 'តុលា',
            11 => 'វិច្ឆិកា',
            12 => 'ធ្នូ',
        ];

        $backRoute = match ($type) {
            'work-performance' => route('evaluation-results.work-performance.index'),
            'attendance' => route('evaluation-results.attendance.index'),
            'behavior' => route('evaluation-results.behavior.index'),
            'overall' => route('evaluation-results.overall.index'),
        };
    @endphp

    <div class="space-y-5">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                {{-- <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5 text-blue-600"></i>
            </div> --}}
                <div>
                    <h1 class="text-xl font-title text-gray-800">{{ $config['title'] }}</h1>
                    {{-- <p class="mt-1 text-sm text-gray-500">{{ $config['description'] }}</p> --}}
                </div>
            </div>

            <a href="{{ $backRoute }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                ត្រឡប់ក្រោយ
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-500">វគ្គវាយតម្លៃ</p>
                    <h2 class="text-lg font-bold text-gray-800">
                        {{ $evaluationPeriod->name_kh ?? 'ខែ ' . ($monthNames[$evaluationPeriod->month] ?? $evaluationPeriod->month) . ' ' . $evaluationPeriod->year }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $evaluationPeriod->start_date?->format('d/m/Y') ?? '—' }} -
                        {{ $evaluationPeriod->end_date?->format('d/m/Y') ?? '—' }}
                    </p>
                </div>
                @if ($departmentPeriod)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        @if ($isFinalized)
                            <span
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-semibold">
                                <i data-lucide="badge-check" class="w-4 h-4"></i>
                                បានបញ្ចប់
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 text-sm font-semibold">
                                <i data-lucide="clock-3" class="w-4 h-4"></i>
                                កំពុងពិនិត្យ
                            </span>

                            @if ($type === 'overall' && $evaluationPeriod->status === 'closed')
                                <form id="finalize-evaluation-form" method="POST"
                                    action="{{ route('evaluation-results.finalize', [$type, $evaluationPeriod->evaluation_period_id]) }}">
                                    @csrf
                                    <button type="submit" id="finalize-evaluation-button"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">
                                        <i data-lucide="check-check" class="w-4 h-4"></i>
                                        បានពិនិត្យនិងឯកភាព
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Same real-time filter pattern used by the existing department results page --}}
        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 mb-3">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                {{-- Left: Search + Office Filter --}}
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                    {{-- Search --}}
                    <div class="w-full sm:w-80 lg:w-80">
                        <x-search-box id="evaluation-outcome-search" />
                    </div>

                    {{-- Office Filter --}}
                    @if ($offices->isNotEmpty())
                        <div class="w-full sm:w-64 lg:w-70">
                            <x-filters.office-filter id="evaluation-outcome-office" :offices="$offices" />
                        </div>
                    @endif

                </div>

                {{-- Right: Per Page --}}
                <div class="flex justify-end w-full lg:w-auto">
                    <x-per-page id="evaluation-outcome-per-page" />
                </div>

            </div>
        </div>

        <style>
            .evaluation-outcome-table-scroll {
                overflow-x: auto;
                width: 100%;
            }

            .evaluation-outcome-table-scroll table {
                width: 100%;
                min-width: 0;
                table-layout: auto;
            }

            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th,
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td {
                padding: 10px 8px !important;
            }

            /* ល.រ */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(1),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(1) {
                width: 55px;
                text-align: center;
            }

            /* អត្តលេខ */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(2),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(2) {
                width: 105px;
            }

            /* គោត្តនាមនិងនាម */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(3),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(3) {
                width: 170px;
            }

            /* ភេទ */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(4),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(4) {
                width: 65px;
            }

            /* តួនាទី */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(5),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(5) {
                width: 150px;
            }

            /* Overall score columns */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(6),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(6),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(7),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(7),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(8),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(8),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(9),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(9) {
                width: 90px;
            }

            /* មូលវិចារណ៍ */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(10),
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(10) {
                width: 100px;
                padding-left: 45px !important;
            }


            /* សកម្មភាព */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:last-child,
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:last-child {
                width: 105px;
                white-space: nowrap;
            }

            /* Single-score pages */
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) th:nth-child(6):last-child,
            .evaluation-outcome-table-scroll table:has(#evaluation-outcome-table-body) td:nth-child(6):last-child {
                width: 100px;
            }
        </style>

        <div class="bg-white rounded-2xl">
            <div class="data-table-scroll evaluation-outcome-table-scroll">
                <x-data-table bodyId="evaluation-outcome-table-body">
                    <x-slot:head>
                        <th class="px-2 py-3 text-center w-[75px]">ល.រ</th>

                        <th class="px-2 py-3 text-left w-[105px]">
                            អត្តលេខ
                        </th>

                        <th class="px-2 py-3 text-left w-[170px]">
                            គោត្តនាមនិងនាម
                        </th>

                        <th class="px-2 py-3 text-left w-[65px]">
                            ភេទ
                        </th>

                        <th class="px-2 py-3 text-left w-[170px]">
                            តួនាទី
                        </th>

                        @if ($type === 'overall')
                            <th class="px-2 py-3 text-center w-[100px]">
                                សមិទ្ធកម្ម
                            </th>

                            <th class="px-2 py-3 text-center w-[100px]">
                                វត្តមាន
                            </th>

                            <th class="px-2 py-3 text-center w-[100px]">
                                ឥរិយាបថ
                            </th>

                            <th class="px-2 py-3 text-center w-[100px]">
                                ពិន្ទុសរុប
                            </th>
                        @else
                            <th class="px-4 py-3 text-center w-[150px]">
                                ពិន្ទុ
                            </th>
                        @endif

                        @if ($type === 'overall')
                            <th class="px-2 py-3 text-start w-[150px]">
                                មូលវិចារណ៍
                            </th>
                        @endif

                        <th class="px-2 py-3 text-center w-[95px]">
                            សកម្មភាព
                        </th>
                    </x-slot:head>

                    <x-slot:body>
                        <tbody id="evaluation-outcome-table-body"></tbody>
                    </x-slot:body>
                </x-data-table>
            </div>
        </div>

        <div id="evaluation-outcome-pagination" class="mt-6"></div>

        @if ($type === 'overall')
            <x-remarks-modal />
        @endif

    </div>

    <script>
        window.evaluationOutcome = {
            periodId: @json($evaluationPeriod->evaluation_period_id),
            type: @json($type),
            isFinalized: @json($isFinalized ?? false),
        };
    </script>

    @vite(['resources/js/pages/evaluation-results/outcome.js'])
@endsection

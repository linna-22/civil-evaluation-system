@extends('layouts.app')

@section('title', $config['title'])

@section('content')

@php
    $monthNames = [
        1 => 'មករា', 2 => 'កុម្ភៈ', 3 => 'មីនា', 4 => 'មេសា',
        5 => 'ឧសភា', 6 => 'មិថុនា', 7 => 'កក្កដា', 8 => 'សីហា',
        9 => 'កញ្ញា', 10 => 'តុលា', 11 => 'វិច្ឆិកា', 12 => 'ធ្នូ',
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
                    {{ $evaluationPeriod->name_kh ?? ('ខែ ' . ($monthNames[$evaluationPeriod->month] ?? $evaluationPeriod->month) . ' ' . $evaluationPeriod->year) }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $evaluationPeriod->start_date?->format('d/m/Y') ?? '—' }} -
                    {{ $evaluationPeriod->end_date?->format('d/m/Y') ?? '—' }}
                </p>
            </div>
            {{-- <div class="flex items-center gap-2 text-sm">
                <span class="px-3 py-1.5 rounded-full bg-green-50 text-green-700 font-semibold">
                    {{ $evaluationPeriod->status === 'closed' ? 'បានបិទ' : ($evaluationPeriod->status ?? '—') }}
                </span>
            </div> --}}
        </div>
    </div>

    {{-- Same real-time filter pattern used by the existing department results page --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-3">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <x-search-box id="evaluation-outcome-search" />

                @if ($offices->isNotEmpty())
                    <x-filters.office-filter
                        id="evaluation-outcome-office"
                        :offices="$offices"
                    />
                @endif
            </div>

            <div class="flex items-center gap-3">
                <x-per-page id="evaluation-outcome-per-page" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl">
        <div class="data-table-scroll">
            <x-data-table bodyId="evaluation-outcome-table-body">
                <x-slot:head>
                    <th class="px-6 py-3 text-left w-20">ល.រ</th>
                    <th class="px-6 py-3 text-left">អត្តលេខ</th>
                    <th class="px-6 py-3 text-left">គោត្តនាមនិងនាម</th>
                    <th class="px-6 py-3 text-left">ភេទ</th>
                    <th class="px-6 py-3 text-left">តួនាទី</th>

                    @if ($type === 'overall')
                        <th class="px-6 py-3 text-center">សមិទ្ធកម្ម</th>
                        <th class="px-6 py-3 text-center">វត្តមាន</th>
                        <th class="px-6 py-3 text-center">ឥរិយាបថ</th>
                        <th class="px-6 py-3 text-center">ពិន្ទុសរុប</th>
                    @else
                        <th class="px-6 py-3 text-center">ពិន្ទុ</th>
                    @endif

                    @if ($type === 'overall')
                        <th class="px-6 py-3 text-left">មូលវិចារណ៍</th>
                    @endif

                    <th class="px-6 py-3 text-center">សកម្មភាព</th>
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
    };
</script>

@vite(['resources/js/pages/evaluation-results/outcome.js'])
@endsection

@extends('layouts.app')

@section('title', $title)

@section('content')

@php
    use App\Helpers\DateHelper;

    $months = [
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

    $icons = [
        'work-performance' => 'clipboard-check',
        'attendance' => 'calendar-check',
        'behavior' => 'user-check',
        'overall' => 'chart-no-axes-combined',
    ];

    /*
     * Only closed evaluation periods should be displayed.
     *
     * Open periods are still kept in the system for data entry,
     * but their results should not appear on this page until
     * the evaluation period has been closed.
     */
    $closedPeriods = $evaluationPeriods->where('status', 'closed');
@endphp

<div class="min-h-screen bg-[#f5f8ff]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <x-page-header
            title="{{ $title }}"
            description="{{ $description }}"
        />

        {{-- No closed evaluation periods --}}
        @if ($closedPeriods->isEmpty())

            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-10 sm:p-14 text-center">

                <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-50 flex items-center justify-center mb-5">
                    <i
                        data-lucide="calendar-x"
                        class="w-8 h-8 text-gray-500"
                    ></i>
                </div>

                <h2 class="text-xl font-bold text-gray-500">
                    មិនទាន់មានវគ្គវាយតម្លៃ
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    បច្ចុប្បន្នមិនមានវគ្គវាយតម្លៃទេ។
                </p>

            </div>

        @else

            {{-- Closed evaluation periods --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                @foreach ($closedPeriods as $period)

                    @php
                        $periodName = $period->name_kh
                            ?: 'ខែ' . ($months[$period->month] ?? $period->month) . ' ' . $period->year;

                        $viewUrl = match ($type) {
                            'work-performance' => route(
                                'evaluation-results.work-performance.show',
                                $period->evaluation_period_id
                            ),

                            'attendance' => route(
                                'evaluation-results.attendance.show',
                                $period->evaluation_period_id
                            ),

                            'behavior' => route(
                                'evaluation-results.behavior.show',
                                $period->evaluation_period_id
                            ),

                            'overall' => route(
                                'evaluation-results.overall.show',
                                $period->evaluation_period_id
                            ),
                        };
                    @endphp

                    <div
                        class="bg-white rounded-2xl border border-blue-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-200 overflow-hidden"
                    >

                        <div class="p-5 sm:p-6">

                            {{-- Card header --}}
                            <div class="flex items-start justify-between gap-4">

                                <div class="flex items-start gap-4 min-w-0">

                                    {{-- Icon --}}
                                    <div
                                        class="w-12 h-12 shrink-0 rounded-xl bg-blue-50 flex items-center justify-center"
                                    >
                                        <i
                                            data-lucide="{{ $icons[$type] ?? 'calendar-days' }}"
                                            class="w-6 h-6 text-[#287cfb]"
                                        ></i>
                                    </div>

                                    {{-- Period information --}}
                                    <div class="min-w-0">

                                        <h2 class="text-lg font-bold text-gray-800 truncate">
                                            {{ $periodName }}
                                        </h2>

                                        <div
                                            class="mt-2 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-500"
                                        >

                                            {{-- Month / Year --}}
                                            <div class="flex items-center gap-1.5">

                                                <i
                                                    data-lucide="calendar-days"
                                                    class="w-4 h-4 text-gray-400"
                                                ></i>

                                                <span>
                                                    ខែ{{ $months[$period->month] ?? $period->month }}
                                                    ឆ្នាំ{{ DateHelper::toKhmerNumber($period->year) }}
                                                </span>

                                            </div>

                                            {{-- Participants --}}
                                            <div class="flex items-center gap-1.5">

                                                <i
                                                    data-lucide="users"
                                                    class="w-4 h-4 text-gray-400"
                                                ></i>

                                                <span>
                                                    {{ $period->participants_count }} នាក់
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- Card footer --}}
                            <div
                                class="mt-5 pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                            >

                                {{-- Evaluation date --}}
                                <div class="text-sm text-gray-500">

                                    @if ($period->start_date && $period->end_date)

                                        {{ DateHelper::toKhmerNumber($period->start_date->format('d/m/Y')) }}

                                        -

                                        {{ DateHelper::toKhmerNumber($period->end_date->format('d/m/Y')) }}

                                    @else

                                        មិនមានកាលបរិច្ឆេទ

                                    @endif

                                </div>

                                {{-- View button --}}
                                <a
                                    href="{{ $viewUrl }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition"
                                >

                                    <i
                                        data-lucide="eye"
                                        class="w-4 h-4"
                                    ></i>

                                    ពិនិត្យ

                                    <i
                                        data-lucide="arrow-right"
                                        class="w-4 h-4"
                                    ></i>

                                </a>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            window.lucide.createIcons();
        }
    });
</script>
@endpush
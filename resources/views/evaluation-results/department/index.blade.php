@extends('layouts.app')

@section('content')
@php
        use App\Helpers\DateHelper;
        use Carbon\Carbon;

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
    @endphp

<div class="min-h-screen bg-[#f5f8ff]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            {{-- PAGE HEADER --}}
            <x-page-header title="លទ្ធផលវាយតម្លៃ" description="">
            </x-page-header>
            {{-- PERIOD LIST --}}
            @if ($periods->count())
                <div class="space-y-4 py-3">
                    @foreach ($periods as $period)
                        <div
                            class="bg-white rounded-2xl
                               border border-blue-100
                               shadow-sm
                               hover:shadow-md
                               hover:border-blue-200
                               transition-all duration-200
                               overflow-hidden">
                            <div
                                class="p-5 sm:p-6
                                   flex flex-col lg:flex-row
                                   lg:items-center
                                   lg:justify-between
                                   gap-5">
                                {{-- PERIOD INFORMATION --}}
                                <div class="flex items-start gap-4">
                                    {{-- Icon --}}
                                    <div
                                        class="w-12 h-12
                                           shrink-0
                                           rounded-xl
                                           bg-blue-50
                                           flex items-center justify-center">

                                        <svg class="w-6 h-6 text-[#287cfb]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    {{-- Information --}}
                                    <div>
                                        <h2 class="text-lg font-bold text-gray-800">
                                            លទ្ធផល{{ $period->name_kh }}
                                        </h2>
                                        <div
                                            class="flex flex-wrap
                                               items-center
                                               gap-x-5 gap-y-2
                                               mt-2">
                                            {{-- Month / Year --}}
                                            <div
                                                class="flex items-center gap-1.5
                                                   text-sm text-gray-500">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>
                                                    ខែ{{ $months[$period->month] }}
                                                    ឆ្នាំ
                                                    {{ DateHelper::toKhmerNumber($period->year) }}
                                                </span>
                                            </div>
                                            {{-- Date --}}
                                            <div
                                                class="flex items-center gap-1.5
                                                   text-sm text-gray-500">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>
                                                    {{ DateHelper::toKhmerNumber(Carbon::parse($period->start_date)->format('d/m/Y')) }}
                                                    -
                                                    {{ DateHelper::toKhmerNumber(Carbon::parse($period->end_date)->format('d/m/Y')) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- RIGHT SIDE --}}
                                <div
                                    class="flex flex-col sm:flex-row
                                       sm:items-center
                                       gap-3">

                                    {{-- Status --}}

                                    @if ($period->status === 'closed')
                                        <span
                                            class="inline-flex
                                               items-center justify-center
                                               gap-2
                                               px-3 py-2
                                               rounded-lg
                                               bg-red-50
                                               border border-red-100
                                               text-red-700
                                               text-sm font-medium">

                                            <span
                                                class="w-2 h-2
                                                   rounded-full
                                                   bg-red-500"></span>

                                            ការវាយតម្លៃបានបិទ

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex
                                               items-center justify-center
                                               gap-2
                                               px-3 py-2
                                               rounded-lg
                                               bg-blue-50
                                               border border-blue-100
                                               text-[#287cfb]
                                               text-sm font-medium">

                                            <span
                                                class="w-2 h-2
                                                   rounded-full
                                                   bg-[#287cfb]"></span>

                                            កំពុងវាយតម្លៃ

                                        </span>
                                    @endif
                                    {{-- View Result Button --}}
                                    @if ($period->status === 'closed')
                                        <a href="{{ route('department-evaluation-results.show', $period->evaluation_period_id) }}"
                                            class="inline-flex items-center gap-2
                                                    px-4 py-2
                                                    rounded-xl
                                                    bg-blue-600
                                                    text-white
                                                    text-sm font-semibold
                                                    hover:bg-blue-700
                                                    transition">
                                            មើលលទ្ធផលវាយតម្លៃ
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="bg-white
                       rounded-2xl
                       border border-blue-100
                       shadow-sm
                       p-10 sm:p-14
                       text-center">
                    <div
                        class="w-16 h-16
                           mx-auto
                           rounded-2xl
                           bg-blue-50
                           flex items-center justify-center
                           mb-5">
                        <svg class="w-8 h-8 text-[#287cfb]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">
                        មិនទាន់មានវគ្គវាយតម្លៃ
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">
                        បច្ចុប្បន្នអ្នកមិនទាន់មានវគ្គវាយតម្លៃណាមួយទេ។
                    </p>
                </div>
            @endif
        </div>
    </div>

@endsection
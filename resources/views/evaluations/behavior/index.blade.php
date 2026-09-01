@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- ==========================================
         Page Header
        =========================================== --}}

        <div class="mb-6">

            <div class="flex items-center justify-between">

                <div>

                    <h1 class="text-xl font-title text-gray-800">
                        ការវាយតម្លៃឥរិយាបថ
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        បញ្ជីមន្ត្រីដែលត្រូវចូលរួមក្នុងការវាយតម្លៃឥរិយាបថ
                    </p>

                </div>


                {{-- ==========================================
                 Start Evaluation Button
            =========================================== --}}

                @php

                    $allEvaluated =
                        $peers->isNotEmpty() &&
                        $peers->every(function ($peer) {
                            return $peer->evaluation_status === 'submitted';
                        });

                @endphp


                @if ($allEvaluated)
                    {{-- ==========================================
         View Result
    =========================================== --}}

                    <a href="{{ route('evaluations.behavior.view') }}"
                        class="
                            inline-flex
                            items-center
                            gap-2
                            px-5
                            py-2.5
                            rounded-lg
                            bg-blue-600
                            text-white
                            text-sm
                            font-medium
                            hover:bg-blue-700
                            transition
                        ">

                        <i data-lucide="eye" class="w-4 h-4"></i>

                        មើលលទ្ធផលវាយតម្លៃ

                    </a>
                @else
                    {{-- ==========================================
         Start Evaluation
    =========================================== --}}

                    @if ($peers->isNotEmpty())
                        <a href="{{ route('evaluations.behavior.create') }}"
                            class="
                                inline-flex
                                items-center
                                gap-2
                                px-5
                                py-2.5
                                rounded-lg
                                bg-blue-600
                                text-white
                                text-sm
                                font-medium
                                hover:bg-blue-700
                                transition
                            ">

                            <i data-lucide="clipboard-pen" class="w-4 h-4"></i>

                            ចាប់ផ្ដើមវាយតម្លៃ

                        </a>
                    @endif
                @endif

            </div>

        </div>


        {{-- ==========================================
         Peer Table
    =========================================== --}}

        <div
            class="
        bg-white
        rounded-xl
        border
        border-gray-200
        shadow-sm
        overflow-hidden
    ">


            @if ($peers->isEmpty())
                {{-- ==========================================
                 No Data
            =========================================== --}}

                <div class="px-6 py-12 text-center">

                    <i data-lucide="users" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>

                    <p class="text-gray-500">
                        មិនមានមន្ត្រីដែលត្រូវវាយតម្លៃទេ
                    </p>

                </div>
            @else
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        {{-- ==========================================
                         Table Header
                    =========================================== --}}

                        <thead class="bg-gray-50 border-b border-gray-200">

                            <tr>

                                <th
                                    class="
                                px-6
                                py-4
                                text-left
                                font-medium
                                text-gray-600
                            ">
                                    ល.រ
                                </th>


                                <th
                                    class="
                                px-6
                                py-4
                                text-left
                                font-medium
                                text-gray-600
                            ">
                                    ឈ្មោះមន្ត្រី
                                </th>


                                <th
                                    class="
                                px-6
                                py-4
                                text-left
                                font-medium
                                text-gray-600
                            ">
                                    ភេទ
                                </th>


                                <th
                                    class="
                                px-6
                                py-4
                                text-left
                                font-medium
                                text-gray-600
                            ">
                                    តួនាទី
                                </th>


                                <th
                                    class="
                                px-6
                                py-4
                                text-left
                                font-medium
                                text-gray-600
                            ">
                                    ស្ថានភាព
                                </th>

                            </tr>

                        </thead>


                        {{-- ==========================================
                         Table Body
                    =========================================== --}}

                        <tbody class="divide-y divide-gray-100">

                            @foreach ($peers as $index => $peer)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Number --}}

                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $index + 1 }}
                                    </td>


                                    {{-- Name --}}

                                    <td class="px-6 py-4">

                                        <div>

                                            <p class="font-medium text-gray-800">
                                                {{ $peer->name_kh }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ $peer->name_en }}
                                            </p>

                                        </div>

                                    </td>
                                    {{-- Gender --}}
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $peer->gender === 'female' ? 'ស្រី' : 'ប្រុស' }}
                                    </td>
                                    {{-- Position --}}

                                    <td class="px-6 py-4 text-gray-600">

                                        {{ $peer->position }}

                                    </td>


                                    {{-- ==========================================
                                     Evaluation Status
                                =========================================== --}}

                                    <td class="px-6 py-4">

                                        @if ($peer->evaluation_status === 'submitted')
                                            <span
                                                class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                px-3
                                                py-1.5
                                                rounded-full
                                                bg-green-50
                                                text-green-700
                                                text-xs
                                                font-medium
                                            ">

                                                <i data-lucide="circle-check" class="w-4 h-4"></i>

                                                បានវាយតម្លៃរួចរាល់

                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-50 text-red-600 text-xs font-medium">
                                                <i data-lucide="clock-3" class="w-4 h-4"></i>
                                                រង់ចាំការវាយតម្លៃ
                                            </span>
                                        @endif

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

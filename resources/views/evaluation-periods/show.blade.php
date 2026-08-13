@extends('layouts.app')

@section('title', 'ព័ត៌មានការវាយតម្លៃ')

@section('content')

    <div class="space-y-6">

        {{-- ========================================== --}}
        {{-- Page Header --}}
        {{-- ========================================== --}}

        <x-page-header
            title="ព័ត៌មានការវាយតម្លៃ"
            description="">

            <x-slot:actions>

                <x-action-btn
                    href="{{ route('evaluation-periods.index') }}"
                    variant="secondary"
                    icon="arrow-left">

                    ត្រឡប់

                </x-action-btn>

            </x-slot:actions>

        </x-page-header>


        {{-- ========================================== --}}
        {{-- Evaluation Information --}}
        {{-- ========================================== --}}

        <div class="bg-white rounded-2xl shadow-sm p-6">

            <div class="flex items-center justify-between mb-6">

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        ព័ត៌មានការវាយតម្លៃ
                    </h2>
                </div>

                <div>

                    @if ($evaluationPeriod->status === 'open')

                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">

                            កំពុងបើកការវាយតម្លៃ

                        </span>

                    @else

                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">

                            បានបិទការវាយតម្លៃ 

                        </span>

                    @endif

                </div>

            </div>
            <hr class="py-2 text-gray-300">


            {{-- Information Grid --}}

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


                {{-- Khmer Name --}}

                <div>

                    <p class="text-sm text-gray-500 mb-1">
                        ឈ្មោះការវាយតម្លៃ (ភាសាខ្មែរ)
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $evaluationPeriod->name_kh }}
                    </p>

                </div>


                {{-- English Name --}}

                <div>

                    <p class="text-sm text-gray-500 mb-1">
                        ឈ្មោះការវាយតម្លៃ (ភាសាអង់គ្លេស)
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $evaluationPeriod->name_en }}
                    </p>

                </div>


                {{-- Month / Year --}}

                <div>

                    <p class="text-sm text-gray-500 mb-1">
                        ខែ / ឆ្នាំវាយតម្លៃ
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $evaluationPeriod->month }}
                        /
                        {{ $evaluationPeriod->year }}
                    </p>

                </div>


                {{-- Start Date --}}

                <div>

                    <p class="text-sm text-gray-500 mb-1">
                        ថ្ងៃចាប់ផ្តើម
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $evaluationPeriod->start_date?->format('d/m/Y') }}
                    </p>

                </div>


                {{-- End Date --}}

                <div>

                    <p class="text-sm text-gray-500 mb-1">
                        ថ្ងៃបញ្ចប់
                    </p>

                    <p class="font-medium text-gray-800">
                        {{ $evaluationPeriod->end_date?->format('d/m/Y') }}
                    </p>

                </div>


                {{-- Open At --}}

                <div>

                    <p class="text-sm text-gray-500 mb-1">
                        ថ្ងៃបើកការវាយតម្លៃ
                    </p>

                    <p class="font-medium text-gray-800">

                        @if ($evaluationPeriod->open_at)

                            {{ $evaluationPeriod->open_at->format('d/m/Y H:i') }}

                        @else

                            -

                        @endif

                    </p>

                </div>


                {{-- Close At --}}

                <div>

                    <p class="text-sm text-gray-500 mb-1">
                        ថ្ងៃបិទការវាយតម្លៃ
                    </p>

                    <p class="font-medium text-gray-800">

                        @if ($evaluationPeriod->close_at)

                            {{ $evaluationPeriod->close_at->format('d/m/Y H:i') }}

                        @else

                            <span class="text-sm text-red-500">ការវាយតម្លៃមិនទាន់បានបិទ</span>

                        @endif

                    </p>

                </div>


                {{-- Participants Count --}}

                <div>

                    <p class="text-sm text-gray-500 mb-1">
                        ចំនួនមន្ត្រីចូលរួម
                    </p>

                    <p class="font-medium text-gray-800">

                        {{ $evaluationPeriod->periodUsers->count() }}

                        នាក់

                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================== --}}
        {{-- Participants --}}
        {{-- ========================================== --}}

        <div class="bg-white rounded-2xl shadow-sm">

            <div class="p-4 border-b text-gray-300">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            មន្ត្រីដែលត្រូវចូលរួមវាយតម្លៃ
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            {{-- មន្ត្រីដែលត្រូវបានចូលរួមក្នុងវគ្គវាយតម្លៃនេះ --}}
                        </p>

                    </div>

                    <div
                        class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium">

                        {{ $evaluationPeriod->periodUsers->count() }}
                        នាក់

                    </div>

                </div>

            </div>


            {{-- Table --}}

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50">

                        <tr class="text-blue-500">

                            <th class="px-6 py-3 text-left w-20">
                                #
                            </th>

                            <th class="px-6 py-3 text-left">
                                ឈ្មោះមន្ត្រី
                            </th>

                            <th class="px-6 py-3 text-left">
                                ឈ្មោះអ្នកប្រើប្រាស់
                            </th>

                            <th class="px-6 py-3 text-left">
                                អុីមែល
                            </th>

                            <th class="px-6 py-3 text-left">
                                ស្ថានភាព
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $evaluationPeriod->periodUsers
                            as $index => $periodUser
                        )

                            <tr
                                class="border-b border-gray-100 hover:bg-gray-50 transition">

                                <td class="px-6 py-4">
                                    {{ $index + 1 }}
                                </td>


                                <td class="px-6 py-4 font-medium text-gray-800">

                                    {{ $periodUser->user->name_kh ?? '-' }}

                                </td>


                                <td class="px-6 py-4 text-gray-600">

                                    {{ $periodUser->user->username ?? '-' }}

                                </td>


                                <td class="px-6 py-4 text-gray-600">

                                    {{ $periodUser->user->email ?? '-' }}

                                </td>


                                <td class="px-6 py-4">

                                    @if (
                                        $periodUser->user &&
                                        $periodUser->user->status === 'active'
                                    )

                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">

                                            សកម្ម

                                        </span>

                                    @else

                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">

                                            អសកម្ម

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="py-12 text-center text-gray-400">

                                    មិនមានទិន្នន័យ

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection
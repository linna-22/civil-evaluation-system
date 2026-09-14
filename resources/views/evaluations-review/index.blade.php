@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-6">

        {{-- ==========================================
         Page Header
        =========================================== --}}

        <div class="py-3">

            <div>

                <h1 class="text-xl font-title text-gray-800">
                    ពិនិត្យការវាយតម្លៃ
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    ពិនិត្យ និងកែសម្រួលការវាយតម្លៃរបស់មន្ត្រីក្នុងនាយកដ្ឋាន
                </p>

            </div>

        </div>


        {{-- ==========================================
         Current Evaluation Period
        =========================================== --}}

        @if($evaluationPeriod)

            <div class="mb-6 bg-blue-50 border border-blue-100 rounded-xl p-4">

                <div class="flex items-center gap-3">

                    <i
                        data-lucide="calendar-check"
                        class="w-5 h-5 text-blue-600">
                    </i>

                    <div>

                        <p class="text-sm font-medium text-blue-800">
                            រយៈពេលវាយតម្លៃបច្ចុប្បន្ន
                        </p>

                        <p class="text-sm text-blue-600 mt-0.5">
                            ការវាយតម្លៃទី
                            {{ $evaluationPeriod->evaluation_period_id }}
                            · កំពុងបើក
                        </p>

                    </div>

                </div>

            </div>

        @else

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">

                <i
                    data-lucide="calendar-x"
                    class="w-8 h-8 mx-auto text-yellow-600 mb-2">
                </i>

                <p class="text-sm text-yellow-700">
                    មិនមានរយៈពេលវាយតម្លៃដែលកំពុងបើកទេ
                </p>

            </div>

        @endif


        {{-- ==========================================
         Review Table
        =========================================== --}}

        @if($evaluationPeriod)

    <div class="bg-white rounded-2xl">

        <div class="overflow-x-hidden">

            <x-data-table bodyId="evaluation-review-table-body">

                <x-slot:head>

                    <th class="px-6 py-3 text-left w-20">
                        ល.រ
                    </th>

                    <th class="px-6 py-3 text-left">
                        គោត្តនាម និងនាម
                    </th>

                    <th class="px-6 py-3 text-left">
                        ឈ្មោះឡាតាំង
                    </th>

                    <th class="px-6 py-3 text-left">
                        តួនាទី
                    </th>

                    <th class="px-6 py-3 text-left">
                        ស្ថានភាព
                    </th>

                    <th class="px-6 py-3 text-center w-32">
                        សកម្មភាព
                    </th>

                </x-slot:head>

                <x-slot:body>

                    <tbody
                        id="evaluation-review-table-body"
                        class="divide-y divide-gray-100">
                    </tbody>

                </x-slot:body>

            </x-data-table>

        </div>

    </div>

    <div
        id="evaluation-review-pagination"
        class="mt-6">
    </div>

@endif

    </div>


    @vite('resources/js/evaluations-review/index.js')

@endsection
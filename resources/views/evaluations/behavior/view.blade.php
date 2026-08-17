@extends('layouts.app')

@section('content')
<meta
    name="csrf-token"
    content="{{ csrf_token() }}"
>

<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- Page Header --}}
<div class="mb-6">

    <div class="flex items-center justify-between">

        {{-- Title --}}
        <div>

            <h1 class="text-xl font-title text-gray-800">
                មើលលទ្ធផលវាយតម្លៃ
            </h1>
        </div>


        {{-- Back Button --}}
        <a
            href="{{ route('evaluations.behavior.index') }}"
            class="
                inline-flex
                items-center
                gap-2
                px-4
                py-2
                rounded-lg
                border
                border-gray-300
                text-gray-700
                text-sm
                font-medium
                hover:bg-gray-200
                transition
                cursor-pointer
            "
        >

            <i
                data-lucide="arrow-left"
                class="w-4 h-4"
            ></i>

            ត្រឡប់ក្រោយ

        </a>

    </div>

</div>


    {{-- Preview Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

            <h2 class="text-lg font-semibold text-gray-800">
                សង្ខេបលទ្ធផលវាយតម្លៃ
            </h2>

        </div>


        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4 text-center font-semibold text-gray-700 whitespace-nowrap">
                            ល.រ
                        </th>
                        <th class="px-5 py-4 text-left font-semibold text-gray-700 whitespace-nowrap">
                            មន្ត្រីដែលត្រូវវាយតម្លៃ
                        </th>
                        <th class="px-5 py-4 text-center font-semibold text-gray-700 whitespace-nowrap">
                            ឥរិយាបថ និងវិន័យ
                        </th>
                        <th class="px-5 py-4 text-center font-semibold text-gray-700 whitespace-nowrap">
                            សមត្ថភាពវិជ្ជាជីវៈ
                        </th>
                        <th class="px-5 py-4 text-center font-semibold text-gray-700 whitespace-nowrap">
                            ភាពជាអ្នកដឹកនាំ
                        </th>
                        <th class="px-5 py-4 text-center font-semibold text-gray-700 whitespace-nowrap">
                            ពិន្ទុសរុប
                        </th>
                    </tr>
                </thead>
                <tbody
                    id="previewTableBody"
                    class="divide-y divide-gray-100"
                >
                    {{-- Rows will be rendered by preview.js --}}
                </tbody>

            </table>

        </div>

    </div>


    {{-- Total Summary --}}
    <div
        id="overallSummary"
        class="mt-6"
    >
        {{-- Rendered by preview.js --}}
    </div>
</div>
<script>
    window.behaviorEvaluations = @json($evaluations);
</script>
@vite('resources/js/evaluations/behavior/view.js')
@endsection
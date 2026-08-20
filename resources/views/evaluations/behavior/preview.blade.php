@extends('layouts.app')

@section('content')
<meta
    name="csrf-token"
    content="{{ csrf_token() }}"
>


<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            ពិនិត្យលទ្ធផលការវាយតម្លៃឥរិយាបថ
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            សូមពិនិត្យពិន្ទុសរុបរបស់មន្ត្រីមុនពេលបញ្ជូន
        </p>
    </div>


    {{-- Preview Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

            <h2 class="text-lg font-semibold text-gray-800">
                សង្ខេបលទ្ធផល
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                ពិន្ទុត្រូវបានបង្ហាញតាមផ្នែកវាយតម្លៃនីមួយៗ
            </p>

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


    {{-- Bottom Actions --}}
    <div class="mt-6 flex items-center justify-between">

        <a
            href="{{ route('evaluations.behavior.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition"
        >
            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            ត្រឡប់ក្រោយ
        </a>


        <button
            type="button"
            id="confirmButton"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition"
        >
            បញ្ជូនការវាយតម្លៃ

            <i data-lucide="send" class="w-4 h-4"></i>
        </button>

    </div>

</div>

@vite('resources/js/evaluations/behavior/preview.js')

@endsection
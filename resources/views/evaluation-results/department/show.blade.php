@extends('layouts.app')

@section('title', 'Evaluation Results')

@section('content')

    <div>

        <x-page-header title="លទ្ធផលការវាយតម្លៃ" description="បង្ហាញលទ្ធផលការវាយតម្លៃរបស់មន្ត្រីក្នុងនាយកដ្ឋាន" />

        {{-- Search & Export Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 mb-3">

            <div class="flex items-center justify-between gap-4">

                <div class="flex items-center gap-3">

                    {{-- Search --}}
                    <x-search-box id="department-result-search" />

                    {{-- Office Filter --}}
                    <x-filters.office-filter id="department-result-office" :offices="$offices" />

                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-3">

                    {{-- Per Page --}}
                    <x-per-page id="department-result-per-page" />

                    {{-- Bulk PDF --}}
                    <button type="button" id="department-result-download-pdf"
                        class="cursor-pointer inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                        <i data-lucide="file-down" class="w-4 h-4"></i>
                        ទាញយក PDF
                    </button>

                    {{-- Bulk Word --}}
                    <button type="button" id="department-result-download-word"
                        class="cursor-pointer inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        ទាញយក Word
                    </button>

                </div>

            </div>

        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl">

            <div class="data-table-scroll">

                <x-data-table bodyId="department-result-table-body">

                    <x-slot:head>

                        <th class="px-6 py-3 text-left w-20">
                            ល.រ
                        </th>

                        <th class="px-6 py-3 text-left">
                            គោត្តនាមនិងនាម
                        </th>

                        <th class="px-6 py-3 text-left">
                            ភេទ
                        </th>

                        <th class="px-6 py-3 text-center">
                            សមិទ្ធកម្មការងារ
                        </th>

                        <th class="px-6 py-3 text-center">
                            វត្តមាន
                        </th>

                        <th class="px-6 py-3 text-center">
                            អាកប្បកិរិយា
                        </th>

                        <th class="px-6 py-3 text-center">
                            ពិន្ទុវាយតម្លៃសរុប
                        </th>
                        <th class="px-6 py-3 text-left">
                            មូលវិចារណ៍
                        </th>
                        <th class="px-6 py-3 text-center">
                            ទាញយក
                        </th>
                    </x-slot:head>

                    <x-slot:body>

                        <tbody id="department-result-table-body">

                        </tbody>

                    </x-slot:body>

                </x-data-table>

            </div>

        </div>

        {{-- Pagination --}}
        <div id="department-result-pagination" class="mt-6">
        </div>
        <x-remarks-modal />

    </div>
    <script>
        window.departmentEvaluationPeriodId =
            @json($evaluationPeriod->evaluation_period_id);
    </script>
    @vite(['resources/js/pages/department-evaluation-results/index.js'])
@endsection

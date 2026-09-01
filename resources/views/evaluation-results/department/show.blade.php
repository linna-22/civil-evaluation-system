@extends('layouts.app')

@section('title', 'Evaluation Results')

@section('content')

    <div class="space-y-6">

        <x-page-header
            title="លទ្ធផលការវាយតម្លៃ"
            description="បង្ហាញលទ្ធផលការវាយតម្លៃរបស់មន្ត្រីក្នុងនាយកដ្ឋាន"
        />

        {{-- Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-3">

            <div class="flex justify-between items-center">

                <x-search-box id="department-result-search" />

                <x-per-page id="department-result-per-page" />

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
                            ឈ្មោះឡាតាំង
                        </th>

                        <th class="px-6 py-3 text-left">
                            តួនាទី
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

    </div>
    <script>
    window.departmentEvaluationPeriodId =
        @json($evaluationPeriod->evaluation_period_id);
</script>
@vite(['resources/js/pages/department-evaluation-results/index.js'])
@endsection
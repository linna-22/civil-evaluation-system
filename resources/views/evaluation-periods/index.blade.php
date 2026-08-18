@extends('layouts.app')

@section('title', 'Evaluations Period')

@section('content')

    <div class="space-y-6">

        {{-- Page Header --}}
        <x-page-header title="កំណត់ការវាយតម្លៃ" description="គ្រប់គ្រងព័ត៌មានការវាយតម្លៃ">

            <x-slot:actions>

                <x-action-btn href="{{ route('evaluation-periods.create') }}" icon="plus">
                    បង្កើតការវាយតម្លៃ
                </x-action-btn>

            </x-slot:actions>

        </x-page-header>


        {{-- Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-3">
            <div class="flex justify-between items-center">
                <x-search-box id="evaluation-period-search" />
                <x-per-page id="evaluation-period-per-page" />
            </div>
        </div>


        {{-- Table --}}
        <div class="bg-white rounded-2xl">

            <div class="data-table-scroll">

                <x-data-table bodyId="evaluation-period-table-body">

                    <x-slot:head>

                        <th class="px-6 py-3 text-left w-20">
                            ល.រ
                        </th>
                        <th class="px-6 py-3 text-left">
                            ឈ្មោះការវាយតម្លៃ (ភាសាខ្មែរ)
                        </th>

                        <th class="px-6 py-3 text-left">
                            ឈ្មោះការវាយតម្លៃ (ភាសាអង់គ្លេស)
                        </th>
                        <th class="px-6 py-3 text-left">
                            កាលបរិច្ឆេទវាយតម្លៃ
                        </th>
                        <th class="px-6 py-3 text-left">
                            ស្ថានភាព
                        </th>

                        <th class="px-6 py-3 text-center w-40">
                            សកម្មភាព
                        </th>

                    </x-slot:head>

                    <x-slot:body>

                        <tbody id="evaluation-period-table-body">
                        </tbody>

                    </x-slot:body>

                </x-data-table>

            </div>

        </div>


        {{-- Pagination --}}
        <div id="evaluation-period-pagination" class="mt-6">
        </div>

    </div>

@endsection

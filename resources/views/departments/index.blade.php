@extends('layouts.app')

@section('title', 'departments')

@section('content')

    <div class="space-y-6">

        <x-page-header title="នាយកដ្ឋាន" description="គ្រប់គ្រងព័ត៌មាននាយកដ្ឋាន">

            <x-slot:actions>

                <x-action-btn href="{{ route('departments.create') }}" icon="plus">

                    បង្កើតនាយកដ្ឋាន

                </x-action-btn>

            </x-slot:actions>

        </x-page-header>

        {{-- Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-3">

            <div class="flex justify-between items-center">

                <x-search-box id="department-search" />

                <x-per-page id="department-per-page" />

            </div>

        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl">

            <div class="data-table-scroll">

                <x-data-table bodyId="department-table-body">

                    <x-slot:head>

                        <th class="px-6 py-3 text-left w-20">
                            #
                        </th>

                        <th class="px-6 py-3 text-left">
                            លេខកូដ
                        </th>

                        <th class="px-6 py-3 text-left">
                            ឈ្មោះនាយកដ្ឋាន (ភាសាខ្មែរ)
                        </th>

                        <th class="px-6 py-3 text-left">
                            ឈ្មោះនាយកដ្ឋាន (ភាសាអង់គ្លេស)
                        </th>

                        <th class="px-6 py-3 text-left">
                            ស្ថានភាព
                        </th>

                        <th class="px-6 py-3 text-center w-40">
                            សកម្មភាព
                        </th>

                    </x-slot:head>


                    <x-slot:body>

                        <tbody id="department-table-body">
                        </tbody>

                    </x-slot:body>

                </x-data-table>

            </div>

        </div>


        <!-- Pagination -->
        <div id="department-pagination" class="mt-6">
        </div>

    </div>

@endsection

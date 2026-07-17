@extends('layouts.app')

@section('title', 'Organizations')

@section('content')

    <div class="space-y-6">

        <x-page-header title="អង្គភាព" description="គ្រប់គ្រងព័ត៌មានអង្គភាព">

            <x-slot:actions>

                <x-action-btn href="{{ route('organizations.create') }}" icon="plus">

                    បន្ថែមអង្គភាព

                </x-action-btn>

            </x-slot:actions>

        </x-page-header>

        {{-- Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-3">

            <div class="flex justify-between items-center">

                <x-search-box id="organization-search" />

                <x-per-page id="organization-per-page" />

            </div>

        </div>

        {{-- Table --}}
        <x-data-table bodyId="organization-table-body">

            <x-slot:head>

                <th class="px-6 py-3 text-left w-20">#</th>

                <th class="px-6 py-3 text-left">
                    លេខកូដ
                </th>

                <th class="px-6 py-3 text-left">
                    ឈ្មោះអង្គភាព (ភាសាខ្មែរ)
                </th>

                <th class="px-6 py-3 text-left">
                    ឈ្មោះអង្គភាព (ភាសាអង់គ្លេស)
                </th>

                <th class="px-6 py-3 text-left">
                    ស្ថានភាព
                </th>

                <th class="px-6 py-3 text-center w-40">
                    សកម្មភាព
                </th>

            </x-slot:head>

            <x-slot:body>

                <tbody id="organization-table-body">

                </tbody>

            </x-slot:body>

        </x-data-table>
        <!-- Pagination -->
        <div id="organization-pagination" class="flex justify-end items-center gap-2 mt-6">
        </div>

    </div>

@endsection
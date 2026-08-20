
@extends('layouts.app')

@section('title', 'offices')

@section('content')

    <div class="space-y-6">

        <x-page-header title="ការិយាល័យ" description="គ្រប់គ្រងព័ត៌មានការិយាល័យ">

            <x-slot:actions>

                <x-action-btn href="{{ route('offices.create') }}" icon="plus">

                    បង្កើតការិយាល័យ

                </x-action-btn>

            </x-slot:actions>

        </x-page-header>

        {{-- Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-3">

            <div class="flex justify-between items-center">

                <x-search-box id="office-search" />

                <x-per-page id="office-per-page" />

            </div>

        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl">
            <div class="data-table-scroll">
                <x-data-table bodyId="office-table-body">

            <x-slot:head>

                <th class="px-6 py-3 text-left w-20">#</th>

                <th class="px-6 py-3 text-left">
                    លេខកូដ
                </th>

                <th class="px-6 py-3 text-left">
                    ឈ្មោះការិយាល័យ (ភាសាខ្មែរ)
                </th>

                <th class="px-6 py-3 text-left">
                    ឈ្មោះការិយាល័យ (ភាសាអង់គ្លេស)
                </th>

                <th class="px-6 py-3 text-left">
                    ស្ថានភាព
                </th>

                <th class="px-6 py-3 text-center w-40">
                    សកម្មភាព
                </th>

            </x-slot:head>

            <x-slot:body>

                <tbody id="office-table-body">

                </tbody>

            </x-slot:body>

        </x-data-table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div id="office-pagination" class="mt-6">
        </div>

    </div>

@endsection
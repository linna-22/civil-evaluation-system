@extends('layouts.app')

@section('title', 'Users')

@section('content')

    <div class="space-y-6">

        <x-page-header title="អ្នកប្រើប្រាស់" description="គ្រប់គ្រងព័ត៌មានអ្នកប្រើប្រាស់">

            <x-slot:actions>

                <x-action-btn href="{{ route('users.create') }}" icon="plus">

                    បង្កើតអ្នកប្រើប្រាស់

                </x-action-btn>

            </x-slot:actions>

        </x-page-header>

        {{-- Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-3">

            <div class="flex justify-between items-center">

                <x-search-box id="user-search" />

                <x-per-page id="user-per-page" />

            </div>

        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl">
            <div class="data-table-scroll">
                <x-data-table bodyId="user-table-body">

            <x-slot:head>

                <th class="px-6 py-3 text-left w-20">ល.រ</th>

                <th class="px-6 py-3 text-left">
                    គោត្តនាមនិងនាម
                </th>

                <th class="px-6 py-3 text-left">
                    ឈ្មោះឡាតាំង
                </th>
                <th class="px-6 py-3 text-left">
                    អង្គភាព
                </th>
                <th class="px-6 py-3 text-left">
                    នាយកដ្ឋាន
                </th>

                <th class="px-6 py-3 text-left">
                    ស្ថានភាព
                </th>

                <th class="px-6 py-3 text-center w-40">
                    សកម្មភាព
                </th>

            </x-slot:head>

            <x-slot:body>

                <tbody id="user-table-body">

                </tbody>

            </x-slot:body>

        </x-data-table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div id="user-pagination" class="mt-6">
        </div>

    </div>

@endsection
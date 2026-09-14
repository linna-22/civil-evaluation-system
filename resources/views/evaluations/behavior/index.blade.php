@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-2">

        {{-- ==========================================
         Page Header
        =========================================== --}}

        <div class="mb-6">

            <div class="flex items-center justify-between">

                <div>

                    <h1 class="text-xl font-title text-gray-800">
                        ការវាយតម្លៃឥរិយាបថ
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        បញ្ជីមន្ត្រីដែលត្រូវចូលរួមក្នុងការវាយតម្លៃឥរិយាបថ
                    </p>

                </div>


                {{-- ==========================================
                 Start Evaluation Button
                =========================================== --}}

                <div>

                    <a
                        href="{{ route('evaluations.behavior.create') }}"
                        class="
                            inline-flex
                            items-center
                            gap-2
                            px-5
                            py-2.5
                            rounded-lg
                            bg-blue-600
                            text-white
                            text-sm
                            font-medium
                            hover:bg-blue-700
                            transition
                        ">

                        <i
                            data-lucide="clipboard-pen"
                            class="w-4 h-4">
                        </i>

                        ចាប់ផ្ដើមវាយតម្លៃ

                    </a>

                </div>

            </div>

        </div>


        {{-- ==========================================
         Peer Table
        =========================================== --}}

        <div
            class="
                bg-white
                rounded-xl
                border
                border-gray-200
                shadow-sm
                overflow-hidden
            ">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    {{-- ==========================================
                     Table Header
                    =========================================== --}}

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    font-medium
                                    text-gray-600
                                ">
                                ល.រ
                            </th>

                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    font-medium
                                    text-gray-600
                                ">
                                ឈ្មោះមន្ត្រី
                            </th>

                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    font-medium
                                    text-gray-600
                                ">
                                ភេទ
                            </th>

                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    font-medium
                                    text-gray-600
                                ">
                                តួនាទី
                            </th>

                            <th
                                class="
                                    px-6
                                    py-4
                                    text-left
                                    font-medium
                                    text-gray-600
                                ">
                                ស្ថានភាព
                            </th>

                        </tr>

                    </thead>


                    {{-- ==========================================
                     Table Body
                    =========================================== --}}

                    <tbody
                        id="behavior-table-body"
                        class="divide-y divide-gray-100">

                    </tbody>

                </table>

            </div>


            {{-- ==========================================
             Pagination
            =========================================== --}}

            <div class="p-4 border-t border-gray-200"
                id="behavior-pagination">
            </div>

        </div>

    </div>


    {{-- ==========================================
     Behavior DataTable JavaScript
    =========================================== --}}

    @vite('resources/js/evaluations/behavior/index.js')

@endsection
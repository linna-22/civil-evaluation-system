@extends('layouts.app')

@section('title', 'វាយតម្លៃសមិទ្ធកម្មការងារ')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- =====================================================
            Page Header
        ====================================================== --}}

        <div class="mb-6">

            <h1 class="text-xl font-title text-gray-800">
                វាយតម្លៃសមិទ្ធកម្មការងារ
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                សូមវាយតម្លៃមន្ត្រីតាមលំដាប់ដែលបានកំណត់
            </p>

        </div>


       {{-- =====================================================
    Evaluation Progress
====================================================== --}}

<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">

    <div class="px-6 py-5">

        {{-- Progress Header --}}

        <div class="flex items-center justify-between mb-5">

            {{-- Current User --}}

            <div class="mt-1 flex items-center gap-3 text-sm text-gray-500">

                <h2
                    class="text-lg font-semibold text-gray-800"
                    id="currentUserName"
                >
                    {{ $currentUser->name_kh }}
                </h2>

                @if ($currentUser->position)

                    <span class="text-gray-300">
                        |
                    </span>

                    <span
                        id="currentUserPosition"
                    >
                        {{ $currentUser->position }}
                    </span>

                @endif

            </div>


            {{-- Current Position --}}

            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-medium"
            >

                <i
                    data-lucide="user-check"
                    class="w-4 h-4"
                ></i>

                <span id="currentPosition">
                    មន្ត្រីទី{{ $currentUserNumber }}
                    នៃមន្ត្រីសរុប {{ $totalUsers }}នាក់
                </span>

            </div>

        </div>


        {{-- Evaluation Progress --}}

        <div
            class="w-full bg-white border border-gray-200 rounded-2xl shadow-sm p-4"
        >

            <div
                id="evaluationUsers"
                class="flex items-center text-xl ml-4 md:ml-12 lg:ml-24"
            ></div>

        </div>

    </div>

</div>

        {{-- =====================================================
            Work Performance Form
        ====================================================== --}}

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Card Header --}}

            <div class="px-6 py-5 border-b border-gray-200">

                <div class="flex items-center gap-3">

                    {{-- Icon --}}
                    <div
                        class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="clipboard-pen" class="w-5 h-5"></i>
                    </div>

                    {{-- Title + Button --}}
                    <div class="flex items-center justify-between flex-1">

                        {{-- Left: Title & Description --}}
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                សមិទ្ធកម្មការងារ
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                បញ្ចូលសកម្មភាព និងសូចនាករសមិទ្ធកម្មការងាររបស់មន្ត្រី
                            </p>
                        </div>

                        {{-- Right: Add Performance Button --}}
                        <button type="button" id="addPerformanceBtn"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 text-sm font-medium hover:bg-blue-100 transition flex-shrink-0">

                            <i data-lucide="plus" class="w-4 h-4"></i>

                            បន្ថែមសកម្មភាព
                        </button>

                    </div>

                </div>

            </div>


            {{-- =================================================
                Form
            ================================================== --}}

            <form id="workPerformanceForm">

                <div class="p-6">

                    {{-- Performance Table --}}

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm border-collapse">

                            <thead>

                                <tr class="bg-gray-50">

                                    <th class="border px-4 py-3 text-center font-medium text-gray-600 w-16">

                                        ល.រ

                                    </th>

                                    <th class="border px-4 py-3 text-left font-medium text-gray-600 min-w-[250px]">

                                        សកម្មភាពការងារ

                                    </th>

                                    <th class="border px-4 py-3 text-left font-medium text-gray-600 min-w-[250px]">

                                        សូចនាករសមិទ្ធកម្ម

                                    </th>

                                    <th class="border px-4 py-3 text-center font-medium text-gray-600 w-40">

                                        លទ្ធផលសមិទ្ធកម្ម (%)

                                    </th>

                                    <th class="border px-4 py-3 text-center font-medium text-gray-600 w-32">

                                        ពិន្ទុ

                                    </th>

                                    <th class="border px-4 py-3 text-center font-medium text-gray-600 w-20">

                                        សកម្មភាព

                                    </th>

                                </tr>

                            </thead>


                            {{-- JS will add rows here --}}

                            <tbody id="performanceTableBody">

                            </tbody>

                        </table>

                    </div>
                </div>


                {{-- =================================================
                    Navigation
                ================================================== --}}

                <div class="px-6 py-5 border-t border-gray-200 flex items-center justify-between">

                    {{-- Previous --}}

                    <button type="button" id="previousUserBtn"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">

                        <i data-lucide="arrow-left" class="w-4 h-4"></i>

                        មន្ត្រីមុន

                    </button>


                    {{-- Next --}}

                    <button type="button" id="nextUserBtn"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">

                        បន្ទាប់

                        <i data-lucide="arrow-right" class="w-4 h-4"></i>

                    </button>

                </div>

            </form>

        </div>

    </div>
    <script>
        window.workPerformanceUsers = @json($users);
    </script>

    @vite('resources/js/evaluations/work_performance/table.js')
    @vite('resources/js/evaluations/work_performance/progressbar.js')
    @vite('resources/js/evaluations/work_performance/navigation.js')
    @vite('resources/js/evaluations/work_performance/create.js')
@endsection

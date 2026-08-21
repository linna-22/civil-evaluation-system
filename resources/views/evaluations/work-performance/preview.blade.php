@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- =====================================================
        Page Header
    ====================================================== --}}

    <div class="mb-6">

        <div class="flex items-center gap-3">

            <div
                class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600
                       flex items-center justify-center"
            >

                <i
                    data-lucide="clipboard-check"
                    class="w-6 h-6"
                ></i>

            </div>

            <div>

                <h1 class="text-xl font-semibold text-gray-800">
                    ពិនិត្យលទ្ធផលវាយតម្លៃ
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    សូមពិនិត្យលទ្ធផលរបស់មន្ត្រី
                    មុនពេលបញ្ជូន
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
        Evaluation Summary
    ====================================================== --}}

    <div
        class="bg-white rounded-xl border border-gray-200
               shadow-sm mb-6"
    >

        <div class="px-6 py-5">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-base font-semibold text-gray-800">
                        សង្ខេបលទ្ធផល
                    </h2>

                    <p
                        id="evaluationSummary"
                        class="text-sm text-gray-500 mt-1"
                    >
                        កំពុងរៀបចំទិន្នន័យ...
                    </p>

                </div>


                {{-- Total Users --}}

                <div
                    class="inline-flex items-center gap-2
                           px-3 py-1.5
                           rounded-full
                           bg-blue-50
                           text-blue-700
                           text-sm
                           font-medium"
                >

                    <i
                        data-lucide="users"
                        class="w-4 h-4"
                    ></i>

                    <span id="totalUsers">
                        0
                    </span>

                    មន្ត្រី

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        Users Summary Table
    ====================================================== --}}

    <div
        class="bg-white rounded-xl border border-gray-200
               shadow-sm overflow-hidden"
    >

        <div class="px-6 py-5 border-b border-gray-200">

            <h2 class="text-base font-semibold text-gray-800">
                លទ្ធផលសមិទ្ធកម្មការងារ
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                សូមពិនិត្យពិន្ទុសរុបរបស់មន្ត្រីម្នាក់ៗ
            </p>

        </div>


        {{-- Table Wrapper --}}

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                {{-- =================================================
                    Table Header
                ================================================== --}}

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr>

                        {{-- Number --}}

                        <th
                            class="px-5 py-4
                                   text-center
                                   font-semibold
                                   text-gray-700
                                   whitespace-nowrap"
                        >
                            ល.រ
                        </th>


                        {{-- User Name --}}

                        <th
                            class="px-5 py-4
                                   text-left
                                   font-semibold
                                   text-gray-700
                                   whitespace-nowrap"
                        >
                            ឈ្មោះមន្ត្រី
                        </th>


                        {{-- Position --}}

                        <th
                            class="px-5 py-4
                                   text-left
                                   font-semibold
                                   text-gray-700
                                   whitespace-nowrap"
                        >
                            តួនាទី
                        </th>


                        {{-- Activity Count --}}

                        <th
                            class="px-5 py-4
                                   text-center
                                   font-semibold
                                   text-gray-700
                                   whitespace-nowrap"
                        >
                            ចំនួនសកម្មភាព
                        </th>


                        {{-- Total Score --}}

                        <th
                            class="px-5 py-4
                                   text-center
                                   font-semibold
                                   text-gray-700
                                   whitespace-nowrap"
                        >
                            ពិន្ទុសរុប
                        </th>


                        {{-- Evaluation Score --}}

                        <th
                            class="px-5 py-4
                                   text-center
                                   font-semibold
                                   text-gray-700
                                   whitespace-nowrap"
                        >
                            ពិន្ទុវាយតម្លៃ
                        </th>

                    </tr>

                </thead>


                {{-- =================================================
                    Table Body
                ================================================== --}}

                <tbody
                    id="previewUsers"
                    class="divide-y divide-gray-100"
                >

                    {{-- JavaScript will render users here --}}

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
        Bottom Navigation
    ====================================================== --}}

    <div
        class="flex items-center justify-between
               bg-white rounded-xl border border-gray-200
               shadow-sm p-4 mt-6"
    >

        {{-- Back --}}

        <button
            type="button"
            id="backToEvaluationBtn"
            class="inline-flex items-center gap-2
                   px-5 py-2.5
                   rounded-lg
                   border border-gray-300
                   text-gray-700
                   hover:bg-gray-50
                   transition"
        >

            <i
                data-lucide="arrow-left"
                class="w-4 h-4"
            ></i>

            ត្រឡប់ក្រោយ

        </button>


        {{-- Submit --}}

        <button
            type="button"
            id="submitEvaluationBtn"
            class="inline-flex items-center gap-2
                   px-5 py-2.5
                   rounded-lg
                   bg-blue-600
                   text-white
                   hover:bg-blue-700
                   transition"
        >

            បញ្ជូនការវាយតម្លៃ

            <i
                data-lucide="send"
                class="w-4 h-4"
            ></i>

        </button>

    </div>

</div>


{{-- =====================================================
    Preview JavaScript
====================================================== --}}
<script>
    window.workPerformanceOfficeId = @json(
        session('work_performance_office_id')
    );
</script>
@vite('resources/js/evaluations/work_performance/preview.js')

@endsection
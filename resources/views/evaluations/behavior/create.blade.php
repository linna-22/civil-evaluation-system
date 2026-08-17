@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-6">

        {{-- ==========================================
         Page Header
    =========================================== --}}

        <div class="flex items-center justify-between mb-6">

            <div>

                <h1 class="text-2xl font-semibold text-gray-800">
                    ការវាយតម្លៃឥរិយាបថ
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    សូមវាយតម្លៃមន្ត្រីម្នាក់ៗតាមលំដាប់
                </p>

            </div>

            <a href="{{ route('evaluations.behavior.index') }}"
                class="
                inline-flex
                items-center
                gap-2
                px-4
                py-2
                rounded-lg
                border
                border-gray-300
                text-gray-600
                text-sm
                font-medium
                hover:bg-gray-50
                transition
            ">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>

                ត្រឡប់
            </a>

        </div>


        {{-- ==========================================
         Progress Steps
    =========================================== --}}

        <div
            class="
        bg-white
        rounded-xl
        border
        border-gray-200
        shadow-sm
        px-8
        py-6
        mb-6
    ">

            <div id="progressSteps" class="flex items-center justify-center"></div>

        </div>


        {{-- ==========================================
         Evaluation Workspace
    =========================================== --}}

        <div id="evaluationWorkspace"
            class="
            bg-white
            rounded-xl
            border
            border-gray-200
            shadow-sm
            overflow-hidden
        ">

            {{-- Peer Header --}}

            <div
                class="
            px-8
            py-6
            border-b
            border-gray-200
            bg-gray-50
        ">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500 mb-1">
                            មន្ត្រីទី
                            <span id="currentPeerNumber">1</span>
                            /
                            <span id="totalPeers">
                                {{ $peers->count() }}
                            </span>
                        </p>

                        <h2 id="peerNameKh" class="text-xl font-semibold text-gray-800"></h2>

                        <p id="peerNameEn" class="text-sm text-gray-500 mt-1"></p>

                        <p id="peerPosition" class="text-sm text-gray-400 mt-1"></p>

                    </div>

                    <div class="
                    w-12
                    h-12
                    rounded-full
                    bg-blue-50
                    text-blue-600
                    flex
                    items-center
                    justify-center
                    text-lg
                    font-semibold
                "
                        id="peerAvatar"></div>

                </div>

            </div>


            {{-- ==========================================
             Behavior Form
        =========================================== --}}

            <form id="behaviorEvaluationForm" method="POST" action="{{ route('evaluations.behavior.store') }}"
                data-preview-url="{{ route('evaluations.behavior.preview') }}" data-peers='@json($peers->values())'>

                @csrf

                <div class="p-8">

                    {{-- ==================================
                     Section 1
                =================================== --}}

                    <div
                        class="
                    border
                    border-gray-200
                    rounded-xl
                    overflow-hidden
                    mb-6
                ">

                        <div
                            class="
                        px-6
                        py-4
                        bg-gray-50
                        border-b
                        border-gray-200
                        flex
                        items-center
                        justify-between
                    ">

                            <div>

                                <h3
                                    class="
                                text-lg
                                font-semibold
                                text-gray-800
                            ">
                                    ១. ឥរិយាបថ និងវិន័យ
                                </h3>

                            </div>

                            <span
                                class="
                            px-3
                            py-1
                            rounded-full
                            bg-blue-50
                            text-blue-600
                            text-sm
                            font-medium
                        ">
                                6 ពិន្ទុ
                            </span>

                        </div>


                        <div id="sectionOne"></div>

                    </div>


                    {{-- ==================================
                     Section 2
                =================================== --}}

                    <div
                        class="
                    border
                    border-gray-200
                    rounded-xl
                    overflow-hidden
                    mb-6
                ">

                        <div
                            class="
                        px-6
                        py-4
                        bg-gray-50
                        border-b
                        border-gray-200
                        flex
                        items-center
                        justify-between
                    ">

                            <h3
                                class="
                            text-lg
                            font-semibold
                            text-gray-800
                        ">
                                ២. សមត្ថភាពវិជ្ជាជីវៈ
                            </h3>

                            <span
                                class="
                            px-3
                            py-1
                            rounded-full
                            bg-blue-50
                            text-blue-600
                            text-sm
                            font-medium
                        ">
                                6 ពិន្ទុ
                            </span>

                        </div>

                        <div id="sectionTwo"></div>

                    </div>


                    {{-- ==================================
                     Section 3
                =================================== --}}

                    <div
                        class="
                    border
                    border-gray-200
                    rounded-xl
                    overflow-hidden
                ">

                        <div
                            class="
                        px-6
                        py-4
                        bg-gray-50
                        border-b
                        border-gray-200
                        flex
                        items-center
                        justify-between
                    ">

                            <h3
                                class="
                            text-lg
                            font-semibold
                            text-gray-800
                        ">
                                ៣. ភាពជាអ្នកដឹកនាំ
                            </h3>

                            <span
                                class="
                            px-3
                            py-1
                            rounded-full
                            bg-blue-50
                            text-blue-600
                            text-sm
                            font-medium
                        ">
                                8 ពិន្ទុ
                            </span>

                        </div>

                        <div id="sectionThree"></div>

                    </div>

                </div>


                {{-- ==========================================
                 Navigation
            =========================================== --}}

                <div
                    class="
                px-8
                py-5
                border-t
                border-gray-200
                flex
                items-center
                justify-between
            ">

                    <button type="button" id="previousButton"
                        class="
                        inline-flex
                        items-center
                        gap-2
                        px-5
                        py-2.5
                        rounded-lg
                        border
                        border-gray-300
                        text-gray-700
                        text-sm
                        font-medium
                        hover:bg-gray-50
                        transition
                    ">

                        <i data-lucide="arrow-left" class="w-4 h-4"></i>

                        ត្រឡប់ក្រោយ

                    </button>


                    <button type="button" id="nextButton"
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

                        បន្ទាប់

                        
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ==========================================
     Peer Data
=========================================== --}}

   @vite('resources/js/evaluations/behavior/create.js')
@endsection

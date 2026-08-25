@extends('layouts.app')

@section('title', 'វាយតម្លៃវត្តមាន')

@section('content')

    <div class="max-w-6xl mx-auto px-6 py-6">

        {{-- =====================================================
            Page Header
        ====================================================== --}}

        <div class="mb-6">

            <div class="flex items-center justify-between">

                <div>

                    <h1 class="text-xl font-title text-gray-800">
                        វាយតម្លៃវត្តមាន
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $evaluationPeriod->name_kh ?? 'ការវាយតម្លៃវត្តមានប្រចាំខែ' }}
                    </p>

                </div>


                {{-- Back Button --}}

                <a href="{{ $officeModel
                    ? route('evaluations.attendance.office.users', [
                        'office' => $officeModel->office_id,
                    ])
                    : route('evaluations.attendance.department.users', [
                        'department' => $department->department_id,
                    ]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    ត្រឡប់ក្រោយ
                </a>
            </div>
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

                        <h2 class="text-lg font-semibold text-gray-800" id="currentUserName">

                            {{ $users->first()->name_kh ?? '' }}

                        </h2>


                        @if ($users->first()?->position)
                            <span class="text-gray-300">
                                |
                            </span>

                            <span id="currentUserPosition">

                                {{ $users->first()->position }}

                            </span>
                        @endif

                    </div>


                    {{-- Current Position --}}

                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">

                        <i data-lucide="user-check" class="w-4 h-4">
                        </i>

                        <span id="currentPosition">

                            មន្ត្រីទី1
                            នៃមន្ត្រីសរុប {{ $users->count() }}នាក់

                        </span>

                    </div>

                </div>


                {{-- Evaluation Progress --}}

                <div class="w-full bg-white border border-gray-200 rounded-2xl shadow-sm p-4">

                    <div id="evaluationUsers" class="flex items-center text-xl ml-4 md:ml-12 lg:ml-24">

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            Attendance Form
        ====================================================== --}}

        <div id="attendanceEvaluationContainer" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">

            @include('evaluations.attendance.partials.attendance-form')

        
             {{-- =====================================================
            Navigation
        ====================================================== --}}

        <div class="px-6 py-4 flex items-center justify-between">
            {{-- Previous --}}

            <button type="button" id="previousUserBtn"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                មុន
            </button>
            {{-- Next --}}

            <button type="button" id="nextUserBtn"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">

                បន្ទាប់

                <i data-lucide="arrow-right" class="w-4 h-4">
                </i>

            </button>

        </div>
        </div>


       

    </div>


    {{-- =====================================================
        Users Data For JavaScript
    ====================================================== --}}

    <script>
        window.attendanceUsers = @json($users->values());
        window.attendanceOfficeId = @json($officeModel?->office_id);
        window.attendanceDepartmentId = @json($department->department_id);
        window.attendanceTotalUsers = {{ $users->count() }};
    </script>
    @vite('resources/js/evaluations/attendance/progressbar.js')
    @vite('resources/js/evaluations/attendance/form.js')
    @vite('resources/js/evaluations/attendance/navigation.js')
@endsection

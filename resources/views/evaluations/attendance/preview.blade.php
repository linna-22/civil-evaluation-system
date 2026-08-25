@extends('layouts.app')

@section('title', 'ពិនិត្យការវាយតម្លៃវត្តមាន')

@section('content')

    <div class="max-w-6xl mx-auto px-6 py-6">

        {{-- =====================================================
            Page Header
        ====================================================== --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-title text-gray-800">
                        ពិនិត្យការវាយតម្លៃវត្តមាន
                    </h1>
                </div>
                {{-- Back Button --}}
                {{-- <button type="button" id="backToEvaluationBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4">
                    </i>
                    ត្រឡប់ក្រោយ
                </button> --}}
            </div>
        </div>
        {{-- =====================================================
            Preview Summary
        ====================================================== --}}

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            សង្ខេបលទ្ធផលវាយតម្លៃ
                        </h2>
                    </div>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">
                        <i data-lucide="users" class="w-4 h-4">
                        </i>
                        <span>
                            មន្ត្រីសរុប {{ $users->count() }} នាក់
                        </span>
                    </div>
                </div>
            </div>
            {{-- =================================================
                Attendance Table
            ================================================== --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-5 py-4 text-left font-semibold text-gray-600">
                                ល.រ
                            </th>
                            <th class="px-5 py-4 text-left font-semibold text-gray-600">
                                មន្ត្រី
                            </th>
                            <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                ឈប់មានច្បាប់
                            </th>
                            <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                ឈប់អត់ច្បាប់
                            </th>
                            <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                មកយឺត
                            </th>
                            <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                ចេញមុន
                            </th>
                            <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                ភាគរយវត្តមាន
                            </th>
                            <th class="px-5 py-4 text-center font-semibold text-gray-600">
                                ពិន្ទុ
                            </th>
                        </tr>
                    </thead>
                    <tbody id="attendancePreviewTable" class="divide-y divide-gray-100">
                    </tbody>
                </table>
            </div>
        </div>
        {{-- =====================================================
            Score Information
        ====================================================== --}}
        {{-- <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-6">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <i data-lucide="info" class="w-5 h-5">
                    </i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">
                        លក្ខខណ្ឌពិន្ទុវត្តមាន
                    </h3>
                    <div class="mt-2 text-sm text-gray-600 space-y-1">
                        <p>
                            • វត្តមានតិចជាង ៨០% → <strong>០ ពិន្ទុ</strong>
                        </p>
                        <p>
                            • វត្តមានចាប់ពី ៨០% → <strong>៥ ពិន្ទុ</strong>
                        </p>
                        <p>
                            • វត្តមានចាប់ពី ៩០% → <strong>១០ ពិន្ទុ</strong>
                        </p>
                        <p>
                            • វត្តមានចាប់ពី ៩៥% → <strong>១៥ ពិន្ទុ</strong>
                        </p>
                        <p>
                            • វត្តមាន ១០០% → <strong>២០ ពិន្ទុ</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- =====================================================
            Actions
        ====================================================== --}}

        <div class="flex items-center justify-between">

            {{-- Back --}}

            <button type="button" id="backToEvaluationBtnBottom"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">

                <i data-lucide="arrow-left" class="w-4 h-4">
                </i>

                ត្រឡប់ក្រោយ

            </button>


            {{-- Submit --}}

            <button type="button" id="submitAttendanceBtn"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">

                <i data-lucide="send" class="w-4 h-4">
                </i>

                បញ្ជូនការវាយតម្លៃ

            </button>

        </div>

    </div>
    {{-- Attendance Data For JavaScript --}}
    <script>
        window.attendanceUsers = @json($users->values());
        window.attendanceEvaluationPeriod = @json($evaluationPeriod);
        window.attendanceOfficeId = @json(request('office'));
        window.attendanceDepartmentId = @json(request('department'));
        window.attendanceSubmitUrl = "{{ route('evaluations.attendance.submit') }}";
        window.attendanceIndexUrl = "{{ route('evaluations.attendance.index') }}";
    </script>
    @vite('resources/js/evaluations/attendance/preview.js')

@endsection

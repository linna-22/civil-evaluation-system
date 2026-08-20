<div class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b px-6 py-4">

        <div>

            <h3 class="text-lg font-semibold text-gray-800">
                📅 វត្តមានការងារ
            </h3>

            <p class="text-sm text-gray-500">
                សង្ខេបវត្តមានប្រចាំខែ
            </p>

        </div>

        <span
            class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

            {{ number_format($evaluation->attendance->attendance_score, 2) }}
            / 20 ពិន្ទុ

        </span>

    </div>

    @php

        $attendance = $evaluation->attendance;

        $perfectAttendance =
            $attendance->approved_leave_count == 0 &&
            $attendance->unapproved_leave_count == 0 &&
            $attendance->late_hours == 0 &&
            $attendance->leave_early_hours == 0;

    @endphp

    @if($perfectAttendance)

        {{-- Perfect Attendance --}}
        <div class="p-8 text-center">

            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100">

                <svg class="h-10 w-10 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"/>

                </svg>

            </div>

            <h3 class="mt-5 text-xl font-bold text-green-700">

                វត្តមានល្អឥតខ្ចោះ

            </h3>

            <p class="mt-2 text-gray-600">

                មន្ត្រីរាជការមិនមានការឈប់សម្រាក មកយឺត ឬចេញមុន
                ក្នុងខែវាយតម្លៃនេះទេ។

            </p>

        </div>

    @else

        {{-- Attendance Detail --}}
        <div class="grid gap-5 p-6 md:grid-cols-4">

            <div class="rounded-lg bg-gray-50 p-4 text-center">

                <p class="text-sm text-gray-500">

                    ឈប់មានច្បាប់

                </p>

                <h4 class="mt-2 text-2xl font-bold text-gray-800">

                    {{ $attendance->approved_leave_count }}

                </h4>

                <p class="text-xs text-gray-500">

                    ថ្ងៃ

                </p>

            </div>

            <div class="rounded-lg bg-gray-50 p-4 text-center">

                <p class="text-sm text-gray-500">

                    ឈប់អត់ច្បាប់

                </p>

                <h4 class="mt-2 text-2xl font-bold text-red-600">

                    {{ $attendance->unapproved_leave_count }}

                </h4>

                <p class="text-xs text-gray-500">

                    ថ្ងៃ

                </p>

            </div>

            <div class="rounded-lg bg-gray-50 p-4 text-center">

                <p class="text-sm text-gray-500">

                    មកយឺត

                </p>

                <h4 class="mt-2 text-2xl font-bold text-yellow-600">

                    {{ rtrim(rtrim(number_format($attendance->late_hours,2),'0'),'.') }}

                </h4>

                <p class="text-xs text-gray-500">

                    ម៉ោង

                </p>

            </div>

            <div class="rounded-lg bg-gray-50 p-4 text-center">

                <p class="text-sm text-gray-500">

                    ចេញមុន

                </p>

                <h4 class="mt-2 text-2xl font-bold text-orange-600">

                    {{ rtrim(rtrim(number_format($attendance->leave_early_hours,2),'0'),'.') }}

                </h4>

                <p class="text-xs text-gray-500">

                    ម៉ោង

                </p>

            </div>

        </div>

    @endif

    {{-- Footer --}}
    {{-- <div class="border-t bg-gray-50 px-6 py-4">

        <div class="flex items-center justify-between">

            <span class="font-medium text-gray-700">

                ភាគរយវត្តមាន

            </span>

            <span class="text-lg font-bold text-green-600">

                {{ number_format($attendance->attendance_percent,2) }}%

            </span>

        </div>

    </div> --}}

</div>
@extends('layouts.app')

@section('title', 'កែប្រែវត្តមាន')

@section('content')

    @php
        $employee = $evaluation->evaluatee;
        $attendance = $evaluation->attendance;
        $approvedLeaveDays = (float) ($attendance?->approved_leave_count ?? 0);
        $unapprovedLeaveDays = (float) ($attendance?->unapproved_leave_count ?? 0);
        $lateHours = (float) ($attendance?->late_hours ?? 0);
        $leaveEarlyHours = (float) ($attendance?->leave_early_hours ?? 0);
        $overtimeHours = (float) ($attendance?->overtime_hours ?? 0);
        $perfectAttendance =
            $approvedLeaveDays == 0 &&
            $unapprovedLeaveDays == 0 &&
            $lateHours == 0 &&
            $leaveEarlyHours == 0;
    @endphp

    <div class="min-h-screen bg-[#f5f8ff]">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        កែប្រែវត្តមាន
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        កែប្រែព័ត៌មានវត្តមាន និងគណនាពិន្ទុឡើងវិញ
                    </p>
                </div>

                <a
                    href="{{ route('evaluation-results.attendance.show', [
                        'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                    ]) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition"
                >
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    ត្រឡប់ក្រោយ
                </a>

            </div>

            {{-- Closed period notice --}}
            <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="triangle-alert" class="w-5 h-5 text-amber-600 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-amber-800">
                            វគ្គវាយតម្លៃបានបិទហើយ
                        </p>
                        <p class="mt-1 text-xs text-amber-700">
                            ការកែប្រែនេះនឹងធ្វើឱ្យពិន្ទុវត្តមាន និងពិន្ទុវាយតម្លៃសរុបរបស់មន្ត្រីត្រូវបានគណនាឡើងវិញដោយស្វ័យប្រវត្តិ។
                        </p>
                    </div>
                </div>
            </div>

            {{-- Employee information --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">

                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        ព័ត៌មានមន្ត្រី
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                        <div>
                            <p class="text-xs text-gray-500 mb-1">អត្តលេខ</p>
                            <p class="font-semibold text-gray-800">
                                {{ $employee->id_code ?? 'មិនមាន' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">គោត្តនាមនិងនាម</p>
                            <p class="font-semibold text-gray-800">
                                {{ $employee->name_kh ?? 'មិនមាន' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">មុខតំណែង</p>
                            <p class="font-semibold text-gray-800">
                                {{ $employee->position ?? 'មិនមាន' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">វគ្គវាយតម្លៃ</p>
                            <p class="font-semibold text-gray-800">
                                {{ $evaluationPeriod->name_kh }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Attendance form --}}
            <form
                method="POST"
                action="{{ route('report.attendance.update', [
                    'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                    'user' => $employee->user_id,
                ]) }}"
                id="attendance-edit-form"
            >
                @csrf
                @method('PATCH')

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="calendar-check" class="w-5 h-5"></i>
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">
                                    វត្តមាន
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    កែប្រែព័ត៌មានវត្តមានរបស់មន្ត្រី
                                </p>
                            </div>

                        </div>
                    </div>

                    <div class="p-6 space-y-8">

                        @if ($errors->any())
                            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Same Perfect Attendance UI as Create Attendance --}}
                        <div
                            id="attendanceCard"
                            class="rounded-xl border border-green-200 bg-green-50 p-5 transition-all duration-300"
                        >
                            <div class="flex items-start justify-between">

                                <div class="flex-1">
                                    <h3 id="attendanceTitle" class="text-lg font-semibold text-green-700">
                                        វត្តមានល្អឥតខ្ចោះ
                                    </h3>
                                    <p id="attendanceDescription" class="mt-2 text-sm text-green-600">
                                        ខ្ញុំមិនមានការឈប់មានច្បាប់ ឈប់អត់ច្បាប់ មកយឺត ឬចេញមុន ក្នុងខែវាយតម្លៃនេះទេ។
                                    </p>
                                </div>

                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        id="perfectAttendance"
                                        name="perfect_attendance"
                                        value="1"
                                        class="peer sr-only"
                                        {{ $perfectAttendance ? 'checked' : '' }}
                                    >
                                    <div class="peer h-6 w-11 rounded-full bg-gray-300 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-green-600 peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                        </div>

                        {{-- Same Attendance fields as Create Attendance --}}
                        <div id="attendanceForm" class="space-y-8 {{ $perfectAttendance ? 'hidden' : '' }}">

                            <div>
                                <h3 class="mb-5 border-b pb-2 text-lg font-semibold text-gray-700">
                                    ព័ត៌មានអំពីការឈប់
                                </h3>

                                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                        <label class="mb-2 block font-medium text-gray-700">
                                            ឈប់មានច្បាប់ (ថ្ងៃ)
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            step="1"
                                            max="30"
                                            name="approved_leave_days"
                                            id="approvedLeaveDays"
                                            value="{{ old('approved_leave_days', $approvedLeaveDays) }}"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                        <p class="mt-2 text-xs text-gray-500">
                                            បញ្ចូលចំនួនថ្ងៃឈប់មានច្បាប់សរុបក្នុងខែនេះ។
                                        </p>
                                    </div>

                                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                        <label class="mb-2 block font-medium text-gray-700">
                                            ឈប់អត់ច្បាប់ (ថ្ងៃ)
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            step="1"
                                            max="30"
                                            name="unapproved_leave_days"
                                            id="unapprovedLeaveDays"
                                            value="{{ old('unapproved_leave_days', $unapprovedLeaveDays) }}"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                        <p class="mt-2 text-xs text-gray-500">
                                            បញ្ចូលចំនួនថ្ងៃឈប់អត់ច្បាប់សរុបក្នុងខែនេះ។
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div>
                                <h3 class="mb-5 border-b pb-2 text-lg font-semibold text-gray-700">
                                    ព័ត៌មានអំពីម៉ោង
                                </h3>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                        <label class="mb-2 block font-medium text-gray-700">
                                            មកយឺត (ម៉ោង)
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            step="0.5"
                                            max="8"
                                            name="late_hours"
                                            id="lateHours"
                                            value="{{ old('late_hours', $lateHours) }}"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                        <p class="mt-2 text-xs text-gray-500">
                                            បញ្ចូលចំនួនម៉ោងមកយឺតសរុបក្នុងខែនេះ។
                                        </p>
                                    </div>

                                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                        <label class="mb-2 block font-medium text-gray-700">
                                            ចេញមុន (ម៉ោង)
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            step="0.5"
                                            max="8"
                                            name="leave_early_hours"
                                            id="leaveEarlyHours"
                                            value="{{ old('leave_early_hours', $leaveEarlyHours) }}"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                        <p class="mt-2 text-xs text-gray-500">
                                            បញ្ចូលចំនួនម៉ោងចេញមុនសរុបក្នុងខែនេះ។
                                        </p>
                                    </div>

                                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                                        <label class="mb-2 block font-medium text-gray-700">
                                            ម៉ោងលើស (ម៉ោង)
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            step="0.5"
                                            name="overtime_hours"
                                            id="overtimeHours"
                                            value="{{ old('overtime_hours', $overtimeHours) }}"
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                        <p class="mt-2 text-xs text-gray-500">
                                            បញ្ចូលចំនួនម៉ោងធ្វើការលើសសរុបក្នុងខែនេះ។
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Calculated result --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                                <p class="text-sm text-gray-500">ភាគរយវត្តមាន</p>
                                <p id="attendancePercent" class="mt-1 text-2xl font-bold text-gray-800">100%</p>
                            </div>
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                                <p class="text-sm text-emerald-700">ពិន្ទុវត្តមាន</p>
                                <p id="attendanceScore" class="mt-1 text-2xl font-bold text-emerald-800">20/20</p>
                            </div>
                        </div>

                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition"
                        >
                            <i data-lucide="save" class="w-4 h-4"></i>
                            រក្សាទុកការកែប្រែ
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    @vite('resources/js/evaluations/attendance/edit.js')
@endsection

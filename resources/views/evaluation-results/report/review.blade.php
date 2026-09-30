@extends('layouts.app')

@section('title', 'ពិនិត្យលទ្ធផលវាយតម្លៃ')

@section('content')

    @php
        $employee = $result->evaluationPeriodUser?->user;
    @endphp

    <div class="min-h-screen bg-[#f5f8ff]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- ==========================================================
            PAGE HEADER
        =========================================================== --}}

            <div class="flex flex-col sm:flex-row sm:items-center
                    sm:justify-between gap-4 mb-6">

                <div>

                    <h1 class="text-2xl font-bold text-gray-800">
                        ពិនិត្យលទ្ធផលវាយតម្លៃ
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        ពិនិត្យលទ្ធផលវាយតម្លៃរបស់មន្ត្រីម្នាក់ៗ
                    </p>

                </div>

                <a href="{{ route('evaluation-results.overall.show', [
                        'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                    ]) }}"
                    class="inline-flex items-center justify-center gap-2
                       px-4 py-2.5
                       rounded-xl
                       bg-white
                       border border-gray-200
                       text-gray-700
                       text-sm font-semibold
                       hover:bg-gray-50
                       transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>

                    ត្រឡប់ក្រោយ
                </a>

            </div>


            {{-- ==========================================================
            EMPLOYEE INFORMATION
        =========================================================== --}}

            <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm overflow-hidden mb-5">

                <div class="px-6 py-5 border-b border-gray-100">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-11 h-11 rounded-xl
                                bg-blue-50
                                flex items-center justify-center">

                            <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>

                        </div>

                        <div>

                            <h2 class="text-lg font-bold text-gray-800">
                                ព័ត៌មានមន្ត្រី
                            </h2>

                            <p class="text-sm text-gray-500">
                                ព័ត៌មានបុគ្គលិកដែលកំពុងពិនិត្យ
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2
                            lg:grid-cols-4 gap-5">


                        {{-- ID Code --}}
                        <div>

                            <p class="text-xs text-gray-500 mb-1">
                                អត្តលេខ
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $employee?->id_code ?? 'មិនមាន' }}
                            </p>

                        </div>


                        {{-- Name --}}
                        <div>

                            <p class="text-xs text-gray-500 mb-1">
                                គោត្តនាមនិងនាម
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $employee?->name_kh ?? 'មិនមាន' }}
                            </p>

                        </div>


                        {{-- English Name --}}
                        <div>

                            <p class="text-xs text-gray-500 mb-1">
                                អក្សរឡាតាំង
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $employee?->name_en ?? 'មិនមាន' }}
                            </p>

                        </div>

                        {{-- Gender --}}
                        <div>

                            <p class="text-xs text-gray-500 mb-1">
                                ភេទ
                            </p>

                            <p class="font-semibold text-gray-800">

                                @if ($employee?->gender === 'male')
                                    ប្រុស
                                @elseif ($employee?->gender === 'female')
                                    ស្រី
                                @else
                                    មិនមាន
                                @endif

                            </p>

                        </div>






                        {{-- Position --}}
                        <div>

                            <p class="text-xs text-gray-500 mb-1">
                                មុខតំណែង
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $employee?->position ?? 'មិនមាន' }}
                            </p>

                        </div>


                        {{-- Phone --}}
                        <div>

                            <p class="text-xs text-gray-500 mb-1">
                                លេខទូរស័ព្ទ
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $employee?->phone ?? 'មិនមាន' }}
                            </p>

                        </div>


                        {{-- Department --}}
                        <div>

                            <p class="text-xs text-gray-500 mb-1">
                                នាយកដ្ឋាន
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $departmentAdmin->department?->department_name_kh ?? 'មិនមាន' }}
                            </p>

                        </div>


                        {{-- Evaluation Period --}}
                        <div>

                            <p class="text-xs text-gray-500 mb-1">
                                វគ្គវាយតម្លៃ
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $evaluationPeriod->name_kh }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==========================================================
            EVALUATION RESULT
        =========================================================== --}}

            <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm overflow-hidden mb-5">

                <div class="px-6 py-5 border-b border-gray-100">

                    <div>

                        <h2 class="text-lg font-bold text-gray-800">
                            លទ្ធផលការវាយតម្លៃ
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            ពិន្ទុដែលបានគណនាសម្រាប់វគ្គវាយតម្លៃនេះ
                        </p>

                    </div>

                </div>


                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        {{-- ==================================================
                        Work Performance
                    =================================================== --}}

                        <div class="rounded-2xl border border-blue-100
                                bg-blue-50 p-5">

                            <div class="flex items-start justify-between gap-3">

                                <div>
                                    <p class="text-sm font-medium text-gray-600">
                                        សមិទ្ធកម្មការងារ
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Work Performance
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">

                                    {{-- Edit Work Performance --}}
                                    <a href="{{ route('report.work-performance.edit', [
                                            'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                                            'user' => $employee->user_id,
                                        ]) }}"
                                        class="inline-flex items-center gap-1.5
                   px-3 py-1.5
                   rounded-lg
                   bg-white
                   border border-blue-200
                   text-blue-600
                   text-xs font-semibold
                   hover:bg-blue-50
                   transition">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        កែប្រែ
                                    </a>

                                    <div
                                        class="w-10 h-10 rounded-xl
                   bg-white
                   flex items-center justify-center">

                                        <i data-lucide="briefcase-business" class="w-5 h-5 text-blue-600">
                                        </i>

                                    </div>

                                </div>

                            </div>


                            <div class="mt-5">

                                <span class="text-3xl font-bold text-gray-800">
                                    {{ number_format($result->work_performance_score ?? 0) }}/60
                                </span>

                                <span class="ml-1 text-sm text-gray-500">
                                    ពិន្ទុ
                                </span>

                            </div>

                        </div>


                        {{-- ==================================================
                        Attendance
                    =================================================== --}}

                        <div
                            class="rounded-2xl border border-emerald-100
                                bg-emerald-50 p-5">

                            <div class="flex items-start justify-between gap-3">

                                <div>
                                    <p class="text-sm font-medium text-gray-600">
                                        វត្តមាន
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Attendance
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">

                                    {{-- Edit Attendance --}}
                                    <a href="{{ route('report.attendance.edit', [
                                        'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                                        'user' => $employee->user_id,
                                    ]) }}"
                                        class="inline-flex items-center gap-1.5
                   px-3 py-1.5
                   rounded-lg
                   bg-white
                   border border-emerald-200
                   text-emerald-600
                   text-xs font-semibold
                   hover:bg-emerald-50
                   transition">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        កែប្រែ
                                    </a>

                                    <div
                                        class="w-10 h-10 rounded-xl
                   bg-white
                   flex items-center justify-center">

                                        <i data-lucide="calendar-check" class="w-5 h-5 text-emerald-600">
                                        </i>

                                    </div>

                                </div>

                            </div>


                            <div class="mt-5">

                                <span class="text-3xl font-bold text-gray-800">
                                    {{ number_format($result->attendance_score ?? 0) }}/20
                                </span>

                                <span class="ml-1 text-sm text-gray-500">
                                    ពិន្ទុ
                                </span>

                            </div>

                        </div>


                        {{-- ==================================================
                        Behavior
                    =================================================== --}}

                        <div class="rounded-2xl border border-purple-100
                                bg-purple-50 p-5">

                            <div class="flex items-start justify-between gap-3">

                                <div>
                                    <p class="text-sm font-medium text-gray-600">
                                        អាកប្បកិរិយា
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Behavior
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">

                                    {{-- Edit Behavior --}}
                                    <a href="{{ route('report.behavior-review', [
                                            'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                                            'user' => $employee->user_id,
                                        ]) }}"
                                        class="inline-flex items-center gap-1.5
                   px-3 py-1.5
                   rounded-lg
                   bg-white
                   border border-purple-200
                   text-purple-600
                   text-xs font-semibold
                   hover:bg-purple-50
                   transition">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        កែប្រែ
                                    </a>

                                    <div
                                        class="w-10 h-10 rounded-xl
                   bg-white
                   flex items-center justify-center">

                                        <i data-lucide="heart-handshake" class="w-5 h-5 text-purple-600">
                                        </i>

                                    </div>

                                </div>

                            </div>


                            <div class="mt-5">

                                <span class="text-3xl font-bold text-gray-800">
                                    {{ number_format($result->behavior_score ?? 0) }}/20
                                </span>

                                <span class="ml-1 text-sm text-gray-500">
                                    ពិន្ទុ
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- ======================================================
                    Total
                ======================================================= --}}

                    <div
                        class="mt-6 rounded-2xl
                            border border-blue-200
                            bg-blue-50 p-6">

                        <div
                            class="flex flex-col sm:flex-row
                                sm:items-center
                                sm:justify-between gap-4">

                            <div>

                                <p class="text-sm font-medium text-gray-600">
                                    ពិន្ទុវាយតម្លៃសរុប
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    Final Evaluation Score
                                </p>

                            </div>

                            <div class="flex items-baseline gap-2">

                                <span class="text-4xl font-bold text-blue-700">
                                    {{ number_format($result->total_score ?? 0) }}
                                </span>

                                <span class="text-sm text-gray-500">
                                    / 100
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==========================================================
            REMARKS
        =========================================================== --}}

            <div class="bg-white rounded-2xl border border-gray-100
                    shadow-sm overflow-hidden mb-6">

                <div class="px-6 py-5 border-b border-gray-100">

                    <h2 class="text-lg font-bold text-gray-800">
                        មូលវិចារណ៍
                    </h2>

                </div>

                <div class="p-6">

                    @if ($result->remarks && trim($result->remarks) !== '')
                        <div
                            class="rounded-xl
                                border border-gray-200
                                bg-gray-50
                                p-4">

                            <p class="text-sm leading-6 text-gray-700">
                                {{ $result->remarks }}
                            </p>

                        </div>
                    @else
                        <div
                            class="rounded-xl
                                border border-dashed
                                border-gray-300
                                p-5 text-center">

                            <p class="text-sm text-gray-500">
                                មិនទាន់មានមូលវិចារណ៍
                            </p>

                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection

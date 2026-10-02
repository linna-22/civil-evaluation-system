@extends('layouts.app')

@section('title', 'វាយតម្លៃវត្តមាន')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- Page Header --}}
        <div class="mb-6">

            @if ($evaluationPeriod)

                <h1 class="text-xl font-title text-gray-800">
                    {{ $evaluationPeriod->name_kh }}
                    <span class="text-blue-500">
                        (វាយតម្លៃវត្តមាន)
                    </span>
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    សូមវាយតម្លៃវត្តមានរបស់មន្ត្រីតាមវិសាលភាពដែលអ្នកទទួលខុសត្រូវ
                </p>

            @else

                <h1 class="text-xl font-title text-gray-800">
                    ការវាយតម្លៃវត្តមាន
                </h1>

            @endif

        </div>


        {{-- No Open Evaluation Period --}}
        @if (!$evaluationPeriod)

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center">

                <div
                    class="w-12 h-12 mx-auto mb-4 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="calendar-x" class="w-6 h-6"></i>
                </div>

                <h2 class="mt-4 text-lg font-semibold text-gray-800">
                    មិនទាន់មានការវាយតម្លៃ
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    បច្ចុប្បន្នមិនមានការវាយតម្លៃដែលកំពុងបើកទេ។
                </p>

            </div>

        @else

            {{-- Office Information --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">

                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                    <div>

                        <div class="flex items-center gap-3">

                            <div
                                class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i data-lucide="building-2" class="w-5 h-5"></i>
                            </div>

                            <div>

                                @if ($assignment?->scope === 'office')
                                    <p class="text-sm text-gray-500">
                                        ការិយាល័យ
                                    </p>

                                    <h2 class="text-lg font-semibold text-gray-800">
                                        {{ $office?->office_name_kh }}
                                    </h2>

                                    <p class="text-xs text-gray-500 mt-1">
                                        អ្នកបញ្ចូលទិន្នន័យតាមការិយាល័យ
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500">
                                        កម្រិតនាយកដ្ឋាន
                                    </p>

                                    <h2 class="text-lg font-semibold text-gray-800">
                                        {{ $assignment?->department?->department_name_kh ?? 'នាយកដ្ឋាន' }}
                                    </h2>

                                    <p class="text-xs text-gray-500 mt-1">
                                        សម្រាប់មន្ត្រីកម្រិតនាយកដ្ឋាន
                                    </p>
                                @endif

                            </div>

                        </div>

                        <div class="mt-3 flex items-center gap-2 text-sm">

                            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>

                            <span class="text-gray-500">
                                មន្ត្រីសរុប៖
                            </span>

                            <span class="font-semibold text-blue-600">
                                {{ $users->count() }}
                            </span>

                            <span class="text-gray-500">
                                នាក់
                            </span>

                        </div>

                    </div>


                    {{-- Evaluation Action --}}
                    <div class="flex items-center gap-3">

                        @if ($allUsersSubmitted)

                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-50 text-green-700 text-sm font-medium">

                                <i data-lucide="check-circle" class="w-4 h-4"></i>

                                បានវាយតម្លៃរួច

                            </span>

                        @else

                            <a
                                href="{{ route('evaluations.attendance.create') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">

                                <i data-lucide="square-pen" class="w-4 h-4"></i>

                                វាយតម្លៃវត្តមាន

                            </a>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Employee Table --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-100">

                    <h2 class="text-base font-semibold text-gray-800">
                        បញ្ជីមន្ត្រី
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        បញ្ជីមន្ត្រីដែលត្រូវវាយតម្លៃវត្តមាន
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead class="bg-gray-50 border-b border-gray-200">

                            <tr class="text-left text-gray-600">

                                <th class="px-6 py-3 w-16">
                                    ល.រ
                                </th>

                                <th class="px-6 py-3">
                                    ឈ្មោះមន្ត្រី
                                </th>

                                <th class="px-6 py-3">
                                    មុខតំណែង
                                </th>

                                <th class="px-6 py-3 text-center">
                                    ស្ថានភាព
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($users as $index => $employee)

                                @php

                                    $submitted = in_array(
                                        $employee->user_id,
                                        $submittedUserIds
                                    );

                                @endphp

                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

                                    {{-- Number --}}
                                    <td class="px-6 py-4">
                                        {{ $index + 1 }}
                                    </td>


                                    {{-- Employee --}}
                                    <td class="px-6 py-4">

                                        <div class="font-medium text-gray-800">
                                            {{ $employee->name_kh }}
                                        </div>

                                        @if ($employee->id_code)

                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $employee->id_code }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Position --}}
                                    <td class="px-6 py-4 text-gray-600">

                                        {{ \App\Helpers\PositionHelper::label($employee->position) }}

                                    </td>


                                    {{-- Status --}}
                                    <td class="px-6 py-4 text-center">

                                        @if ($submitted)

                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium">

                                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>

                                                បានវាយតម្លៃ

                                            </span>

                                        @else

                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium">

                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>

                                                មិនទាន់វាយតម្លៃ

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="px-6 py-10 text-center text-gray-500">

                                        មិនមានមន្ត្រីក្នុងការិយាល័យនេះទេ។

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    </div>

@endsection
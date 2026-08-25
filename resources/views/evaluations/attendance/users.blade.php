@extends('layouts.app')

@section('title', 'មន្ត្រីដែលត្រូវវាយតម្លៃវត្តមាន')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- =====================================================
            Page Header
        ====================================================== --}}

        <div class="mb-6">

            <div class="flex items-center justify-between">

                <div>

                    <h1 class="text-xl font-title text-gray-800">
                        មន្ត្រីដែលត្រូវវាយតម្លៃ
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">

                        @if ($office)
                            {{ $office->office_name_kh }}
                        @else
                            {{ $department->department_name_kh }}
                        @endif

                    </p>

                </div>


                {{-- Back Button --}}
                @if ($office)
                <a href="{{ route('evaluations.attendance.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">

                    <i data-lucide="arrow-left" class="w-4 h-4"></i>

                    ត្រឡប់ក្រោយ

                </a>
                @endif

            </div>

        </div>


        {{-- =====================================================
            Location Information
        ====================================================== --}}

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">

            <div class="px-6 py-5">

                <div class="flex items-center gap-4">

                    {{-- Icon --}}

                    <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">

                        <i data-lucide="{{ $office ? 'building' : 'building-2' }}" class="w-5 h-5">
                        </i>

                    </div>


                    <div>

                        {{-- Department --}}

                        <h2 class="text-lg font-semibold text-gray-800">

                            {{ $department->department_name_kh }}

                        </h2>


                        {{-- Office --}}

                        @if ($office)
                            <p class="mt-1 text-sm text-gray-500">

                                <span class="font-medium text-gray-700">

                                    {{ $office->office_name_kh }}

                                </span>

                            </p>
                        @else
                            <p class="mt-1 text-sm text-gray-500">

                                មិនមានការិយាល័យ

                            </p>
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            User Table
        ====================================================== --}}

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Table Header --}}

            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-medium text-gray-800">

                        បញ្ជីមន្ត្រីដែលត្រូវវាយតម្លៃវត្តមាន

                    </h2>

                </div>


                {{-- Total Users --}}

                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">

                    <i data-lucide="users" class="w-4 h-4"></i>

                    {{ $users->count() }} នាក់

                </div>

            </div>


            @if ($users->isEmpty())

                {{-- =================================================
                    No Users
                ================================================== --}}

                <div class="px-6 py-12 text-center">

                    <i data-lucide="users" class="w-10 h-10 mx-auto text-gray-300 mb-3">
                    </i>

                    <p class="text-gray-500">

                        មិនមានមន្ត្រីសម្រាប់វាយតម្លៃទេ

                    </p>

                </div>
            @else
                {{-- =================================================
                    User Table
                ================================================== --}}

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50 border-b border-gray-200">

                            <tr>
                                <th class="px-6 py-4 text-left font-medium text-gray-600">
                                    ល.រ
                                </th>
                                <th class="px-6 py-4 text-left font-medium text-gray-600">
                                    ឈ្មោះមន្ត្រី
                                </th>
                                <th class="px-6 py-4 text-left font-medium text-gray-600">
                                    ភេទ
                                </th>
                                <th class="px-6 py-4 text-left font-medium text-gray-600">
                                    តួនាទី
                                </th>
                                <th class="px-6 py-4 text-left font-medium text-gray-600">
                                    ស្ថានភាព
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach ($users as $index => $user)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Number --}}

                                    <td class="px-6 py-4 text-gray-500">

                                        {{ $index + 1 }}

                                    </td>

                                    {{-- Name --}}

                                    <td class="px-6 py-4">

                                        <p class="font-medium text-gray-800">

                                            {{ $user->name_kh }}

                                        </p>

                                        @if ($user->name_en)
                                            <p class="text-sm text-gray-500 mt-0.5">

                                                {{ $user->name_en }}

                                            </p>
                                        @endif

                                    </td>


                                    {{-- Gender --}}

                                    <td class="px-6 py-4 text-gray-600">

                                        {{ $user->gender === 'female' ? 'ស្រី' : 'ប្រុស' }}

                                    </td>


                                    {{-- Position --}}

                                    <td class="px-6 py-4 text-gray-600">

                                        {{ $user->position ?? '-' }}

                                    </td>
                                    <td class="px-6 py-4 ">

                                        @if (in_array($user->user_id, $evaluatedUserIds))
                                            <span
                                                class="inline-flex items-center gap-1.5
                                                        px-3 py-1.5
                                                        rounded-full
                                                        bg-green-50
                                                        text-green-700
                                                        text-xs
                                                        font-medium">
                                                <i data-lucide="circle-check" class="w-3.5 h-3.5">
                                                </i>
                                                បានវាយតម្លៃរួច
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5
                                                    px-3 py-1.5
                                                    rounded-full
                                                    bg-red-50
                                                    text-red-600
                                                    text-xs
                                                    font-medium">

                                                <i data-lucide="clock-3" class="w-3.5 h-3.5">
                                                </i>

                                                រង់ចាំការវាយតម្លៃ

                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                {{-- =================================================
                    Start Evaluation
                ================================================== --}}

                <div class="px-6 py-5 border-t border-gray-200 flex justify-end">

                    @if (in_array($user->user_id, $evaluatedUserIds))

                       @if ($office)

                            <a href="{{ route('evaluations.attendance.view', [
                                'office' => $office->office_id
                            ]) }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">

                                <i data-lucide="eye" class="w-4 h-4"></i>

                                ពិនិត្យលទ្ធផលវាយតម្លៃ

                            </a>

                        @else

                            <a href="{{ route('evaluations.attendance.view', [
                                'department' => $department->department_id
                            ]) }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">

                                <i data-lucide="eye" class="w-4 h-4"></i>

                                ពិនិត្យលទ្ធផលវាយតម្លៃ

                            </a>

                        @endif
                    @else
                        {{-- Not Evaluated Yet --}}
                        @if ($office)
                            {{-- User has Office --}}
                            <a href="{{ route('evaluations.attendance.create', ['office' => $office->office_id, ]) }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                                <i data-lucide="clipboard-pen" class="w-4 h-4"></i>
                                ចាប់ផ្ដើមវាយតម្លៃ
                            </a>
                        @else
                            {{-- User has No Office --}}
                            <a href="{{ route('evaluations.attendance.create') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                                <i data-lucide="clipboard-pen" class="w-4 h-4"></i>
                                ចាប់ផ្ដើមវាយតម្លៃ
                            </a>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

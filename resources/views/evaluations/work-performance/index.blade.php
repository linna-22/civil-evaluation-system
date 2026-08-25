@extends('layouts.app')
@section('title', 'វាយតម្លៃសមិទ្ធកម្មការងារ')
@section('content')


    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-xl font-title text-gray-800">
            @if($evaluationPeriod)
            {{ $evaluationPeriod->name_kh }} <span class="text-blue-500">(សមិទ្ធកម្មការងារ)</span>
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            សូមជ្រើសរើសការិយាល័យ ដើម្បីបន្តការវាយតម្លៃ
        </p>
        @else
        <h1 class="text-xl font-title text-gray-800">ការវាយតម្លៃសមិទ្ធកម្មការងារ</h1>
        @endif
        </div>
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
            {{-- Your existing office cards here --}}
        @endif
        @if ($offices)

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                @foreach ($offices as $office)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition">

                        <div class="flex items-center justify-between mb-4">

                            {{-- Icon --}}
                            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">

                                <i data-lucide="building-2" class="w-5 h-5"></i>

                            </div>


                            {{-- Office Code + Evaluation Status --}}
                            <div class="flex items-center gap-2">

                                {{-- @if (!empty($office->office_code))
                                    <p class="text-sm text-gray-500">
                                        {{ $office->office_code }}
                                    </p>
                                @endif --}}


                                {{-- Evaluation Completed --}}
                                @if ($office->is_evaluated)
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                        វាយតម្លៃរួច
                                    </span>
                                @else
                                     <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-xs font-medium">
                                        <i data-lucide="hourglass" class="w-3.5 h-3.5"></i>
                                        រងចាំការវាយតម្លៃ
                                    </span>
                                @endif

                            </div>

                        </div>


                        {{-- Office Name --}}
                        <h2 class="text-lg font-semibold text-gray-800">

                            {{ $office->office_name_kh }}

                        </h2>


                        {{-- Total Users --}}
                        <div class="mt-2 flex items-center gap-2 text-sm">

                            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>

                            <span class="text-gray-500">
                                មន្ត្រីសរុប:
                            </span>

                            <span class="font-semibold text-blue-700">
                                {{ $office->users_count }}
                            </span>
                        </div>


                        {{-- Action --}}
                        <a href="{{ route('evaluations.work-performance.office.users', $office) }}"
                            class="inline-flex items-center gap-2 mt-4 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                            លម្អិត
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                @endforeach

            </div>

        @endif
    </div>

@endsection

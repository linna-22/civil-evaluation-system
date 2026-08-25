@extends('layouts.app')
@section('title', 'វាយតម្លៃវត្តមាន')
@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">
            {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-title text-gray-800">
                        @if($evaluationPeriod)
                        {{ $evaluationPeriod->name_kh }} <span class="text-blue-500">(វាយតម្លៃវត្តមាន)</span>
                    </h1>
                     <p class="mt-1 text-sm text-gray-500">
                        ជ្រើសរើសការិយាល័យដែលត្រូវវាយ
                    </p>
                    @else
                    <h1 class="text-xl font-title text-gray-800">ការវាយតម្លៃវត្តមាន</h1>
                    @endif 
                </div>
            </div>
        </div>
            {{-- No Open Evaluation Period --}}
        @if (!$evaluationPeriod)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-12 text-center">
                    <div
                        class="w-12 h-12 mx-auto mb-4 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i data-lucide="calendar-x" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        មិនទាន់មានការវាយតម្លៃ
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">
                        បច្ចុប្បន្នមិនមានការវាយតម្លៃដែលកំពុងបើកទេ។
                    </p>
                </div>
            </div>
        @else
                {{-- Evaluation Period --}}
            {{-- <div
                class="mb-6 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i data-lucide="calendar-check" class="w-5 h-5"></i>
                    </div>
                </div>
            </div> --}}
                {{-- Offices --}}
            @if ($offices->isEmpty())
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="px-6 py-12 text-center">
                        <div
                            class="w-12 h-12 mx-auto mb-4 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            មិនមានការិយាល័យ
                        </h2>
                        <p class="mt-2 text-sm text-gray-500">
                            មិនមានការិយាល័យសម្រាប់វាយតម្លៃទេ។
                        </p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($offices as $office)
                        <div
                            class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
                                {{-- Card Header --}}
                            <div class="flex items-center justify-between mb-4">
                                {{-- Icon --}}
                                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </div>
                                {{-- Office Code --}}
                                @if (!empty($office->office_code))
                                    <p class="text-sm text-gray-500">
                                        {{ $office->office_code }}
                                    </p>
                                @endif
                            </div>
                                {{-- Office Name --}}
                            <h2 class="text-lg font-semibold text-gray-800">
                                {{ $office->office_name_kh }}
                            </h2>
                                {{-- Total Users --}}
                            <div class="mt-2 flex items-center gap-2 text-sm">
                                <i
                                    data-lucide="users"
                                    class="w-4 h-4 text-gray-400">
                                </i>
                                <span class="text-gray-500">
                                    មន្ត្រីសរុប:
                                </span>
                                <span class="font-semibold text-blue-700">
                                    {{ $office->users_count }}
                                </span>
                            </div>
                                {{-- Action --}}
                            @if ($office->users_count > 0)
                                <a
                                    href="{{ route(
                                        'evaluations.attendance.office.users',
                                        ['office' => $office->office_id]
                                    ) }}"
                                    class="inline-flex items-center gap-2 mt-4 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                                    មើលមន្ត្រី
                                    <i
                                        data-lucide="arrow-right"
                                        class="w-4 h-4">
                                    </i>
                                </a>
                            @else
                                <span class="inline-flex items-center gap-2 mt-4 text-sm text-gray-400">
                                    មិនមានមន្ត្រី
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
@endsection
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-title text-gray-800">
            ការវាយតម្លៃសមិទ្ធកម្មការងារ និងវត្តមាន
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            សូមជ្រើសរើសនាយកដ្ឋាន ដើម្បីបន្តការវាយតម្លៃ
        </p>
    </div>
    {{-- Department Cards --}}
    @if ($departments->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-12 text-center">
            <i data-lucide="building-2" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
            <p class="text-gray-500">
                មិនមាននាយកដ្ឋានសម្រាប់វាយតម្លៃទេ
            </p>
        </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($departments as $department)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                {{-- Icon --}}
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                </div>
                {{-- Department Code --}}
                @if (!empty($department->department_code))
                <p class="text-sm text-gray-500">
                    {{ $department->department_code }}
                </p>
                @endif
            </div>
            {{-- Department Name --}}
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $department->department_name_kh }}
            </h2>
            {{-- Total Offices --}}
            <div class="mt-4 flex items-center gap-2 text-sm">
                {{-- <i data-lucide="building" class="w-4 h-4 text-gray-400"></i> --}}
                @if ($department->offices->isNotEmpty())
                <span class="text-gray-500">
                    ការិយាល័យសរុប:
                </span>
                <span class="font-semibold text-blue-700">
                    {{ $department->offices->count() }}
                </span>
                @else
                <span class="text-gray-400">
                    មិនមានការិយាល័យ
                </span>
                @endif
            </div>
            {{-- Action --}}
            <div class="mt-5">
                @if ($department->offices->isNotEmpty())
                {{-- Has Offices --}}
                <a href="{{ route('evaluations.work-attendance.offices', $department) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                    មើលការិយាល័យ
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
                @else
                {{-- No Offices --}}
                <a href="{{ route('evaluations.work-attendance.department.users', $department) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                    វាយតម្លៃមន្ត្រី
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
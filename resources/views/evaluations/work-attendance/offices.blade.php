@extends('layouts.app')
@section('title', 'វាយតម្លៃមន្ត្រី')
@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">
        {{-- Page Header --}}
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-xl font-title text-gray-800">
                        ការិយាល័យ
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        នាយកដ្ឋាន៖
                        <span class="font-medium text-gray-700">
                            {{ $department->department_name_kh }}
                        </span>
                    </p>
                </div>
                {{-- Back Button --}}
                <a href="{{ route('evaluations.work-attendance.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    ត្រឡប់ក្រោយ
                </a>
            </div>
        </div>
        {{-- Office Cards --}}
        @if ($offices->isEmpty())
            <div class=" bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-12 text-center">
                <i data-lucide="building" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
                <p class="text-gray-500">
                    មិនមានការិយាល័យនៅក្នុងនាយកដ្ឋាននេះទេ
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($offices as $office)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
                        {{-- Icon & Office Code --}}
                        <div class="flex items-center justify-between mb-4">
                            {{-- Icon --}}
                            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i data-lucide="building" class="w-5 h-5"></i>
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
                        <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            មន្ត្រីសរុប:
                            <span class="font-medium text-gray-700">
                                {{ $office->eligible_users_count }}
                            </span>
                            នាក់
                        </div>
                        {{-- Action --}}
                        <div class="mt-5">
                            <a href="{{ route('evaluations.work-attendance.office.users', $office) }}"
                                class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                                ​វាយតម្លៃមន្ត្រី
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

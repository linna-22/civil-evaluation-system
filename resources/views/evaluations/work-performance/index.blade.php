@extends('layouts.app')
@section('title', 'វាយតម្លៃសមិទ្ធកម្មការងារ')
@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-xl font-title text-gray-800">
                ការវាយតម្លៃសមិទ្ធកម្មការងារ និងវត្តមាន
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                សូមជ្រើសរើសការិយាល័យ ដើម្បីបន្តការវាយតម្លៃ
            </p>
        </div>
        {{-- Department Cards --}}
        @if ($offices->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-12 text-center">
                <i data-lucide="building-2" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
                <p class="text-gray-500">
                    មិនមានការិយាល័យសម្រាប់វាយតម្លៃទេ
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($offices as $office)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            {{-- Icon --}}
                            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i data-lucide="building-2" class="w-5 h-5"></i>
                            </div>
                            {{-- Department Code --}}
                            @if (!empty($office->office_code))
                                <p class="text-sm text-gray-500">
                                    {{ $office->office_code }}
                                </p>
                            @endif
                        </div>
                        {{-- Department Name --}}
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $office->office_name_kh }}
                        </h2>
                        {{-- Total user in each office --}}
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

                            មើលមន្ត្រីដែលត្រូវវាយតម្លៃ

                            <i data-lucide="arrow-right" class="w-4 h-4"></i>

                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection

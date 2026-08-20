@extends('layouts.app')

@section('title', 'ពិនិត្យការវាយតម្លៃសមិទ្ធកម្ម')

@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- ==========================================
            Page Header
        =========================================== --}}

        <div class="mb-6">

            <div class="flex items-center justify-between">

                <div>

                    <h1 class="text-xl font-title text-gray-800">
                        ពិនិត្យការវាយតម្លៃសមិទ្ធកម្មការងារ
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        សូមពិនិត្យព័ត៌មាន និងពិន្ទុ មុនពេលរក្សាទុក
                    </p>

                </div>

                <a href="{{ route('evaluations.work-performance.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                           border border-gray-300 text-gray-600 text-sm font-medium
                           hover:bg-gray-50 transition">

                    <i data-lucide="arrow-left" class="w-4 h-4"></i>

                    ត្រឡប់ទៅវាយតម្លៃ

                </a>

            </div>

        </div>


        {{-- ==========================================
            Summary
        =========================================== --}}

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">

            <div class="px-6 py-5 border-b border-gray-200">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            សេចក្តីសង្ខេប
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            បញ្ជីមន្ត្រីដែលបានបញ្ចូលការវាយតម្លៃ
                        </p>

                    </div>


                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5
                               rounded-full bg-blue-50 text-blue-700
                               text-sm font-medium">

                        <i data-lucide="users" class="w-4 h-4"></i>

                        {{ count($evaluations ?? []) }} នាក់

                    </div>

                </div>

            </div>


            {{-- ==========================================
                Evaluation List
            =========================================== --}}

            <div class="divide-y divide-gray-100">

                @forelse ($evaluations ?? [] as $evaluation)

                    <div class="px-6 py-5">

                        <div class="flex items-center justify-between">

                            {{-- User Information --}}

                            <div class="flex items-center gap-4">

                                <div
                                    class="w-11 h-11 rounded-full bg-blue-50
                                           text-blue-600 flex items-center
                                           justify-center">

                                    <i data-lucide="user" class="w-5 h-5"></i>

                                </div>


                                <div>

                                    <h3 class="font-semibold text-gray-800">

                                        {{ $evaluation['user_name'] ?? '-' }}

                                    </h3>

                                    @if (!empty($evaluation['position']))

                                        <p class="text-sm text-gray-500">

                                            {{ $evaluation['position'] }}

                                        </p>

                                    @endif

                                </div>

                            </div>


                            {{-- Score --}}

                            <div class="text-right">

                                <p class="text-xs text-gray-500">
                                    ពិន្ទុសមិទ្ធកម្ម
                                </p>

                                <p class="text-xl font-bold text-blue-600">

                                    {{ number_format($evaluation['total_score'] ?? 0, 2) }}

                                    <span class="text-sm text-gray-400">
                                        / 100
                                    </span>

                                </p>

                            </div>

                        </div>


                        {{-- ==========================================
                            Performance Details
                        =========================================== --}}

                        @if (!empty($evaluation['performances']))

                            <div class="mt-5 overflow-x-auto">

                                <table class="min-w-full border border-gray-200 text-sm">

                                    <thead class="bg-gray-50">

                                        <tr>

                                            <th class="border px-3 py-3 text-center">
                                                ល.រ
                                            </th>

                                            <th class="border px-4 py-3 text-left">
                                                សកម្មភាព
                                            </th>

                                            <th class="border px-4 py-3 text-left">
                                                សូចនាករ
                                            </th>

                                            <th class="border px-3 py-3 text-center">
                                                លទ្ធផល (%)
                                            </th>

                                            <th class="border px-3 py-3 text-center">
                                                ពិន្ទុ
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach ($evaluation['performances'] as $index => $performance)

                                            <tr>

                                                <td class="border px-3 py-3 text-center">
                                                    {{ $index + 1 }}
                                                </td>

                                                <td class="border px-4 py-3">
                                                    {{ $performance['activity'] ?? '-' }}
                                                </td>

                                                <td class="border px-4 py-3">
                                                    {{ $performance['indicator'] ?? '-' }}
                                                </td>

                                                <td class="border px-3 py-3 text-center">
                                                    {{ $performance['achievement_percent'] ?? 0 }}%
                                                </td>

                                                <td class="border px-3 py-3 text-center font-medium">
                                                    {{ number_format($performance['score'] ?? 0, 2) }}
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @endif

                    </div>

                @empty

                    {{-- No Data --}}

                    <div class="px-6 py-12 text-center">

                        <i data-lucide="clipboard-x"
                            class="w-10 h-10 mx-auto text-gray-300 mb-3">
                        </i>

                        <p class="text-gray-500">
                            មិនទាន់មានទិន្នន័យសម្រាប់ពិនិត្យទេ
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- ==========================================
            Bottom Actions
        =========================================== --}}

        <div class="flex items-center justify-end gap-3">

            {{-- Back --}}

            <a href="{{ route('evaluations.work-performance.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                       border border-gray-300 text-gray-700 text-sm
                       font-medium hover:bg-gray-50 transition">

                <i data-lucide="arrow-left" class="w-4 h-4"></i>

                កែសម្រួល

            </a>


            {{-- Final Save --}}

            <form
                method="POST"
                action="#">

                @csrf

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5
                           rounded-lg bg-blue-600 text-white text-sm
                           font-medium hover:bg-blue-700 transition">

                    <i data-lucide="save" class="w-4 h-4"></i>

                    រក្សាទុកការវាយតម្លៃ

                </button>

            </form>

        </div>

    </div>

@endsection
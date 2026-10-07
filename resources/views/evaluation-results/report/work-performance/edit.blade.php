@extends('layouts.app')

@section('title', 'កែប្រែសមិទ្ធកម្មការងារ')

@section('content')

    @php
        $employee = $evaluation->evaluatee;
    @endphp

    <div class="min-h-screen bg-[#f5f8ff]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        កែប្រែសមិទ្ធកម្មការងារ
                    </h1>

                    {{-- <p class="mt-1 text-sm text-gray-500">
                        កែប្រែទិន្នន័យសមិទ្ធកម្មការងារ និងគណនាពិន្ទុឡើងវិញ
                    </p> --}}
                </div>

                <a
                    href="{{ route('evaluation-results.work-performance.show', [
                        'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                    ]) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition"
                >
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    ត្រឡប់ក្រោយ
                </a>

            </div>


            {{-- Closed period notice --}}
            {{-- <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="triangle-alert" class="w-5 h-5 text-amber-600 mt-0.5"></i>

                    <div>
                        <p class="text-sm font-semibold text-amber-800">
                            ការតម្លៃបានបិទហើយ
                        </p>
                        <p class="mt-1 text-xs text-amber-700">
                            ការកែប្រែនេះនឹងធ្វើឱ្យពិន្ទុសមិទ្ធកម្ម និងពិន្ទុវាយតម្លៃសរុបរបស់មន្ត្រីត្រូវបានគណនាឡើងវិញដោយស្វ័យប្រវត្តិ។
                        </p>
                    </div>
                </div>
            </div> --}}


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
                                {{ \App\Helpers\PositionHelper::label($employee?->position) }}
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


            {{-- Work performance form --}}
            <form
                method="POST"
                action="{{ route('evaluation-results.work-performance.update', [
                    'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                    'user' => $employee->user_id,
                ]) }}"
                id="work-performance-edit-form"
            >
                @csrf
                @method('PATCH')

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                    {{-- Card Header --}}
                    <div class="px-6 py-5 border-b border-gray-200">

                        <div class="flex items-center gap-3">

                            {{-- Icon --}}
                            <div
                                class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0"
                            >
                                <i data-lucide="clipboard-pen" class="w-5 h-5"></i>
                            </div>

                            {{-- Title + Button --}}
                            <div class="flex items-center justify-between flex-1">

                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">
                                        សមិទ្ធកម្មការងារ
                                    </h2>

                                    {{-- <p class="mt-1 text-sm text-gray-500">
                                        កែប្រែសកម្មភាព និងសូចនាករសមិទ្ធកម្មការងាររបស់មន្ត្រី
                                    </p> --}}
                                </div>

                                {{-- Same Add button as Create Work Performance --}}
                                <button
                                    type="button"
                                    id="addPerformanceBtn"
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 text-sm font-medium hover:bg-blue-100 transition flex-shrink-0"
                                >
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    បន្ថែមសកម្មភាព
                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="p-6">

                        @if ($errors->any())
                            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Same Work Performance table as Create --}}
                        <div class="overflow-x-auto">

                            <table class="w-full text-sm border-collapse">

                                <thead>
                                    <tr class="bg-gray-50">

                                        <th class="border border-gray-200 px-4 py-3 text-center font-medium text-gray-600 w-16">
                                            ល.រ
                                        </th>

                                        <th class="border border-gray-200 px-4 py-3 text-left font-medium text-gray-600 min-w-[250px]">
                                            សកម្មភាពការងារ
                                        </th>

                                        <th class="border border-gray-200 px-4 py-3 text-left font-medium text-gray-600 min-w-[250px]">
                                            សូចនាករសមិទ្ធកម្ម
                                        </th>

                                        <th class="border border-gray-200 px-4 py-3 text-center font-medium text-gray-600 w-40">
                                            លទ្ធផលសមិទ្ធកម្ម (%)
                                        </th>

                                        <th class="border border-gray-200 px-4 py-3 text-center font-medium text-gray-600 w-32">
                                            ពិន្ទុ
                                        </th>

                                        <th class="border border-gray-200 px-4 py-3 text-center font-medium text-gray-600 w-20">
                                            សកម្មភាព
                                        </th>

                                    </tr>
                                </thead>

                                <tbody id="performanceTableBody">

                                    @forelse ($evaluation->workPerformance as $index => $performance)

                                        <tr>

                                            <input
                                                type="hidden"
                                                name="performances[{{ $index }}][work_performance_id]"
                                                value="{{ $performance->work_performance_id }}"
                                            >

                                            {{-- Number --}}
                                            <td class="border border-gray-300 text-center row-number font-medium">
                                                {{ $index + 1 }}
                                            </td>

                                            {{-- Activity --}}
                                            <td class="border border-gray-300 p-2">
                                                <textarea
                                                    name="performances[{{ $index }}][activity]"
                                                    rows="2"
                                                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                                                    placeholder="បញ្ចូលសកម្មភាព..."
                                                >{{ old("performances.$index.activity", $performance->activity) }}</textarea>
                                            </td>

                                            {{-- Indicator --}}
                                            <td class="border border-gray-300 p-2">
                                                <textarea
                                                    name="performances[{{ $index }}][indicator]"
                                                    rows="2"
                                                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                                                    placeholder="បញ្ចូលសូចនាករសមិទ្ធកម្ម..."
                                                >{{ old("performances.$index.indicator", $performance->indicator) }}</textarea>
                                            </td>

                                            {{-- Achievement --}}
                                            <td class="border border-gray-300 p-2">
                                                <input
                                                    type="number"
                                                    name="performances[{{ $index }}][achievement_percent]"
                                                    min="0"
                                                    max="100"
                                                    step="0.01"
                                                    value="{{ old("performances.$index.achievement_percent", $performance->achievement_percent) }}"
                                                    class="w-full rounded-lg text-center outline-none focus:outline-none focus:ring-0"
                                                    placeholder="0"
                                                >
                                            </td>

                                            {{-- Score --}}
                                            <td class="border border-gray-300 p-2">
                                                <input
                                                    type="text"
                                                    name="performances[{{ $index }}][score]"
                                                    value="0"
                                                    readonly
                                                    data-score
                                                    class="w-full rounded-lg bg-gray-100 text-center border-0"
                                                >
                                            </td>

                                            {{-- Action --}}
                                            {{-- Retrieved records cannot be deleted. --}}
                                            <td class="border border-gray-300 text-center">
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            {{-- Number --}}
                                            <td class="border border-gray-300 text-center row-number font-medium">
                                                1
                                            </td>

                                            {{-- Activity --}}
                                            <td class="border border-gray-300 p-2">
                                                <textarea
                                                    name="performances[0][activity]"
                                                    rows="2"
                                                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                                                    placeholder="បញ្ចូលសកម្មភាព..."
                                                ></textarea>
                                            </td>

                                            {{-- Indicator --}}
                                            <td class="border border-gray-300 p-2">
                                                <textarea
                                                    name="performances[0][indicator]"
                                                    rows="2"
                                                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                                                    placeholder="បញ្ចូលសូចនាករសមិទ្ធកម្ម..."
                                                ></textarea>
                                            </td>

                                            {{-- Achievement --}}
                                            <td class="border border-gray-300 p-2">
                                                <input
                                                    type="number"
                                                    name="performances[0][achievement_percent]"
                                                    min="0"
                                                    max="100"
                                                    step="0.01"
                                                    class="w-full rounded-lg text-center outline-none focus:outline-none focus:ring-0"
                                                    placeholder="0"
                                                >
                                            </td>

                                            {{-- Score --}}
                                            <td class="border border-gray-300 p-2">
                                                <input
                                                    type="text"
                                                    name="performances[0][score]"
                                                    value="0"
                                                    readonly
                                                    data-score
                                                    class="w-full rounded-lg bg-gray-100 text-center border-0"
                                                >
                                            </td>

                                            {{-- Action --}}
                                            <td class="border border-gray-300 text-center">
                                                <button
                                                    type="button"
                                                    class="delete-row text-red-600 hover:text-red-700 cursor-pointer"
                                                >
                                                    <i data-lucide="trash-2" class="w-5 h-5 mx-auto"></i>
                                                </button>
                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-gray-100 pt-5">

                            <div class="text-sm text-gray-500">
                                <span class="font-semibold text-gray-700">ចំណាំ:</span>
                                ពិន្ទុនឹងត្រូវបានគណនាឡើងវិញតាមចំនួនសកម្មភាពដោយស្វ័យប្រវត្តិ។
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition"
                                >
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    រក្សាទុកការកែប្រែ
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

    @vite('resources/js/evaluations/work_performance/edit.js')
@endsection

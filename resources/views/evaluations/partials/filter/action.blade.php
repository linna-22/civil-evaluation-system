@php

    $months = collect(range(1, 12))
        ->mapWithKeys(function ($m) {
            return [
                $m => \Carbon\Carbon::create()->month($m)->translatedFormat('F'),
            ];
        })
        ->toArray();

    $years = collect(range(now()->year, now()->year - 5))
        ->mapWithKeys(function ($y) {
            return [
                $y => $y,
            ];
        })
        ->toArray();

@endphp

<div class="mt-4 flex flex-wrap items-center justify-between gap-3">

    <div class="flex gap-2">

        <div class="w-45">
            <x-filters.select name="month" :options="$months" :selected="request('month', now()->month)" />
        </div>
        <div class="w-45">
            <x-filters.select name="year" :options="$years" :selected="request('year', now()->year)" />
        </div>

    </div>

    <div class="flex gap-2">

        {{-- Reset --}}
        <button id="resetFilter" type="button"
            class="cursor-pointer w-42 inline-flex h-11 items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">

            <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
            កំណត់ឡើងវិញ
        </button>

        {{-- Report --}}
        <div class="relative">

            {{-- Button --}}
            <button id="reportDropdownBtn" type="button"
                class="cursor-pointer inline-flex h-11 items-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-medium text-white transition hover:bg-blue-700">
                <i data-lucide="file-text" class="h-4 w-4"></i>
                បង្កើតរបាយការណ៍
                <i data-lucide="chevron-down" class="h-4 w-4"></i>
            </button>

            {{-- Dropdown --}}
            <div id="reportDropdownMenu"
                class="hidden absolute right-0 mt-3 w-72 origin-top-right overflow-hidden rounded-2xl bg-white shadow-xl opacity-0 scale-95 transition-all duration-200 z-50">

                <a id="previewReport" data-url="{{ route('reports.evaluation.preview') }}"
                    class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100">
                    <i data-lucide="eye" class="w-5 h-5 text-blue-600"></i>
                    <span class="font-body text-blue-600">
                        មើលរបាយការណ៍
                    </span>
                </a>
                <a id="downloadWord" href="#" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100">

                    <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>

                    <span class="font-body text-blue-600">

                        ទាញយករបាយការណ៍ជា Word

                    </span>

                </a>

            </div>

        </div>

    </div>

</div>

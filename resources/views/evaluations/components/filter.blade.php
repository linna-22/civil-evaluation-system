@php
    // Prepare month options with translated localized names
    $months = collect(range(1, 12))->mapWithKeys(function ($m) {
        return [$m => \Carbon\Carbon::create()->month($m)->translatedFormat('F')];
    })->toArray();

    // Prepare year options (current year down to 5 years prior)
    $years = collect(range(now()->year, now()->year - 5))->mapWithKeys(function ($y) {
        return [$y => $y];
    })->toArray();
@endphp

<div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
    <form id="evaluationFilterForm" method="GET" action="{{ route('evaluations.list') }}">
        
        {{-- Row 1: Search & Role-based Filters --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            
            {{-- Search Input --}}
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="ស្វែងរកតាមអត្តលេខ ឈ្មោះ..."
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-11 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                >
            </div>

            {{-- Organization Filter (Super Admin Only) --}}
            @if (auth()->user()->role === 'super_admin')
                <div>
                    <x-filters.select 
                        name="organization" 
                        placeholder="អង្គភាពទាំងអស់" 
                        :options="$organizations"
                        :selected="request('organization')" 
                    />
                </div>
            @endif

            {{-- Department Filter (Super Admin & Organization Admin) --}}
            @if (in_array(auth()->user()->role, ['super_admin', 'organization_admin']))
                <div>
                    <x-filters.select 
                        name="department" 
                        placeholder="នាយកដ្ឋានទាំងអស់" 
                        :options="$departments"
                        :selected="request('department')" 
                    />
                </div>
            @endif

        </div>

        {{-- Row 2: Date Filters & Action Buttons --}}
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
            
            {{-- Date Selectors --}}
            <div class="flex gap-3">
                <div class="w-40">
                    <x-filters.select 
                        name="month" 
                        :options="$months" 
                        :selected="request('month', now()->month)" 
                    />
                </div>

                <div class="w-32">
                    <x-filters.select 
                        name="year" 
                        :options="$years" 
                        :selected="request('year', now()->year)" 
                    />
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex gap-2">
                {{-- Reset Button --}}
                <button 
                    id="resetFilter" 
                    type="button"
                    class="cursor-pointer inline-flex h-11 items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                    កំណត់ឡើងវិញ
                </button>

                <div class="relative">

    {{-- Button --}}
    <button
        id="reportDropdownBtn"
        type="button"
        class="inline-flex h-11 items-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-medium text-white transition hover:bg-blue-700">

        <i data-lucide="file-text" class="h-4 w-4"></i>

        បង្កើតរបាយការណ៍

        <i data-lucide="chevron-down" class="h-4 w-4"></i>

    </button>

    {{-- Dropdown --}}
    <div
        id="reportDropdownMenu"
        class="hidden
               opacity-0
               scale-95
               origin-top-right
               absolute
               right-0
               mt-3
               w-72
               rounded-2xl
               bg-white
               shadow-xl
               transition-all
               duration-200
               overflow-hidden
               z-50">

        <a
            id="previewReport"
            href="{{ route('reports.evaluation.preview') }}"
            class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100">

            <i data-lucide="eye" class="w-5 h-5 text-blue-600"></i>

            <span class="font-body text-blue-600">

                មើលរបាយការណ៍

            </span>

        </a>

        <a
            id="downloadPdf"
            href="#"
            class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100">

            <i data-lucide="file-down" class="w-5 h-5 text-red-600"></i>

            <span class="font-body text-red-600">

                ទាញយករបាយការណ៍ជា PDF

            </span>

        </a>

        <a
            id="downloadWord"
            href="#"
            class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100">

            <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>

            <span class="font-body text-blue-600">

                ទាញយករបាយការណ៍ជា Word

            </span>

        </a>

    </div>

</div>
            </div>

        </div>

    </form>
</div>
<script>
    const reportBtn = document.getElementById("reportDropdownBtn");
const reportMenu = document.getElementById("reportDropdownMenu");

reportBtn.addEventListener("click", function (e) {

    e.stopPropagation();

    reportMenu.classList.toggle("hidden");

    setTimeout(() => {

        reportMenu.classList.toggle("opacity-0");
        reportMenu.classList.toggle("scale-95");

    }, 10);

});

document.addEventListener("click", function () {

    reportMenu.classList.add("opacity-0");
    reportMenu.classList.add("scale-95");

    setTimeout(() => {

        reportMenu.classList.add("hidden");

    }, 200);

});
</script>
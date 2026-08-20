@php
    // Prepare month options with translated localized names
    $months = collect(range(1, 12))
        ->mapWithKeys(function ($m) {
            return [$m => \Carbon\Carbon::create()->month($m)->translatedFormat('F')];
        })
        ->toArray();

    // Prepare year options (current year down to 5 years prior)
    $years = collect(range(now()->year, now()->year - 5))
        ->mapWithKeys(function ($y) {
            return [$y => $y];
        })
        ->toArray();
@endphp

<div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
    <form id="evaluationFilterForm" method="GET" action="{{ route('evaluations.list') }}">

        @switch(auth()->user()->role)
            @case('super_admin')
                @include('evaluations.partials.filter.super-admin')
            @break

            @case('organization_admin')
                @include('evaluations.partials.filter.organization-admin')
            @break

            @case('department_admin')
                @include('evaluations.partials.filter.department-admin')
            @break
        @endswitch

        @include('evaluations.partials.filter.action')

    </form>
</div>
<script>
    const reportBtn = document.getElementById("reportDropdownBtn");
    const reportMenu = document.getElementById("reportDropdownMenu");

    reportBtn.addEventListener("click", function(e) {

        e.stopPropagation();

        reportMenu.classList.toggle("hidden");

        setTimeout(() => {

            reportMenu.classList.toggle("opacity-0");
            reportMenu.classList.toggle("scale-95");

        }, 10);

    });

    document.addEventListener("click", function() {

        reportMenu.classList.add("opacity-0");
        reportMenu.classList.add("scale-95");

        setTimeout(() => {

            reportMenu.classList.add("hidden");

        }, 200);

    });
</script>

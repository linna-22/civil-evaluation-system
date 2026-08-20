@props([
    'evaluation' => null,
])

<x-layout.page-card
    title="ការវាយតម្លៃប្រចាំខែ"
    description="ស្ថានភាពការវាយតម្លៃសម្រាប់ខែបច្ចុប្បន្ន"
    icon="clipboard-check">

    @props([
    'evaluation' => null,
])

@php
    $status = $evaluation?->evaluation_status ?? 'null';

 if ($status === 'submitted') {

        $buttonText = 'មើលការវាយតម្លៃ';
        $buttonIcon = 'eye';
        $buttonUrl = route('evaluations.show', $evaluation);

    } else {

        $buttonText = 'ចាប់ផ្តើមការវាយតម្លៃ';
        $buttonIcon = 'play';
        $buttonUrl =  route('evaluations.evaluations.create');

    }
@endphp

    <div class="space-y-6">

        {{-- Current Month --}}
        <div>

            <div class="flex items-center gap-2">

                <i
                    data-lucide="calendar-days"
                    class="w-5 h-5 text-primary">
                </i>

                <p class="text-sm font-medium text-gray-500">
                    ខែបច្ចុប្បន្ន
                </p>

            </div>

            <h3 class="mt-2 text-2xl font-bold text-gray-800">

                {{ now()->translatedFormat('F Y') }}

            </h3>

        </div>

        <div class="border-t border-gray-200"></div>

        {{-- Status --}}
        <div>

            <div class="flex items-center gap-2">

                <i
                    data-lucide="clipboard-list"
                    class="w-5 h-5 text-primary">
                </i>

                <p class="text-sm font-medium text-gray-500">
                    ស្ថានភាព
                </p>

            </div>

            <div class="mt-2">

                <x-dashboard.status-badge
                    :status="$status" />

            </div>

        </div>

        <div class="border-t border-gray-200"></div>

        {{-- Action --}}
        <div>

            <a
                href="{{ $buttonUrl }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-3 text-sm font-medium text-white hover:bg-blue-600 transition">

                <i
                    data-lucide="{{ $buttonIcon }}"
                    class="w-5 h-5">
                </i>

                {{ $buttonText }}

            </a>

        </div>

    </div>

</x-layout.page-card>
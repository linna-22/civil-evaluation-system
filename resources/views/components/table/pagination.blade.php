@props([
    'paginator',
])

@if ($paginator->hasPages())

<div
    class="flex
           items-center
           justify-between
           px-6
           py-5
           border-t
           bg-white">

    {{-- Left --}}
    <div
        class="text-sm
               text-gray-500">

        បង្ហាញ

        <span class="font-semibold text-gray-700">

            {{ $paginator->firstItem() }}

        </span>

        -

        <span class="font-semibold text-gray-700">

            {{ $paginator->lastItem() }}

        </span>

        of

        <span class="font-semibold text-gray-700">

            {{ $paginator->total() }}

        </span>

        records

    </div>

    {{-- Right --}}
    <div class="flex items-center gap-2">

        {{-- Previous --}}
        @if($paginator->onFirstPage())

            <span
                class="w-10
                       h-10
                       rounded-xl
                       border
                       border-gray-200
                       flex
                       items-center
                       justify-center
                       text-gray-300">

                <i
                    data-lucide="chevron-left"
                    class="w-5 h-5">
                </i>

            </span>

        @else

            <a
                href="{{ $paginator->previousPageUrl() }}"
                class="w-10
                       h-10
                       rounded-xl
                       border
                       border-gray-200
                       hover:bg-blue-50
                       hover:border-blue-300
                       transition
                       flex
                       items-center
                       justify-center">

                <i
                    data-lucide="chevron-left"
                    class="w-5 h-5">
                </i>

            </a>

        @endif

        {{-- Page Numbers --}}
        @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)

        @endforeach

    </div>

</div>

@endif
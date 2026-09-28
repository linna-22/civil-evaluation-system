@props([
    'paginator',
])

@if ($paginator->hasPages())

    <div
        class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-white">

        {{-- Left --}}
        <div class="text-xs text-gray-500">

            កំពុងបង្ហាញ

            <span class="font-semibold text-gray-700">
                {{ $paginator->firstItem() }}
            </span>

            ដល់

            <span class="font-semibold text-gray-700">
                {{ $paginator->lastItem() }}
            </span>

            នៃ

            <span class="font-semibold text-gray-700">
                {{ $paginator->total() }}
            </span>

            ទិន្នន័យ

        </div>


        {{-- Right --}}
        <div class="flex items-center gap-1">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())

                <span
                    class="flex
                           h-7
                           w-7
                           items-center
                           justify-center
                           rounded-md
                           border
                           border-gray-200
                           text-gray-300">

                    <i
                        data-lucide="chevron-left"
                        class="h-3.5 w-3.5">
                    </i>

                </span>

            @else

                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="flex
                           h-7
                           w-7
                           items-center
                           justify-center
                           rounded-md
                           border
                           border-gray-200
                           text-gray-600
                           transition
                           hover:border-blue-300
                           hover:bg-blue-50">

                    <i
                        data-lucide="chevron-left"
                        class="h-3.5 w-3.5">
                    </i>

                </a>

            @endif


            {{-- Page Numbers --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)

                @if ($page == $paginator->currentPage())

                    {{-- Active page --}}
                    <span
                        class="flex
                               h-7
                               w-7
                               items-center
                               justify-center
                               rounded-md
                               bg-blue-600
                               text-xs
                               font-semibold
                               text-white">

                        {{ $page }}

                    </span>

                @else

                    {{-- Other page --}}
                    <a
                        href="{{ $url }}"
                        class="flex
                               h-7
                               w-7
                               items-center
                               justify-center
                               rounded-md
                               border
                               border-gray-200
                               bg-white
                               text-xs
                               font-medium
                               text-gray-600
                               transition
                               hover:border-blue-300
                               hover:bg-blue-50">

                        {{ $page }}

                    </a>

                @endif

            @endforeach


            {{-- Next --}}
            @if ($paginator->hasMorePages())

                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="flex
                           h-7
                           w-7
                           items-center
                           justify-center
                           rounded-md
                           border
                           border-gray-200
                           text-gray-600
                           transition
                           hover:border-blue-300
                           hover:bg-blue-50">

                    <i
                        data-lucide="chevron-right"
                        class="h-3.5 w-3.5">
                    </i>

                </a>

            @else

                <span
                    class="flex
                           h-7
                           w-7
                           items-center
                           justify-center
                           rounded-md
                           border
                           border-gray-200
                           text-gray-300">

                    <i
                        data-lucide="chevron-right"
                        class="h-3.5 w-3.5">
                    </i>

                </span>

            @endif

        </div>

    </div>

@endif
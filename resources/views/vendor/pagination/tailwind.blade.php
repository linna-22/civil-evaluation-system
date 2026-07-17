@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <div class="flex gap-2 items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 cursor-not-allowed leading-5 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-200">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-200">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 cursor-not-allowed leading-5 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        <div class="hidden sm:flex items-center justify-between">

            {{-- Left --}}
            <div class="text-sm text-gray-500">

                កំពុងបង្ហាញ

                <span class="font-semibold text-gray-800">
                    {{ $paginator->firstItem() }}
                </span>

                ដល់

                <span class="font-semibold text-gray-800">
                    {{ $paginator->lastItem() }}
                </span>

                នៃ

                <span class="font-semibold text-gray-800">
                    {{ $paginator->total() }}
                </span>

                ទិន្នន័យ

            </div>

            {{-- Right --}}
            <div class="flex items-center gap-2">

                {{-- Previous --}}
                @if ($paginator->onFirstPage())

                    <span class="w-10 h-10
                               rounded-xl
                               border
                               border-gray-200
                               flex
                               items-center
                               justify-center
                               text-gray-300">

                        <i data-lucide="chevron-left" class="w-5 h-5">
                        </i>

                    </span>

                @else

                    <a href="{{ $paginator->previousPageUrl() }}" class="w-10
                               h-10
                               rounded-xl
                               border
                               border-gray-200
                               flex
                               items-center
                               justify-center
                               hover:bg-blue-50
                               hover:border-blue-300
                               transition">

                        <i data-lucide="chevron-left" class="w-5 h-5">
                        </i>

                    </a>

                @endif

                {{-- Numbers --}}
                @foreach ($elements as $element)

                    @if (is_string($element))

                        <span class="w-10
                                       h-10
                                       flex
                                       items-center
                                       justify-center
                                       text-gray-400">

                            {{ $element }}

                        </span>

                    @endif

                    @if (is_array($element))

                        @foreach ($element as $page => $url)

                            @if ($page == $paginator->currentPage())

                                <span class="w-10
                                                       h-10
                                                       rounded-xl
                                                       bg-primary
                                                       text-blue-500
                                                       shadow-sm
                                                       flex
                                                       items-center
                                                       justify-center
                                                       font-semibold">

                                    {{ $page }}

                                </span>

                            @else

                                <a href="{{ $url }}" class="w-10
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

                                    {{ $page }}

                                </a>

                            @endif

                        @endforeach

                    @endif

                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())

                    <a href="{{ $paginator->nextPageUrl() }}" class="w-10
                               h-10
                               rounded-xl
                               border
                               border-gray-200
                               flex
                               items-center
                               justify-center
                               hover:bg-blue-50
                               hover:border-blue-300
                               transition">

                        <i data-lucide="chevron-right" class="w-5 h-5">
                        </i>

                    </a>

                @else

                    <span class="w-10
                               h-10
                               rounded-xl
                               border
                               border-gray-200
                               flex
                               items-center
                               justify-center
                               text-gray-300">

                        <i data-lucide="chevron-right" class="w-5 h-5">
                        </i>

                    </span>

                @endif

            </div>

        </div>
    </nav>
@endif
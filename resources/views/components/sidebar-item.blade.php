@props(['icon', 'title', 'route' => null, 'url' => '#', 'children' => []])

@php

    $hasChildren = !empty($children);

    // Parent active
    $active = $route ? request()->routeIs($route) : false;

    // Check if any child is active
    $childActive = false;

    foreach ($children as $child) {
        if (isset($child['route']) && request()->routeIs($child['route'])) {
            $childActive = true;
            break;
        }
    }
    // Open dropdown automatically if child is active
    $isOpen = $active || $childActive;
@endphp


<div class="sidebar-menu-group">

    {{-- ================================================= --}}
    {{-- Parent Menu --}}
    {{-- ================================================= --}}

    @if ($hasChildren)

        <button type="button"
            class="
                sidebar-dropdown-toggle
                w-full
                flex
                items-center
                gap-4
                h-12
                mx-4
                px-4
                rounded-2xl
                transition-all
                duration-300
                text-white
                hover:bg-white/10
            "
            style="width: calc(100% - 2rem);" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">

            {{-- Icon --}}
            <i data-lucide="{{ $icon }}" class="w-6 h-6 shrink-0">
            </i>


            {{-- Title --}}
            <span
                class="
                    sidebar-text
                    font-body
                    text-base
                    flex-1
                    text-left
                ">
                {{ $title }}
            </span>


            {{-- Arrow --}}
            <i data-lucide="chevron-right"
                class="
                    sidebar-dropdown-arrow
                    w-5
                    h-5
                    shrink-0
                    transition-transform
                    duration-300
                    {{ $isOpen ? 'rotate-90' : '' }}
                "></i>

        </button>


        {{-- ================================================= --}}
        {{-- Dropdown --}}
        {{-- ================================================= --}}

        <div
            class="
                sidebar-submenu
                overflow-hidden
                transition-all
                duration-300
                {{ $isOpen ? '' : 'hidden' }}
            ">

            <div class="mt-1 mb-2 ml-8 pl-6 border-l border-white/20">

                @foreach ($children as $child)
                    @php
                        $childActive = isset($child['route']) && request()->routeIs($child['route']);
                    @endphp
                    <a href="{{ $child['url'] === '#' ? '#' : route($child['url']) }}"
                        class="
                            flex
                            items-center
                            gap-3
                            min-h-10
                            px-4
                            py-2
                            rounded-xl
                            transition-all
                            duration-200
                            font-body
                            text-sm

                            {{ $childActive ? 'bg-blue-300 text-blue-600 shadow-sm' : 'text-white hover:bg-white/10' }}
                        ">

                        {{-- Small dot --}}
                        {{-- <span
                            class="
                                w-1.5
                                h-1.5
                                rounded-full
                                shrink-0
                                {{ $childActive ? 'bg-blue-600' : 'bg-blue-200' }}
                            "></span> --}}


                        <span>
                            {{ $child['title'] }}
                        </span>

                    </a>
                @endforeach

            </div>

        </div>
    @else
        {{-- ================================================= --}}
        {{-- Normal Menu Item --}}
        {{-- ================================================= --}}

        <a href="{{ $url }}"
            class="
                sidebar-item
                flex
                items-center
                gap-4
                h-12
                mx-4
                px-4
                rounded-2xl
                transition-all
                duration-300
                {{ $active ? 'bg-blue-300 text-blue-600 shadow-md' : 'text-white hover:bg-white/10' }}">
            <i data-lucide="{{ $icon }}" class="w-6 h-6 shrink-0">
            </i>
            <span class="sidebar-text font-body text-base">
                {{ $title }}
            </span>
        </a>
    @endif
</div>

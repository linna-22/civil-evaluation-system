@props([
    'id' => null,
    'placeholder' => 'ស្វែងរក...',
    'name' => 'search',
])

<div class="relative">

    <i
        data-lucide="search"
        class="absolute
               left-4
               top-1/2
               -translate-y-1/2
               w-5
               h-5
               text-gray-400">
    </i>

    <input

    id="{{ $id }}"

    type="text"

    name="{{ $name }}"

    value="{{ request($name) }}"

    placeholder="{{ $placeholder }}"

    class="w-80
           h-10
           pl-12
           pr-4
           rounded-2xl
           border
           border-gray-200
           bg-white
           outline-none
           focus:ring-1
           focus:ring-blue-600
           focus:border-blue-600
           transition">

</div>
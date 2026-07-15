@props([
    'type' => 'button',
])

<button
    type="{{ $type }}"

    {{ $attributes->merge([
        'class' => 'w-full h-14 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-lg transition duration-300 shadow-lg cursor-pointer'
    ]) }}>

    {{ $slot }}

</button>
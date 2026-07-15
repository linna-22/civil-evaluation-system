@props([
    'label',
    'name',
    'placeholder' => '',
])

<div>

    <label
        for="{{ $name }}"
        class="block mb-2 text-gray-700 font-medium">

        {{ $label }}

    </label>

    <div class="relative">

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="password"
            placeholder="{{ $placeholder }}"

            {{ $attributes->merge([
                'class' => 'w-full h-14 rounded-2xl border border-gray-300 px-5 pr-14 outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition'
            ]) }}>

       <button
    type="button"
    onclick="togglePassword('{{ $name }}', this)"
    class="absolute right-5 top-1/2 -translate-y-1/2
           flex items-center justify-center
           p-1 bg-transparent border-0 cursor-pointer
           text-gray-500 hover:text-blue-600">

    <i
        data-lucide="eye"
        class="icon-eye w-5 h-5">
    </i>

    <i
        data-lucide="eye-off"
        class="icon-eye-off w-5 h-5 hidden">
    </i>

</button>

    </div>

    @error($name)
        <p class="text-red-500 text-sm mt-2">
            {{ $message }}
        </p>
    @enderror

</div>
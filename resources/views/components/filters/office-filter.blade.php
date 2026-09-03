@props([
    'id' => null,
    'name' => 'office_id',
    'offices' => [],
    'placeholder' => 'ជ្រើសរើសការិយាល័យ',
])

<div class="relative">

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        class="w-70
               h-11
               px-4
               pr-10
               rounded-2xl
               border
               border-gray-200
               bg-white
               text-sm
               text-gray-700
               outline-none
               focus:ring-1
               focus:ring-blue-600
               focus:border-blue-600
               transition
               appearance-none
               cursor-pointer"
    >

        <option value="">
            {{ $placeholder }}
        </option>

        @foreach ($offices as $office)
            <option
                value="{{ $office->office_id }}"
                @selected(request($name) == $office->office_id)
            >
                {{ $office->office_name_kh }}
            </option>
        @endforeach

    </select>

    {{-- Dropdown icon --}}
    <i
        data-lucide="chevron-down"
        class="absolute
               right-4
               top-1/2
               -translate-y-1/2
               w-4
               h-4
               text-gray-400
               pointer-events-none">
    </i>

</div>
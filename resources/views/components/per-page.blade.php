@props([
    'id' => null,
    'value' => 5,
])

<div class="flex items-center gap-2">
    <span class="text-sm text-gray-700">
        បង្ហាញទិន្នន័យ
    </span>

    <select
        id="{{ $id }}"
        class="h-10 rounded-2xl border border-blue-300 px-2 bg-white">

        <option value="5" @selected($value == 5)>5</option>
        <option value="10" @selected($value == 10)>10</option>
        <option value="25" @selected($value == 25)>25</option>
        <option value="50" @selected($value == 50)>50</option>
        <option value="100" @selected($value == 100)>100</option>

    </select>
</div>
@props([
    'id' => null,
    'value' => 5,
])

<select
    id="{{ $id }}"
    class="h-10 rounded-2xl border border-gray-200 px-2 bg-white">

    <option value="5" @selected($value == 5)>5</option>
    <option value="10" @selected($value == 10)>10</option>
    <option value="25" @selected($value == 25)>25</option>
    <option value="50" @selected($value == 50)>50</option>
    <option value="100" @selected($value == 100)>100</option>

</select>
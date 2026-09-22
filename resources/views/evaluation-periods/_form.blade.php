<form
    action="{{ isset($evaluationPeriod)
        ? route('evaluation-periods.update', $evaluationPeriod)
        : route('evaluation-periods.store') }}"
    method="POST" class="space-y-6">

    @csrf

    @isset($evaluationPeriod)
        @method('PUT')
    @endisset


    {{-- ==========================================
        Row 1 - Evaluation Name
    ========================================== --}}

    <div class="grid grid-cols-2 gap-5">
        <x-input label="ឈ្មោះការវាយតម្លៃ (ភាសាខ្មែរ)" name="name_kh" :value="old('name_kh', $evaluationPeriod->name_kh ?? '')" placeholder="បញ្ចូលឈ្មោះការវាយតម្លៃ"
            required />

        <x-input label="ឈ្មោះការវាយតម្លៃ (ភាសាអង់គ្លេស)" name="name_en" :value="old('name_en', $evaluationPeriod->name_en ?? '')"
            placeholder="បញ្ចូលឈ្មោះការវាយតម្លៃជាភាសាអង់គ្លេស" required />

    </div>


    {{-- ==========================================
        Row 2 - Month & Year
    ========================================== --}}

    <div class="grid grid-cols-2 gap-5">

        <x-forms.select label="ខែវាយតម្លៃ" name="month" :selected="old('month', $evaluationPeriod->month ?? '')" :options="[
            1 => 'មករា',
            2 => 'កុម្ភៈ',
            3 => 'មីនា',
            4 => 'មេសា',
            5 => 'ឧសភា',
            6 => 'មិថុនា',
            7 => 'កក្កដា',
            8 => 'សីហា',
            9 => 'កញ្ញា',
            10 => 'តុលា',
            11 => 'វិច្ឆិកា',
            12 => 'ធ្នូ',
        ]" required />

        <x-input label="ឆ្នាំវាយតម្លៃ" name="year" type="number" :value="old('year', $evaluationPeriod->year ?? date('Y'))" placeholder="បញ្ចូលឆ្នាំ"
            required />

    </div>


    {{-- ==========================================
        Row 3 - Date Range
    ========================================== --}}

    <div class="grid grid-cols-2 gap-5">

        <x-input label="ថ្ងៃចាប់ផ្តើមវាយតម្លៃ" name="start_date" id="start_date" type="text" :value="old(
            'start_date',
            isset($evaluationPeriod->start_date) ? $evaluationPeriod->start_date->format('Y-m-d') : '',
        )"
            placeholder="ជ្រើសរើសថ្ងៃចាប់ផ្តើម" required />

        <x-input label="ថ្ងៃបញ្ចប់វាយតម្លៃ" name="end_date" id="end_date" type="text" :value="old(
            'end_date',
            isset($evaluationPeriod->end_date) ? $evaluationPeriod->end_date->format('Y-m-d') : '',
        )"
            placeholder="ជ្រើសរើសថ្ងៃបញ្ចប់" required />

    </div>
    {{-- ==========================================
    Row 4 - Participating Departments
========================================== --}}

    <div class="space-y-3">

        <div>
            <label class="block text-sm font-medium text-gray-700">
                អង្គភាពចូលរួមវាយតម្លៃ
                <span class="text-red-500">*</span>
            </label>

            <p class="text-sm text-gray-500 mt-1">
                សូមជ្រើសរើសអង្គភាពដែលត្រូវចូលរួមក្នុងការវាយតម្លៃនេះ។
            </p>
        </div>

        <div class="grid grid-cols-2 gap-3">

            @foreach ($departments as $department)
                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">

                    <input type="checkbox" name="department_ids[]" value="{{ $department->department_id }}"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        {{ in_array($department->department_id, old('department_ids', [])) ? 'checked' : '' }}>

                    <span class="text-sm text-gray-700">
                        {{ $department->department_name_kh }}
                    </span>

                </label>
            @endforeach

        </div>

        @error('department_ids')
            <p class="text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

        @error('department_ids.*')
            <p class="text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>
    {{-- ==========================================
        Footer
    ========================================== --}}

    <div class="flex justify-end gap-3 pt-6 mt-6">

        <x-action-btn href="{{ route('evaluation-periods.index') }}" variant="secondary" icon="x">
            បោះបង់
        </x-action-btn>

        <x-action-btn icon="save">

            {{ isset($evaluationPeriod) ? 'កែប្រែ' : 'រក្សាទុក' }}

        </x-action-btn>

    </div>

</form>

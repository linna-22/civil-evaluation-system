<form
    action="{{ isset($office)
        ? route('offices.update', $office)
        : route('offices.store') }}"
    method="POST"
    class="space-y-6">

    @csrf

    @isset($office)
        @method('PUT')
    @endisset

    {{-- Row 1 --}}
    <div class="grid grid-cols gap-5">
        <x-forms.select
            label="នាយកដ្ឋាន"
            name="department_id"
            required
            :selected="old('department_id', $office?->department_id)"
            :options="$department->pluck('department_name_kh', 'department_id')->toArray()"
        />
    </div>
    <div class="grid grid-cols-2 gap-5">
        <x-input
            label="ឈ្មោះការិយាល័យ (ភាសាខ្មែរ)"
            name="name_kh"
            :value="old('name_kh', $office->office_name_kh ?? '')"
            placeholder="បញ្ចូលឈ្មោះការិយាល័យ"
            required />

        <x-input
            label="ឈ្មោះការិយាល័យ (អង់គ្លេស)"
            name="name_en"
            :value="old('name_en', $office->office_name_en ?? '')"
            placeholder="បញ្ចូលឈ្មោះការិយាល័យ" />

    </div>

    {{-- Row 2 --}}
    <div class="grid grid-cols-2 gap-5">

        <x-input
            label="លេខកូដការិយាល័យ"
            name="code"
            :value="old('code', $office->office_code ?? '')"
            required />

        <x-forms.select
            label="ស្ថានភាព"
            name="status"
            :selected="old('status', $office->status ?? 'active')"
            :options="[
                'active' => 'សកម្ម',
                'inactive' => 'អសកម្ម',
            ]" />

    </div>

    {{-- Description --}}
    <x-forms.textarea
        label="បរិយាយ"
        name="description"
        :value="old('description', $office->desc ?? '')" />

    {{-- Footer --}}
    <div class="flex justify-end gap-3 pt-6 mt-6 border-t">

        <x-action-btn
            href="{{ route('offices.index') }}"
            variant="secondary"
            icon="x">

            បោះបង់

        </x-action-btn>

        <x-action-btn icon="save">

            {{ isset($office) ? 'កែប្រែ' : 'រក្សាទុក' }}

        </x-action-btn>

    </div>

</form>
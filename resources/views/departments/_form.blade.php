<form
    action="{{ isset($department)
        ? route('departments.update', $department)
        : route('departments.store') }}"
    method="POST"
    class="space-y-6">

    @csrf

    @isset($department)
        @method('PUT')
    @endisset

    {{-- Row 1 --}}
    <div class="grid grid-cols gap-5">
        <x-forms.select
            label="អង្គភាព"
            name="organization_id"
            required
            :selected="old('organization_id', $department?->organization_id)"
            :options="$organizations->pluck('org_name_kh', 'organization_id')->toArray()"
        />
    </div>
    <div class="grid grid-cols-2 gap-5">
        <x-input
            label="ឈ្មោះនាយកដ្ឋាន (ភាសាខ្មែរ)"
            name="name_kh"
            :value="old('name_kh', $department->department_name_kh ?? '')"
            placeholder="បញ្ចូលឈ្មោះនាយកដ្ឋាន"
            required />

        <x-input
            label="ឈ្មោះនាយកដ្ឋាន (អង់គ្លេស)"
            name="name_en"
            :value="old('name_en', $department->department_name_en ?? '')"
            placeholder="បញ្ចូលឈ្មោះនាយកដ្ឋាន" />

    </div>

    {{-- Row 2 --}}
    <div class="grid grid-cols-2 gap-5">

        <x-input
            label="លេខកូដនាយកដ្ឋាន"
            name="code"
            :value="old('code', $department->department_code ?? '')"
            required />

        <x-forms.select
            label="ស្ថានភាព"
            name="status"
            :selected="old('status', $department->status ?? 'active')"
            :options="[
                'active' => 'សកម្ម',
                'inactive' => 'អសកម្ម',
            ]" />

    </div>

    {{-- Description --}}
    <x-forms.textarea
        label="បរិយាយ"
        name="description"
        :value="old('description', $department->desc ?? '')" />

    {{-- Footer --}}
    <div class="flex justify-end gap-3 pt-6 mt-6 border-t">

        <x-action-btn
            href="{{ route('departments.index') }}"
            variant="secondary"
            icon="x">

            បោះបង់

        </x-action-btn>

        <x-action-btn icon="save">

            {{ isset($department) ? 'កែប្រែ' : 'រក្សាទុក' }}

        </x-action-btn>

    </div>

</form>
<form
    action="{{ isset($organization)
        ? route('organizations.update', $organization)
        : route('organizations.store') }}"
    method="POST"
    class="space-y-6">

    @csrf

    @isset($organization)
        @method('PUT')
    @endisset

    {{-- Row 1 --}}
    <div class="grid grid-cols-2 gap-5">

        <x-input
            label="ឈ្មោះអង្គភាព (ភាសាខ្មែរ)"
            name="name_kh"
            :value="old('name_kh', $organization->org_name_kh ?? '')"
            placeholder="បញ្ចូលឈ្មោះអង្គភាព"
            required />

        <x-input
            label="ឈ្មោះអង្គភាព (អង់គ្លេស)"
            name="name_en"
            :value="old('name_en', $organization->org_name_en ?? '')"
            placeholder="បញ្ចូលឈ្មោះអង្គភាព" />

    </div>

    {{-- Row 2 --}}
    <div class="grid grid-cols-2 gap-5">

        <x-input
            label="លេខកូដអង្គភាព"
            name="code"
            :value="old('code', $organization->org_code ?? '')"
            required />

        <x-forms.select
            label="ស្ថានភាព"
            name="status"
            :selected="old('status', $organization->status ?? 'active')"
            :options="[
                'active' => 'សកម្ម',
                'inactive' => 'អសកម្ម',
            ]" />

    </div>

    {{-- Description --}}
    <x-forms.textarea
        label="បរិយាយ"
        name="description"
        :value="old('description', $organization->desc ?? '')" />

    {{-- Footer --}}
    <div class="flex justify-end gap-3 pt-6 mt-6 border-t">

        <x-action-btn
            href="{{ route('organizations.index') }}"
            variant="secondary"
            icon="x">

            បោះបង់

        </x-action-btn>

        <x-action-btn icon="save">

            {{ isset($organization) ? 'កែប្រែ' : 'រក្សាទុក' }}

        </x-action-btn>

    </div>

</form>
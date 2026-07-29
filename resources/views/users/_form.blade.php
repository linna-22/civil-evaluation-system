<form
    action="{{ isset($user)
        ? route('users.update', $user)
        : route('users.store') }}"
    method="POST"
    class="space-y-6">

    @csrf

    @isset($user)
        @method('PUT')
    @endisset

    {{-- Row 1 --}}
    <div class="grid grid-cols-2 gap-5">

        <x-input
            label="គោត្តនាមនិងនាម"
            name="name_kh"
            :value="old('name_kh', $user->name_kh ?? '')"
            placeholder="បញ្ចូលគោត្តនាមនិងនាម"
            required />

        <x-input
            label="អក្សរឡាតាំង"
            name="name_en"
            :value="old('name_en', $user->name_en ?? '')"
            placeholder="បញ្ចូលអក្សរឡាតាំង" />

    </div>
    <div class="grid grid-cols-2 gap-5">

        <x-forms.searchable-select
            label="ស្ថាប័ន"
            name="organization_id"
            id="organization"
            :selected="old('organization_id', $user->organization_id ?? '')"
            :options="$organizations->pluck('org_name_kh', 'organization_id')->toArray()"
            placeholder="ជ្រើសរើសស្ថាប័ន"
            required
        />

       <x-forms.searchable-select
            label="នាយកដ្ឋាន"
            name="department_id"
            id="department"
            :selected="old('department_id', $user->department_id ?? '')"
            data-selected="{{ old('department_id', $user->department_id ?? '') }}"
            :options="[]"
            placeholder="ជ្រើសរើសនាយកដ្ឋាន"
            disabled
            required
        />

    </div>
    <div class="grid grid-cols-2 gap-5">

        <x-input
            label="ឈ្មោះអ្នកប្រើប្រាស់"
            name="username"
            :value="old('username', $user->username ?? '')"
            placeholder="បញ្ចូលឈ្មោះអ្នកប្រើប្រាស់"
            required />

         <x-forms.select
            label="ភេទ"
            name="gender"
            :selected="old('gender', $user->gender ?? 'male')"
            :options="[
                'male' => 'ប្រុស',
                'female' => 'ស្រី',
            ]" />

    </div>
     {{-- Row 2 --}}
    <div class="grid grid-cols-2 gap-5">

        <x-input
            label="លេខទូរស័ព្ទ"
            name="phone"
            :value="old('phone', $user->phone ?? '')"
            required />
        <x-input
            label="តួនាទី"
            name="position"
            :value="old('position', $user->position ?? '')"
            required />

    </div>
    <div class="grid grid-cols-2 gap-5">

        <x-input
            label="អ៊ីមែល"
            name="email"
            type="email"
            :value="old('email', $user->email ?? '')"
            placeholder="បញ្ចូលអ៊ីមែល"
            required />
        <x-input
            label="លេខសម្ងាត់"
            name="password"
            type="password"
            :required="!isset($user)"
            :disabled="isset($user)"
            :value="old('password', $user->password ?? '')"
            placeholder="**********"
            required />

        
    </div>
    <div class="grid grid-cols-2 gap-5">

        <x-forms.select
            label="Role"
            name="role"
            :selected="old('role', $user->role ?? 'user')"
            :options="[
                'organization_admin' => 'អ្នកគ្រប់គ្រងអង្គភាព',
                'department_admin' => 'អ្នកគ្រប់គ្រងនាយកដ្ឋាន',
                'user' => 'អ្នកប្រើប្រាស់',
            ]" />
        <x-forms.select
            label="ស្ថានភាព"
            name="status"
            :selected="old('status', $user->status ?? 'active')"
            :options="[
                'active' => 'សកម្ម',
                'inactive' => 'អសកម្ម',
            ]" />

        
    </div>

   


    {{-- Footer --}}
    <div class="flex justify-end gap-3 pt-6 mt-6 border-t">

        <x-action-btn
            href="{{ route('users.index') }}"
            variant="secondary"
            icon="x">

            បោះបង់

        </x-action-btn>

        <x-action-btn icon="save">

            {{ isset($user) ? 'កែប្រែ' : 'រក្សាទុក' }}

        </x-action-btn>

    </div>

</form>
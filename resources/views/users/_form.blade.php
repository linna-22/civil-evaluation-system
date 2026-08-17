<form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST" class="space-y-6">

    @csrf

    @isset($user)
        @method('PUT')
    @endisset

    {{-- Row 1 --}}
    <div class="grid grid-cols-3 gap-5">

        <x-input label="គោត្តនាមនិងនាម" name="name_kh" :value="old('name_kh', $user->name_kh ?? '')" placeholder="បញ្ចូលគោត្តនាមនិងនាម" required />

        <x-input label="អក្សរឡាតាំង" name="name_en" :value="old('name_en', $user->name_en ?? '')" placeholder="បញ្ចូលអក្សរឡាតាំង" />

        <x-input label="ឈ្មោះអ្នកប្រើប្រាស់" name="username" :value="old('username', $user->username ?? '')" placeholder="បញ្ចូលឈ្មោះអ្នកប្រើប្រាស់"
            required />

    </div>


    {{-- Row 2 --}}
    <div class="grid grid-cols-3 gap-5">

        {{-- Organization --}}
        <x-forms.searchable-select label="ស្ថាប័ន" name="organization_id" id="organization" :selected="old('organization_id', $user->organization_id ?? '')"
            :options="$organizations->pluck('org_name_kh', 'organization_id')->toArray()" placeholder="ជ្រើសរើសស្ថាប័ន" required />

        {{-- Department --}}
        <x-forms.searchable-select label="នាយកដ្ឋាន" name="department_id" id="department" :selected="old('department_id', $user->department_id ?? '')"
            data-selected="{{ old('department_id', $user->department_id ?? '') }}" :options="[]"
            placeholder="ជ្រើសរើសនាយកដ្ឋាន" disabled />

        {{-- Office --}}
        <x-forms.searchable-select label="ការិយាល័យ" name="office_id" id="office" :selected="old('office_id', $user->office_id ?? '')"
            data-selected="{{ old('office_id', $user->office_id ?? '') }}" :options="[]"
            placeholder="ជ្រើសរើសការិយាល័យ" disabled />
    </div>


    {{-- Row 3 --}}
    <div class="grid grid-cols-3 gap-5">

        <x-forms.select label="ភេទ" name="gender" :selected="old('gender', $user->gender ?? 'male')" :options="[
            'male' => 'ប្រុស',
            'female' => 'ស្រី',
        ]" />

        <x-input label="លេខទូរស័ព្ទ" name="phone" :value="old('phone', $user->phone ?? '')" required />

        <x-input label="អ៊ីមែល" name="email" type="email" :value="old('email', $user->email ?? '')" placeholder="បញ្ចូលអ៊ីមែល" required />

    </div>


    {{-- Row 4 --}}
    <div class="grid grid-cols-3 gap-5">

        <x-input label="តួនាទី" name="position" :value="old('position', $user->position ?? '')" required />

        <x-input label="អត្តលេខ" name="id_code" :value="old('id_code', $user->id_code ?? '')" placeholder="បញ្ចូលអត្តលេខ" />

        <x-input label="លេខសម្ងាត់" name="password" type="password" :required="!isset($user)" :disabled="isset($user)"
            :value="old('password', $user->password ?? '')" placeholder="********" />

    </div>


    {{-- Row 5 --}}
    <div class="grid grid-cols-3 gap-5">

        {{-- Role --}}
        
        @if(auth()->user()->role === 'super_admin')
            <x-forms.select label="Role" name="role" :selected="old('role', $user->role ?? 'user')" :options="[
            'super_admin' => 'Super Admin',
            'organization_admin' => 'អ្នកគ្រប់គ្រងអង្គភាព',
            'department_admin' => 'អ្នកគ្រប់គ្រងនាយកដ្ឋាន',
            'user' => 'អ្នកប្រើប្រាស់',
        ]" />
        @else 
         <x-forms.select label="Role" name="role" :selected="old('role', $user->role ?? 'user')" :options="[
            'organization_admin' => 'អ្នកគ្រប់គ្រងអង្គភាព',
            'department_admin' => 'អ្នកគ្រប់គ្រងនាយកដ្ឋាន',
            'user' => 'អ្នកប្រើប្រាស់',
        ]" />
        @endif

        {{-- Status --}}
        <x-forms.select label="ស្ថានភាព" name="status" :selected="old('status', $user->status ?? 'active')" :options="[
            'active' => 'សកម្ម',
            'inactive' => 'អសកម្ម',
        ]" />

        {{-- Leader --}}
        <x-forms.select label="ថ្នាក់ដឹកនាំ" name="is_leader" :selected="old('is_leader', $user->is_leader ?? 0)" :options="[
            1 => 'ជាថ្នាក់ដឹកនាំ',
            0 => 'មិនមែនជាថ្នាក់ដឹកនាំ',
        ]" />

    </div>




    {{-- Footer --}}
    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">

        <x-action-btn href="{{ route('users.index') }}" variant="secondary" icon="x">

            បោះបង់

        </x-action-btn>

        <x-action-btn icon="save">

            {{ isset($user) ? 'កែប្រែ' : 'រក្សាទុក' }}

        </x-action-btn>

    </div>

</form>

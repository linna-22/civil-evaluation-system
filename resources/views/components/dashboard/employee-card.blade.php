<x-layout.page-card
    title="ព័ត៌មានមន្ត្រី"
    description="ព័ត៌មានផ្ទាល់ខ្លួនរបស់មន្ត្រី"
    icon="user-round">

    {{-- Profile --}}
    <div class="flex flex-col items-center">

        <h3 class="mt-2 text-xl font-bold text-gray-800">

            {{ $user->name_kh }}

        </h3>

        <p class="mt-1 text-gray-500">

            {{ $user->position }}

        </p>

    </div>

    {{-- Divider --}}
    <div class="my-5 border-t border-gray-200"></div>

    {{-- Employee Information --}}
    <div class="divide-y divide-gray-100">

        <x-dashboard.info-item
            label="ភេទ"
            :value="match($user->gender) {
                'male' => 'ប្រុស',
                'female' => 'ស្រី',
                default => '-',
            }" />

        <x-dashboard.info-item
            label="នាយកដ្ឋាន"
            :value="$user->department->department_name_kh ?? '-'" />

        <x-dashboard.info-item
            label="អង្គភាព"
            :value="$user->organization->org_name_kh ?? '-'" />

    </div>

</x-layout.page-card>
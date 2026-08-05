<div class="grid grid-cols-1 gap-4 lg:grid-cols-12">

    {{-- Search --}}
    <div class="relative lg:col-span-8">

        <i data-lucide="search"
           class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400">
        </i>

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="ស្វែងរកតាមឈ្មោះ..."
            class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-11 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">

    </div>

    {{-- Department --}}
    <div class="lg:col-span-4">

        <x-filters.select
            label="នាយកដ្ឋាន"
            name="department"
            placeholder="នាយកដ្ឋានទាំងអស់"
            :options="$departments"
            :selected="request('department')" />

    </div>

</div>
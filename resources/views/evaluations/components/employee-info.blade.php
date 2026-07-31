<x-layout.page-card
    title="ព័ត៌មានបុគ្គលិក"
    description="ព័ត៌មានមូលដ្ឋានរបស់បុគ្គលិក និងខែវាយតម្លៃ"
    icon="user-round"
    class="mb-6">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        {{-- Employee Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                ឈ្មោះ
            </label>

            <span>{{ auth()->user()->name_kh ?? 'Employee Name' }}</span>
        </div>

        {{-- Organization --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                អង្គភាព
            </label>
            <span>
                {{ auth()->user()->organization->org_name_kh ?? '' }}
            </span>

        </div>

        {{-- Department --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                នាយកដ្ឋាន
            </label>

            <span>{{ auth()->user()->department->department_name_kh ?? '' }}</span>
        </div>

        {{-- Position --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                មុខតំណែង
            </label>
            <span>{{ auth()->user()->position ?? '' }}</span>
        </div>

        {{-- Month --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                ខែវាយតម្លៃ
            </label>

            <select
                name="month"
                id="month"
                class="w-full rounded-xl border-gray-300">

                @foreach(range(1,12) as $month)

                    <option value="{{ $month }}">

                        {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}

                    </option>

                @endforeach

            </select>
        </div>

        {{-- Year --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                ឆ្នាំវាយតម្លៃ
            </label>

            <select
                name="year"
                id="year"
                class="w-full rounded-xl border-gray-300">

                @for($year = now()->year; $year >= now()->year-5; $year--)

                    <option value="{{ $year }}">

                        {{ $year }}

                    </option>

                @endfor

            </select>

        </div>

    </div>

</x-layout.page-card>
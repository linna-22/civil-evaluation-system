<div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b px-6 py-4">

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                ព័ត៌មានការវាយតម្លៃ
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                ព័ត៌មានទូទៅនៃការវាយតម្លៃប្រចាំខែ
            </p>

        </div>

        <x-dashboard.status-badge
            :status="$evaluation->evaluation_status" />

    </div>

    {{-- Body --}}
    <div class="p-6">

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

            {{-- Name --}}
            <div>

                <p class="text-sm text-gray-500">
                    ឈ្មោះ
                </p>

                <p class="mt-1 font-semibold text-gray-800">

                    {{ $evaluation->user->name_kh }}

                </p>

            </div>

            {{-- Gender --}}
            <div>

                <p class="text-sm text-gray-500">
                    ភេទ
                </p>

                <p class="mt-1 font-semibold text-gray-800">

                    {{ $evaluation->user->gender == 'male' ? 'ប្រុស' : 'ស្រី' }}

                </p>

            </div>

            {{-- Position --}}
            <div>

                <p class="text-sm text-gray-500">
                    មុខតំណែង
                </p>

                <p class="mt-1 font-semibold text-gray-800">

                    {{ $evaluation->user->position }}

                </p>

            </div>

            {{-- Organization --}}
            <div>

                <p class="text-sm text-gray-500">
                    អង្គភាព
                </p>

                <p class="mt-1 font-semibold text-gray-800">

                    {{ $evaluation->user->organization->org_name_kh }}

                </p>

            </div>

            {{-- Department --}}
            <div>

                <p class="text-sm text-gray-500">
                    នាយកដ្ឋាន
                </p>

                <p class="mt-1 font-semibold text-gray-800">

                    {{ $evaluation->user->department->department_name_kh }}

                </p>

            </div>

            {{-- Evaluation Month --}}
            <div>

                <p class="text-sm text-gray-500">
                    កាលបរិច្ឆេទវាយតម្លៃ
                </p>

                <p class="mt-1 font-semibold text-gray-800">

                    {{ \Carbon\Carbon::create()
                        ->month($evaluation->evaluation_month)
                        ->translatedFormat('F') }}
                    {{ $evaluation->evaluation_year }}

                </p>

            </div>

            {{-- Submitted At --}}
            <div>

                <p class="text-sm text-gray-500">
                    កាលបរិច្ឆេទដាក់បញ្ជូនការវាយតម្លៃ
                </p>

                <p class="mt-1 font-semibold text-gray-800">

                    {{ $evaluation->submitted_at
                        ? \App\Helpers\DateHelper::khmerDateTime($evaluation->submitted_at)
                        : '-' }}
                </p>

            </div>

            {{-- Total Score --}}
            <div>

                <p class="text-sm text-gray-500">
                    ពិន្ទុសរុប
                </p>

                <p class="mt-1 text-lg font-bold text-blue-600">

                    {{ number_format($evaluation->total_score, 2) }} / 100

                </p>

            </div>

        </div>

    </div>

</div>
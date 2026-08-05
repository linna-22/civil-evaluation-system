<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

    {{-- <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">

        <div>

            <h3 class="text-lg font-semibold text-gray-800">
                បញ្ជីការវាយតម្លៃ
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                បង្ហាញការវាយតម្លៃដែលបានបញ្ជូនរួច
            </p>

        </div>

        <span class="rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600">

            សរុប 0 ទិន្នន័យ

        </span>

    </div> --}}

    <div class="overflow-x-auto font-body">

        <table class="min-w-full divide-y divide-gray-100">

            <thead class="bg-gray-50">

                <tr>

                    <th class="w-16 px-6 py-3 text-center text-sm font-semibold text-gray-500">
                        ល.រ
                    </th>

                    {{-- <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500">
                        អត្តលេខ
                    </th> --}}

                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500">
                        ឈ្មោះ
                    </th>

                    @if(auth()->user()->role === 'super_admin')

                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500">
                            អង្គភាព
                        </th>

                    @endif

                    @if(in_array(auth()->user()->role, ['super_admin','organization_admin']))

                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500">
                            នាយកដ្ឋាន
                        </th>

                    @endif

                    <th class="text-center px-6 py-3 text-sm font-semibold text-gray-500">
                        ខែ
                    </th>

                    <th class="text-center px-6 py-3 text-sm font-semibold text-gray-500">
                        ឆ្នាំ
                    </th>

                    <th class="text-center px-6 py-3 text-sm font-semibold text-gray-500">
                        ពិន្ទុសរុប
                    </th>

                    <th class="text-center px-6 py-3 text-sm font-semibold text-gray-500">
                        កាលបរិច្ឆេទ
                    </th>

                    <th class="text-center px-6 py-3 text-sm font-semibold text-gray-500">
                        សកម្មភាព
                    </th>

                </tr>

            </thead>

            @include('evaluations.partials.table-body')

        </table>

    </div>

    <div id="pagination">
    
        @include('evaluations.partials.pagination')
    
    </div>
</div>
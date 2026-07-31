<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">

        <div>
            <h2 class="text-lg font-semibold text-gray-800">
                ប្រវត្តិការវាយតម្លៃ
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                បង្ហាញប្រវត្តិការវាយតម្លៃប្រចាំខែរបស់អ្នក
            </p>
        </div>

        <button
            type="button"
            class="px-4 py-2 rounded-xl border border-gray-300 text-sm hover:bg-gray-50 transition">

            មើលទាំងអស់

        </button>

    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-gray-50">

                <tr class="text-left text-sm text-gray-600">

                    <th class="px-6 py-4 font-semibold">
                        ខែ
                    </th>

                    <th class="px-6 py-4 font-semibold">
                        ឆ្នាំ
                    </th>

                    <th class="px-6 py-4 font-semibold">
                        កាលបរិច្ឆេទដាក់
                    </th>

                    <th class="px-6 py-4 font-semibold">
                        ស្ថានភាព
                    </th>

                    <th class="px-6 py-4 text-center font-semibold">
                        សកម្មភាព
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100">

                {{-- Sample Row --}}
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-4">
                        មិថុនា
                    </td>

                    <td class="px-6 py-4">
                        ២០២៦
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        25/06/2026
                    </td>

                    <td class="px-6 py-4">

                        <x-dashboard.status-badge
                            status="approved" />

                    </td>

                    <td class="px-6 py-4 text-center">

                        <button
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-blue-600 hover:bg-blue-50 transition">

                            <i
                                data-lucide="eye"
                                class="w-4 h-4">
                            </i>

                            <span>មើល</span>

                        </button>

                    </td>

                </tr>

                {{-- Sample Row --}}
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-4">
                        ឧសភា
                    </td>

                    <td class="px-6 py-4">
                        ២០២៦
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        28/05/2026
                    </td>

                    <td class="px-6 py-4">

                        <x-dashboard.status-badge
                            status="submitted" />

                    </td>

                    <td class="px-6 py-4 text-center">

                        <button
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-blue-600 hover:bg-blue-50 transition">

                            <i
                                data-lucide="eye"
                                class="w-4 h-4">
                            </i>

                            <span>មើល</span>

                        </button>

                    </td>

                </tr>

                {{-- Empty State --}}
                {{-- Uncomment when no records exist --}}
                {{--
                <tr>

                    <td
                        colspan="5"
                        class="py-14 text-center">

                        <div class="flex flex-col items-center">

                            <i
                                data-lucide="clipboard-x"
                                class="w-12 h-12 text-gray-300">
                            </i>

                            <h3 class="mt-4 text-gray-700 font-medium">
                                មិនទាន់មានប្រវត្តិការវាយតម្លៃ
                            </h3>

                            <p class="text-sm text-gray-500 mt-2">
                                ប្រវត្តិការវាយតម្លៃរបស់អ្នកនឹងបង្ហាញនៅទីនេះ
                            </p>

                        </div>

                    </td>

                </tr>
                --}}

            </tbody>

        </table>

    </div>

</div>
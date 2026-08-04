<div class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b px-6 py-4">

        <div>

            <h3 class="text-lg font-semibold text-gray-800">
                📊 សមិទ្ធផលការងារ
            </h3>

            <p class="text-sm text-gray-500">
                សង្ខេបលទ្ធផលការងារប្រចាំខែ
            </p>

        </div>

        <span
            class="inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

            {{ number_format($evaluation->work_performance_score, 2) }}
            / 60 ពិន្ទុ

        </span>

    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-gray-50">

                <tr>

                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                        ល.រ
                    </th>

                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                        សកម្មភាពការងារ
                    </th>

                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                        សូចនាករ
                    </th>

                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">
                        ភាគរយ
                    </th>

                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">
                        ពិន្ទុ
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse ($evaluation->workPerformance as $performance)

                    <tr>

                        <td class="px-6 py-4">

                            {{ $loop->iteration }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $performance->activity }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $performance->indicator }}

                        </td>

                        <td class="px-6 py-4 text-center">

                            {{ number_format($performance->achievement_percent, 2) }}%

                        </td>

                        <td class="px-6 py-4 text-center font-semibold text-blue-600">

                            {{ number_format($performance->score, 2) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-8 text-center text-gray-500">

                            មិនមានទិន្នន័យសមិទ្ធផលការងារ

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Footer --}}
    {{-- <div class="border-t bg-gray-50 px-6 py-4">

        <div class="flex items-center justify-between">

            <span class="font-medium text-gray-700">

                ពិន្ទុសរុបសមិទ្ធផលការងារ

            </span>

            <span class="text-lg font-bold text-blue-600">

                {{ number_format($evaluation->work_performance_score, 2) }}
                / 60

            </span>

        </div>

    </div> --}}

</div>
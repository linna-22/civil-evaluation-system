<x-layout.page-card title="សមិទ្ធកម្មការងារ" description="បញ្ចូលសកម្មភាព និងលទ្ធផលសមិទ្ធកម្ម" icon="clipboard-list"
    class="mb-6">

    {{-- Toolbar --}}
    <div class="flex justify-between items-center mb-6">

        <p class="text-sm text-gray-500">
            សូមបំពេញសកម្មភាព និងលទ្ធផលសមិទ្ធកម្មរបស់អ្នក។
        </p>

        <button type="button" id="addPerformanceBtn"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition cursor-pointer">

            <i data-lucide="plus" class="w-4 h-4"></i>

            បន្ថែមការងារ

        </button>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full border border-gray-200">

            <thead class="bg-gray-50">

                <tr>

                    <th class="w-16 border px-3 py-3 text-center">
                        ល.រ
                    </th>

                    <th class="border px-4 py-3">
                        សកម្មភាពព្រមព្រៀងអនុវត្ត
                    </th>

                    <th class="border px-4 py-3">
                        សូចនាករសមិទ្ធកម្ម
                    </th>

                    <th class="w-40 border px-3 py-3 text-center">
                        លទ្ធផលសមិទ្ធកម្ម (%)
                    </th>

                    <th class="w-36 border px-3 py-3 text-center">
                        ពិន្ទុទទួលបាន
                    </th>

                    <th class="w-24 border px-3 py-3 text-center">
                        សកម្មភាព
                    </th>
                </tr>

            </thead>

            <tbody id="performanceTableBody">

                <tr>

                    <td class="border text-center font-medium row-number">
                        1
                    </td>

                    {{-- Activity --}}
                    <td class="border p-2">

                        <textarea name="performances[0][activity]" rows="2"
                            class="w-full rounded-lg outline-none focus:outline-none focus:ring-0" placeholder="បញ្ចូលសកម្មភាព..."></textarea>

                    </td>

                    {{-- Indicator --}}
                    <td class="border p-2">

                        <textarea name="performances[0][indicator]" rows="2"
                            class="w-full rounded-lg outline-none focus:outline-none focus:ring-0" placeholder="បញ្ចូលសូចនាករសមិទ្ធកម្ម..."></textarea>

                    </td>

                    {{-- Achievement --}}
                    <td class="border p-2">

                        <input type="number" name="performances[0][achievement_percent]" min="0" max="100"
                            class="w-full rounded-lg text-center outline-none focus:outline-none focus:ring-0"
                            placeholder="0">

                    </td>

                    {{-- Score --}}
                    <td class="border p-2">

                        <input type="text" name="performances[0][score]" readonly data-score value="0"
                            class="w-full bg-gray-100 rounded-lg text-center border-0">

                    </td>

                    {{-- Action --}}
                    <td class="border text-center">

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</x-layout.page-card>

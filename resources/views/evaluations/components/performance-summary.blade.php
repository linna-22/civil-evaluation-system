<x-layout.page-card
    title="សង្ខេបលទ្ធផល"
    icon="chart-column"
    class="mb-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="rounded-xl border bg-blue-50 p-6">

            <p class="text-sm text-gray-500">
                សរុបគោលដៅ
            </p>

            <h2
                id="totalTarget"
                class="text-3xl font-bold mt-2 text-blue-700">

                0%

            </h2>

        </div>

        <div class="rounded-xl border bg-green-50 p-6">

            <p class="text-sm text-gray-500">
                សរុបសម្រេច
            </p>

            <h2
                id="totalAchieved"
                class="text-3xl font-bold mt-2 text-green-700">

                0%

            </h2>

        </div>

        <div class="rounded-xl border bg-orange-50 p-6">

            <p class="text-sm text-gray-500">
                លទ្ធផលវាយតម្លៃ
            </p>

            <h2
                id="performanceScore"
                class="text-3xl font-bold mt-2 text-orange-600">

                --

            </h2>

        </div>

    </div>

</x-layout.page-card>
<x-layout.page-card
    title="សង្ខេបលទ្ធផល"
    icon="chart-column"
    class="mb-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Total Activity Score --}}
        <div class="rounded-xl border bg-green-50 p-6">

            <p class="text-sm text-gray-500">
                ពិន្ទុសរុបសកម្មភាព
            </p>

            <h2
                id="totalActivityScore"
                class="mt-2 text-3xl font-bold text-green-700">

                0.00 / 100

            </h2>

        </div>

        {{-- Work Performance Score --}}
        <div class="rounded-xl border bg-orange-50 p-6">

            <p class="text-sm text-gray-500">
                ពិន្ទុសមិទ្ធកម្មការងារ
            </p>

            <h2
                id="workPerformanceScore"
                class="mt-2 text-3xl font-bold text-orange-600">

                0 / 60

            </h2>

        </div>

    </div>

</x-layout.page-card>
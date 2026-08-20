<div class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b px-6 py-4">

        <div>

            <h3 class="text-lg font-semibold text-gray-800">
                ⭐ ឥរិយាបថ និងសមត្ថភាព
            </h3>

            <p class="text-sm text-gray-500">
                សង្ខេបពិន្ទុវាយតម្លៃ
            </p>

        </div>

        <span
            class="rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-700">

            {{ number_format($evaluation->behavior->total_score, 2) }}
            / 20 ពិន្ទុ

        </span>

    </div>

    @php

        $behavior = $evaluation->behavior;

        $discipline =
            $behavior->discipline +
            $behavior->responsibility +
            $behavior->professional_ethics;

        $professional =
            $behavior->work_performance +
            $behavior->self_development +
            $behavior->initiative_creativity;

        $leadership =
            $behavior->teamwork +
            $behavior->interpersonal_skill +
            $behavior->work_under_pressure +
            $behavior->leadership;

    @endphp

    <div class="p-6 space-y-6">

        {{-- Discipline --}}
        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-5 py-4">

            <div>

                <h4 class="font-medium text-gray-800">
                    ក. ឥរិយាបថ និងវិន័យ
                </h4>

                <p class="mt-1 text-sm text-gray-500">
                   • គោរពវិន័យ • ការទទួលខុសត្រូវ • គោរពឋានានុក្រមការងារ
                </p>

            </div>

            <span class="text-lg font-bold text-blue-600">

                {{ number_format($discipline, 2) }} / 6

            </span>

        </div>

        {{-- Professional --}}
        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-5 py-4">

            <div>

                <h4 class="font-medium text-gray-800">
                    ខ. សមត្ថភាពវិជ្ជាជីវៈ
                </h4>

                <p class="mt-1 text-sm text-gray-500">
                    • បំពេញការងារ • អភិវឌ្ឍខ្លួន • គំនិតផ្តួចផ្តើម
                </p>

            </div>

            <span class="text-lg font-bold text-blue-600">

                {{ number_format($professional, 2) }} / 6

            </span>

        </div>

        {{-- Leadership --}}
        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-5 py-4">

            <div>

                <h4 class="font-medium text-gray-800">
                    គ. ភាពជាអ្នកដឹកនាំ
                </h4>

                <p class="mt-1 text-sm text-gray-500">
                    • ការងារជាក្រុម • ទំនាក់ទំនង • សម្ពាធការងារ • ភាពជាអ្នកដឹកនាំ
                </p>

            </div>

            <span class="text-lg font-bold text-blue-600">

                {{ number_format($leadership, 2) }} / 8

            </span>

        </div>

    </div>

</div>
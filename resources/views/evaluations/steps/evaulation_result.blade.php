<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="text-center mb-8">

        <h2 class="text-3xl font-bold text-gray-800">
            លទ្ធផលបូកសរុបពិន្ទុការវាយតម្លៃ
        </h2>

        <p class="mt-2 text-gray-500">
            ប្រព័ន្ធបានគណនាពិន្ទុការវាយតម្លៃដោយស្វ័យប្រវត្តិ។
            សូមពិនិត្យលទ្ធផលខាងក្រោម មុនពេលបញ្ជូនការវាយតម្លៃ។
        </p>

    </div>

    {{-- Overall Score --}}
    <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg p-10 text-center">

        <p class="text-lg opacity-90">
            ពិន្ទុសរុប
        </p>

        <h1
            id="resultTotalScore"
            class="text-7xl font-bold mt-3">

            0

        </h1>

        <p class="text-2xl opacity-80">
            / 100
        </p>

        <span
            id="resultRatingBadge"
            class="inline-block mt-6 px-6 py-2 rounded-full bg-white/20 text-xl font-semibold">

            -

        </span>

    </div>

    {{-- Breakdown --}}
    <div class="mt-8 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b">

            <h3 class="text-lg font-semibold text-gray-800">
                ព័ត៌មានពិន្ទុលម្អិត
            </h3>

        </div>

        <div class="divide-y">

            <div class="flex justify-between items-center px-6 py-4">

                <span>សមិទ្ធផលការងារ</span>

                <span
                    id="resultWorkScore"
                    class="font-semibold">

                    0 / 60

                </span>

            </div>

            <div class="flex justify-between items-center px-6 py-4">

                <span>វត្តមានការងារ</span>

                <span
                    id="resultAttendanceScore"
                    class="font-semibold">

                    0 / 20

                </span>

            </div>

            <div class="flex justify-between items-center px-6 py-4">

                <span>ឥរិយាបថ និងសមត្ថភាព</span>

                <span
                    id="resultBehaviorScore"
                    class="font-semibold">

                    0 / 20

                </span>

            </div>

        </div>

    </div>

    {{-- Rating --}}
    <div
        id="resultRatingCard"
        class="mt-8 rounded-xl border border-green-200 bg-green-50 p-6">

        <h3 class="text-lg font-semibold text-green-700">

            និទ្ទេស

        </h3>

        <h2
            id="resultRatingTitle"
            class="mt-2 text-3xl font-bold text-green-700">

            -

        </h2>

        <p
            id="resultRatingDescription"
            class="mt-3 leading-7 text-gray-700">

        </p>

    </div>

</div>
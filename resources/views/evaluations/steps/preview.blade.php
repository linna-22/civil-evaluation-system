<!-- Page Header -->
<div class="mb-8">

    <h2 class="text-3xl font-bold text-gray-800">
        សង្ខេបលទ្ធផលវាយតម្លៃ
    </h2>

    <p class="mt-2 text-gray-500">
        សូមពិនិត្យព័ត៌មានទាំងអស់ឱ្យបានត្រឹមត្រូវ មុនពេលបញ្ជូនការវាយតម្លៃ។
    </p>

</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">

    <div class="px-6 py-4 border-b">

        <h3 class="text-lg font-semibold text-gray-800">
            👤 ព័ត៌មានមន្ត្រីរាជការ
        </h3>

    </div>

    <div class="p-6">

        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

            <div>
                <p class="text-sm text-gray-500">ឈ្មោះ</p>
                <p class="font-semibold">
                    {{ $employee->khmer_name ?? 'ឈ្មោះមន្ត្រី' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">ភេទ</p>
                <p class="font-semibold">
                    {{ $employee->gender ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">មុខតំណែង</p>
                <p class="font-semibold">
                    {{ $employee->position->position_name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">អង្គភាព</p>
                <p class="font-semibold">
                    {{ $employee->organization->organization_name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">នាយកដ្ឋាន</p>
                <p class="font-semibold">
                    {{ $employee->department->department_name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">ខែវាយតម្លៃ</p>
                <p class="font-semibold">
                    កក្កដា ២០២៦
                </p>
            </div>

        </div>

    </div>

</div>
<!-- Work Performance -->
<div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm">

    <div class="flex items-center justify-between px-6 py-4 border-b">

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

            56 / 60 ពិន្ទុ

        </span>

    </div>

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

                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">
                        ភាគរយ
                    </th>

                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">
                        ពិន្ទុ
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                <tr>

                    <td class="px-6 py-4">១</td>

                    <td class="px-6 py-4">
                        សកម្មភាពការងារទី១
                    </td>

                    <td class="px-6 py-4 text-center">
                        100%
                    </td>

                    <td class="px-6 py-4 text-center font-semibold">
                        10
                    </td>

                </tr>

                <tr>

                    <td class="px-6 py-4">២</td>

                    <td class="px-6 py-4">
                        សកម្មភាពការងារទី២
                    </td>

                    <td class="px-6 py-4 text-center">
                        95%
                    </td>

                    <td class="px-6 py-4 text-center font-semibold">
                        9
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>
<!-- Attendance -->
<div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm">

    <div class="flex items-center justify-between px-6 py-4 border-b">

        <div>

            <h3 class="text-lg font-semibold text-gray-800">
                📅 វត្តមានការងារ
            </h3>

            <p class="text-sm text-gray-500">
                សង្ខេបវត្តមានប្រចាំខែ
            </p>

        </div>

        <span class="rounded-full bg-green-100 px-4 py-2 font-semibold text-green-700">

            19 / 20 ពិន្ទុ

        </span>

    </div>

    <div class="grid md:grid-cols-4 gap-5 p-6">

        <div class="rounded-lg bg-gray-50 p-4 text-center">
            <p class="text-sm text-gray-500">ច្បាប់ឈប់សម្រាក</p>
            <h4 class="mt-2 text-2xl font-bold">១</h4>
            <p class="text-xs text-gray-500">ថ្ងៃ</p>
        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">
            <p class="text-sm text-gray-500">អវត្តមាន</p>
            <h4 class="mt-2 text-2xl font-bold">០</h4>
            <p class="text-xs text-gray-500">ថ្ងៃ</p>
        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">
            <p class="text-sm text-gray-500">មកយឺត</p>
            <h4 class="mt-2 text-2xl font-bold">១</h4>
            <p class="text-xs text-gray-500">ម៉ោង</p>
        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">
            <p class="text-sm text-gray-500">ចេញមុន</p>
            <h4 class="mt-2 text-2xl font-bold">០</h4>
            <p class="text-xs text-gray-500">ម៉ោង</p>
        </div>

    </div>

</div>
<!-- Behavior -->
<div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm">

    <div class="flex items-center justify-between px-6 py-4 border-b">

        <div>

            <h3 class="text-lg font-semibold text-gray-800">
                ⭐ ឥរិយាបថ និងសមត្ថភាព
            </h3>

            <p class="text-sm text-gray-500">
                សង្ខេបពិន្ទុវាយតម្លៃ
            </p>

        </div>

        <span class="rounded-full bg-yellow-100 px-4 py-2 font-semibold text-yellow-700">

            18 / 20 ពិន្ទុ

        </span>

    </div>

    <div class="p-6 space-y-5">

        <div class="flex justify-between items-center">

            <span>ឥរិយាបថ និងវិន័យ</span>

            <span class="font-semibold">6 / 6</span>

        </div>

        <div class="flex justify-between items-center">

            <span>សមត្ថភាពវិជ្ជាជីវៈ</span>

            <span class="font-semibold">5 / 6</span>

        </div>

        <div class="flex justify-between items-center">

            <span>ភាពជាអ្នកដឹកនាំ</span>

            <span class="font-semibold">7 / 8</span>

        </div>

    </div>

</div>
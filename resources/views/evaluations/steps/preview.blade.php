<!-- Page Header -->
<div class="mb-8">

    <h2 class="text-3xl font-bold text-gray-800">
        សង្ខេបលទ្ធផលវាយតម្លៃ
    </h2>

    <p class="mt-2 text-gray-500">
        សូមពិនិត្យព័ត៌មានទាំងអស់ឱ្យបានត្រឹមត្រូវ មុនពេលបញ្ជូនការវាយតម្លៃ។
    </p>

</div>

<!-- Employee Information -->
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
                <p id="previewEmployeeName" class="font-semibold"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">ភេទ</p>
                <p id="previewGender" class="font-semibold"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">មុខតំណែង</p>
                <p id="previewPosition" class="font-semibold"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">អង្គភាព</p>
                <p id="previewOrganization" class="font-semibold"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">នាយកដ្ឋាន</p>
                <p id="previewDepartment" class="font-semibold"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">វាយតម្លៃសម្រាប់</p>
                <p id="previewEvaluationMonth" class="font-semibold"></p>
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
            id="previewWorkPerformanceScore"
            class="inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

            0 / 60 ពិន្ទុ

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

            <tbody
                id="previewPerformanceTable"
                class="divide-y divide-gray-200">

            </tbody>

        </table>

    </div>

</div>

<!-- Attendance -->
{{-- <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm">

    <div class="flex items-center justify-between px-6 py-4 border-b">

        <div>

            <h3 class="text-lg font-semibold text-gray-800">
                📅 វត្តមានការងារ
            </h3>

            <p class="text-sm text-gray-500">
                សង្ខេបវត្តមានប្រចាំខែ
            </p>

        </div>

        <span
            id="previewAttendanceScore"
            class="rounded-full bg-green-100 px-4 py-2 font-semibold text-green-700">

            0 / 20 ពិន្ទុ

        </span>

    </div>

    <div class="grid md:grid-cols-4 gap-5 p-6">

        <div class="rounded-lg bg-gray-50 p-4 text-center">

            <p class="text-sm text-gray-500">
                ច្បាប់ឈប់សម្រាក
            </p>

            <h4
                id="previewApprovedLeave"
                class="mt-2 text-2xl font-bold">

                0

            </h4>

            <p class="text-xs text-gray-500">
                ថ្ងៃ
            </p>

        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">

            <p class="text-sm text-gray-500">
                អវត្តមាន
            </p>

            <h4
                id="previewUnapprovedLeave"
                class="mt-2 text-2xl font-bold">

                0

            </h4>

            <p class="text-xs text-gray-500">
                ថ្ងៃ
            </p>

        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">

            <p class="text-sm text-gray-500">
                មកយឺត
            </p>

            <h4
                id="previewLateHours"
                class="mt-2 text-2xl font-bold">

                0

            </h4>

            <p class="text-xs text-gray-500">
                ម៉ោង
            </p>

        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">

            <p class="text-sm text-gray-500">
                ចេញមុន
            </p>

            <h4
                id="previewLeaveEarlyHours"
                class="mt-2 text-2xl font-bold">

                0

            </h4>

            <p class="text-xs text-gray-500">
                ម៉ោង
            </p>

        </div>

    </div>

</div> --}}
<div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b">

        <div>

            <h3 class="text-lg font-semibold text-gray-800">
                📅 វត្តមានការងារ
            </h3>

            <p class="text-sm text-gray-500">
                សង្ខេបវត្តមានប្រចាំខែ
            </p>

        </div>

        <span
            id="previewAttendanceScore"
            class="rounded-full bg-green-100 px-4 py-2 font-semibold text-green-700">

            0 / 20 ពិន្ទុ

        </span>

    </div>

    {{-- Perfect Attendance --}}
    <div
        id="perfectAttendanceMessage"
        class="hidden p-8 text-center">

        <div
            class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100">

            <span class="text-4xl">
                ✅
            </span>

        </div>

        <h3 class="mt-5 text-2xl font-bold text-green-700">
            វត្តមានល្អឥតខ្ចោះ
        </h3>

        <p class="mt-3 text-gray-600 leading-7">

            មិនមានការឈប់សម្រាកទេក្នុងខែវាយតម្លៃនេះ។

        </p>

    </div>

    {{-- Attendance Detail --}}
    <div
        id="attendanceDetails"
        class="grid md:grid-cols-4 gap-5 p-6">

        <div class="rounded-lg bg-gray-50 p-4 text-center">

            <p class="text-sm text-gray-500">
                ច្បាប់ឈប់សម្រាក
            </p>

            <h4
                id="previewApprovedLeave"
                class="mt-2 text-2xl font-bold">

                0

            </h4>

            <p class="text-xs text-gray-500">
                ថ្ងៃ
            </p>

        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">

            <p class="text-sm text-gray-500">
                អវត្តមាន
            </p>

            <h4
                id="previewUnapprovedLeave"
                class="mt-2 text-2xl font-bold">

                0

            </h4>

            <p class="text-xs text-gray-500">
                ថ្ងៃ
            </p>

        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">

            <p class="text-sm text-gray-500">
                មកយឺត
            </p>

            <h4
                id="previewLateHours"
                class="mt-2 text-2xl font-bold">

                0

            </h4>

            <p class="text-xs text-gray-500">
                ម៉ោង
            </p>

        </div>

        <div class="rounded-lg bg-gray-50 p-4 text-center">

            <p class="text-sm text-gray-500">
                ចេញមុន
            </p>

            <h4
                id="previewLeaveEarlyHours"
                class="mt-2 text-2xl font-bold">

                0

            </h4>

            <p class="text-xs text-gray-500">
                ម៉ោង
            </p>

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

        <span
            id="previewBehaviorScore"
            class="rounded-full bg-yellow-100 px-4 py-2 font-semibold text-yellow-700">

            0 / 20 ពិន្ទុ

        </span>

    </div>

    <div class="p-6 space-y-5">

        <div class="flex justify-between items-center">

            <span>ឥរិយាបថ និងវិន័យ</span>

            <span
                id="previewDisciplineScore"
                class="font-semibold">

                0 / 6

            </span>

        </div>

        <div class="flex justify-between items-center">

            <span>សមត្ថភាពវិជ្ជាជីវៈ</span>

            <span
                id="previewProfessionalScore"
                class="font-semibold">

                0 / 6

            </span>

        </div>

        <div class="flex justify-between items-center">

            <span>ភាពជាអ្នកដឹកនាំ</span>

            <span
                id="previewLeadershipScore"
                class="font-semibold">

                0 / 8

            </span>

        </div>

    </div>

</div>
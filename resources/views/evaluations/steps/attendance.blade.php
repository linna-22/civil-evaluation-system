<div class="space-y-8">

    <!-- Header -->
    {{-- <div>
        <h2 class="text-xl font-semibold text-gray-800">
            ជំហានទី២
        </h2>

        <p class="mt-2 text-gray-500">
            ការវាយតម្លៃវត្តមាន
        </p>
    </div> --}}

    <!-- Perfect Attendance -->
    <!-- Perfect Attendance -->
    <div id="attendanceCard" class="rounded-xl border border-green-200 bg-green-50 p-5 transition-all duration-300">

        <div class="flex items-start justify-between">

            <div class="flex-1">

                <h3 id="attendanceTitle" class="text-lg font-semibold text-green-700">

                    វត្តមានល្អឥតខ្ចោះ

                </h3>

                <p id="attendanceDescription" class="mt-2 text-sm text-green-600">

                    ខ្ញុំមិនមានការឈប់មានច្បាប់ ឈប់អត់ច្បាប់
                    មកយឺត ឬចេញមុន ក្នុងខែវាយតម្លៃនេះទេ។

                </p>

            </div>

            <!-- Toggle -->
            <label class="relative inline-flex cursor-pointer items-center">

                <input type="checkbox" id="perfectAttendance" class="peer sr-only" checked>

                <div
                    class="peer h-6 w-11 rounded-full bg-gray-300
                after:absolute after:left-[2px] after:top-[2px]
                after:h-5 after:w-5 after:rounded-full
                after:bg-white after:transition-all
                peer-checked:bg-green-600
                peer-checked:after:translate-x-full">

                </div>

            </label>

        </div>

    </div>

    <!-- Attendance Form -->
    <div id="attendanceForm" class="space-y-8 hidden">

        <!-- Leave Information -->
        <div>

            <h3 class="mb-5 border-b pb-2 text-lg font-semibold text-gray-700">
                ព័ត៌មានអំពីការឈប់
            </h3>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                <!-- Approved Leave -->
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                    <label class="mb-2 block font-medium text-gray-700">
                        ឈប់មានច្បាប់ (ថ្ងៃ)
                    </label>

                    <input type="number" min="0" step="0.5" name="approved_leave_days" value="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <p class="mt-2 text-xs text-gray-500">
                        បញ្ចូលចំនួនថ្ងៃឈប់មានច្បាប់សរុបក្នុងខែនេះ។
                    </p>

                </div>

                <!-- Unapproved Leave -->
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                    <label class="mb-2 block font-medium text-gray-700">
                        ឈប់អត់ច្បាប់ (ថ្ងៃ)
                    </label>

                    <input type="number" min="0" step="0.5" name="unapproved_leave_days" value="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <p class="mt-2 text-xs text-gray-500">
                        បញ្ចូលចំនួនថ្ងៃឈប់អត់ច្បាប់សរុបក្នុងខែនេះ។
                    </p>

                </div>

            </div>

        </div>

        <!-- Time Information -->
        <div>

            <h3 class="mb-5 border-b pb-2 text-lg font-semibold text-gray-700">
                ព័ត៌មានអំពីម៉ោង
            </h3>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                <!-- Late Arrival -->
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                    <label class="mb-2 block font-medium text-gray-700">
                        មកយឺត (ម៉ោង)
                    </label>

                    <input type="number" min="0" step="0.5" name="late_hours" value="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <p class="mt-2 text-xs text-gray-500">
                        បញ្ចូលចំនួនម៉ោងមកយឺតសរុបក្នុងខែនេះ។
                    </p>

                </div>

                <!-- Leave Early -->
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                    <label class="mb-2 block font-medium text-gray-700">
                        ចេញមុន (ម៉ោង)
                    </label>

                    <input type="number" min="0" step="0.5" name="leave_early_hours" value="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                    <p class="mt-2 text-xs text-gray-500">
                        បញ្ចូលចំនួនម៉ោងចេញមុនសរុបក្នុងខែនេះ។
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- Attendance Summary -->
    <div>

        <h3 class="mb-5 border-b pb-2 text-lg font-semibold text-gray-700">
            លទ្ធផលវត្តមាន
        </h3>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <!-- Attendance Percentage -->
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 text-center">

                <p class="text-sm font-medium text-blue-700">
                    ភាគរយវត្តមាន
                </p>

                <h2 id="attendancePercent" class="mt-3 text-4xl font-bold text-blue-600">

                    100%

                </h2>

            </div>

            <!-- Attendance Score -->
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 text-center">

                <p class="text-sm font-medium text-amber-700">
                    ពិន្ទុវត្តមាន
                </p>

                <h2 id="attendanceScore" class="mt-3 text-4xl font-bold text-amber-600">

                    20

                </h2>

            </div>

        </div>

    </div>

</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {

        const toggle = document.getElementById("perfectAttendance");

        const form = document.getElementById("attendanceForm");

        const card = document.getElementById("attendanceCard");

        const title = document.getElementById("attendanceTitle");

        const description = document.getElementById("attendanceDescription");

        function updateAttendanceUI() {

            if (toggle.checked) {

                form.classList.add("hidden");

                card.classList.remove(
                    "bg-amber-50",
                    "border-amber-300"
                );

                card.classList.add(
                    "bg-green-50",
                    "border-green-200"
                );

                title.classList.remove("text-amber-700");
                title.classList.add("text-green-700");

                description.classList.remove("text-amber-600");
                description.classList.add("text-green-600");

                title.textContent = "មិនមានអវត្តមាន";

                description.textContent =
                    "ខ្ញុំមិនមានការឈប់សម្រាកទេក្នុងខែវាយតម្លៃនេះ។";

            } else {

                form.classList.remove("hidden");

                card.classList.remove(
                    "bg-green-50",
                    "border-green-200"
                );

                card.classList.add(
                    "bg-amber-50",
                    "border-amber-300"
                );

                title.classList.remove("text-green-700");
                title.classList.add("text-amber-700");

                description.classList.remove("text-green-600");
                description.classList.add("text-amber-600");

                title.textContent = "សូមបំពេញព័ត៌មានវត្តមាន";

                description.textContent =
                    "សូមបំពេញព័ត៌មានការឈប់សម្រាកខាងក្រោម។";

            }

        }

        updateAttendanceUI();

        toggle.addEventListener("change", updateAttendanceUI);

    });
</script>

import { refreshIcons } from "../utils/lucide";
document.addEventListener("DOMContentLoaded", () => {

    const addBtn = document.getElementById("addPerformanceBtn");
    const tbody = document.getElementById("performanceTableBody");

    addBtn.addEventListener("click", () => {

        const rowCount = tbody.querySelectorAll("tr").length + 1;

        const row = document.createElement("tr");

        row.innerHTML = `
            <td class="border text-center row-number font-medium">
                ${rowCount}
            </td>

            <!-- Activity -->
            <td class="border p-2">
                <textarea
                    name="activity[]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសកម្មភាព..."></textarea>
            </td>

            <!-- Performance Indicator -->
            <td class="border p-2">
                <textarea
                    name="indicator[]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសូចនាករសមិទ្ធកម្ម..."></textarea>
            </td>

            <!-- Achievement Percent -->
            <td class="border p-2">
                <input
                    type="number"
                    name="achievement_percent[]"
                    min="0"
                    max="100"
                    class="w-full rounded-lg text-center outline-none focus:outline-none focus:ring-0"
                    placeholder="0">
            </td>

            <!-- Score (Auto) -->
            <td class="border p-2">
                <input
                    type="text"
                    name="score[]"
                    value="0"
                    readonly
                    class="w-full rounded-lg bg-gray-100 text-center border-0 outline-none">
            </td>

            <!-- Rating (Auto) -->
            <td class="border p-2">
                <input
                    type="text"
                    name="rating[]"
                    readonly
                    class="w-full rounded-lg bg-gray-100 text-center border-0 outline-none"
                    placeholder="-">
            </td>

            <!-- Action -->
            <td class="border text-center">
                <button
                    type="button"
                    class="delete-row text-red-600 hover:text-red-700">

                    <i data-lucide="trash-2" class="w-5 h-5 mx-auto"></i>

                </button>
            </td>
        `;

        tbody.appendChild(row);

        refreshIcons();
    });
    // Delete row
    tbody.addEventListener("click", (e) => {

        const deleteBtn = e.target.closest(".delete-row");

        if (!deleteBtn) return;

        deleteBtn.closest("tr").remove();

        // Update row numbers
        tbody.querySelectorAll("tr").forEach((row, index) => {
            const numberCell = row.querySelector(".row-number");

            if (numberCell) {
                numberCell.textContent = index + 1;
            }
        });

    });

});
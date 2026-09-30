import Swal from "sweetalert2";
import { refreshIcons } from "../../utils/lucide";

document.addEventListener("DOMContentLoaded", () => {
    const tbody = document.getElementById("performanceTableBody");
    const addPerformanceBtn = document.getElementById("addPerformanceBtn");

    if (!tbody) {
        return;
    }

    const MAX_ROWS = 5;

    // =====================================================
    // Calculate Row Score
    // Same rule used by Work Performance Create.
    // =====================================================

    function calculateRowScore(percent) {
        const rowCount = tbody.querySelectorAll("tr").length;

        if (rowCount === 0) {
            return 0;
        }

        const achievement = Number(percent) || 0;
        const weight = 100 / rowCount;

        return Number(((achievement * weight) / 100).toFixed(2));
    }

    // =====================================================
    // Recalculate All Row Scores
    // =====================================================

    function recalculateAllRowScores() {
        const rows = tbody.querySelectorAll("tr");

        rows.forEach((row) => {
            const achievementInput = row.querySelector(
                'input[name*="[achievement_percent]"]'
            );
            const scoreInput = row.querySelector(
                'input[name*="[score]"]'
            );

            if (!achievementInput || !scoreInput) {
                return;
            }

            scoreInput.value = calculateRowScore(
                achievementInput.value
            );
        });
    }

    // =====================================================
    // Create New Row
    // =====================================================

    function createRow(index, data = {}) {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td class="border text-center row-number font-medium">
                ${index + 1}
            </td>

            <td class="border p-2">
                <textarea
                    name="performances[${index}][activity]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសកម្មភាព..."
                >${data.activity ?? ""}</textarea>
            </td>

            <td class="border p-2">
                <textarea
                    name="performances[${index}][indicator]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសូចនាករសមិទ្ធកម្ម..."
                >${data.indicator ?? ""}</textarea>
            </td>

            <td class="border p-2">
                <input
                    type="number"
                    name="performances[${index}][achievement_percent]"
                    min="0"
                    max="100"
                    step="0.01"
                    value="${data.achievement_percent ?? ""}"
                    class="w-full rounded-lg text-center outline-none focus:outline-none focus:ring-0"
                    placeholder="0"
                >
            </td>

            <td class="border p-2">
                <input
                    type="text"
                    name="performances[${index}][score]"
                    value="0"
                    readonly
                    data-score
                    class="w-full rounded-lg bg-gray-100 text-center border-0"
                >
            </td>

            <td class="border text-center">
                <button
                    type="button"
                    class="delete-row text-red-600 hover:text-red-700 cursor-pointer"
                    title="លុប"
                >
                    <i data-lucide="trash-2" class="w-5 h-5 mx-auto"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);
        refreshIcons();
    }

    // =====================================================
    // Add Row
    // =====================================================

    if (addPerformanceBtn) {
        addPerformanceBtn.addEventListener("click", () => {
            const rowCount = tbody.querySelectorAll("tr").length;

            if (rowCount >= MAX_ROWS) {
                Swal.fire({
                    icon: "warning",
                    title: "មិនអាចបន្ថែមបានទេ",
                    text: "សកម្មភាពអាចមាន៥ជាអតិបរមា។",
                    confirmButtonText: "យល់ព្រម",
                    confirmButtonColor: "#2563eb",
                });

                return;
            }

            createRow(rowCount);
            reIndexRows();
            recalculateAllRowScores();
        });
    }

    // =====================================================
    // Achievement Input
    // =====================================================

    tbody.addEventListener("input", (event) => {
        if (!event.target.name.includes("achievement_percent")) {
            return;
        }

        let percent = Number(event.target.value);

        if (percent < 0) {
            percent = 0;
        }

        if (percent > 100) {
            percent = 100;
        }

        event.target.value = percent;

        recalculateAllRowScores();
    });

    // =====================================================
    // Delete Row
    // =====================================================

    tbody.addEventListener("click", (event) => {
        const deleteButton = event.target.closest(".delete-row");

        if (!deleteButton) {
            return;
        }

        const row = deleteButton.closest("tr");

        if (!row) {
            return;
        }

        row.remove();

        reIndexRows();
        recalculateAllRowScores();
    });

    // =====================================================
    // Re-index Rows
    // =====================================================

    function reIndexRows() {
        tbody.querySelectorAll("tr").forEach((row, index) => {
            const rowNumber = row.querySelector(".row-number");
            const activity = row.querySelector(
                'textarea[name*="[activity]"]'
            );
            const indicator = row.querySelector(
                'textarea[name*="[indicator]"]'
            );
            const achievement = row.querySelector(
                'input[name*="[achievement_percent]"]'
            );
            const score = row.querySelector(
                'input[name*="[score]"]'
            );
            const idInput = row.querySelector(
                'input[name*="[work_performance_id]"]'
            );

            if (rowNumber) {
                rowNumber.textContent = index + 1;
            }

            if (activity) {
                activity.name = `performances[${index}][activity]`;
            }

            if (indicator) {
                indicator.name = `performances[${index}][indicator]`;
            }

            if (achievement) {
                achievement.name =
                    `performances[${index}][achievement_percent]`;
            }

            if (score) {
                score.name = `performances[${index}][score]`;
            }

            if (idInput) {
                idInput.name =
                    `performances[${index}][work_performance_id]`;
            }

            // Make sure every row, including newly added rows, has
            // its own delete button.
            if (!row.querySelector(".delete-row")) {
                const actionCell = row.querySelector("td:last-child");

                if (actionCell) {
                    actionCell.innerHTML = `
                        <button
                            type="button"
                            class="delete-row text-red-600 hover:text-red-700 cursor-pointer"
                            title="លុប"
                        >
                            <i data-lucide="trash-2" class="w-5 h-5 mx-auto"></i>
                        </button>
                    `;
                }
            }
        });

        refreshIcons();
    }

    // =====================================================
    // Initial Setup
    // =====================================================

    reIndexRows();
    recalculateAllRowScores();
});

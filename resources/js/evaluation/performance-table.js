import Swal from "sweetalert2";
import { refreshIcons } from "../utils/lucide";

document.addEventListener("DOMContentLoaded", () => {
   
    const addBtn = document.getElementById("addPerformanceBtn");
    const tbody = document.getElementById("performanceTableBody");
     if (!addBtn || !tbody) {
        return;
    }

    // ========================================
    // Add Row
    // ========================================

    addBtn.addEventListener("click", () => {

        const currentRows = tbody.querySelectorAll("tr").length;

        if (currentRows >= 5) {

            Swal.fire({
                icon: "warning",
                title: "មិនអាចបន្ថែមបានទេ",
                text: "សកម្មភាពអាចមាន៥ជាអតិបរមា។",
                confirmButtonColor: "#2563eb"
            });

            return;

        }

        const rowCount = currentRows + 1;
        const index = currentRows;

        const row = document.createElement("tr");

        row.innerHTML = `
            <td class="border text-center row-number font-medium">
                ${rowCount}
            </td>

            <!-- Activity -->
            <td class="border p-2">
                <textarea
                    name="performances[${index}][activity]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសកម្មភាព..."></textarea>
            </td>

            <!-- Indicator -->
            <td class="border p-2">
                <textarea
                    name="performances[${index}][indicator]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសូចនាករសមិទ្ធកម្ម..."></textarea>
            </td>

            <!-- Achievement -->
            <td class="border p-2">
                <input
                    type="number"
                    name="performances[${index}][achievement_percent]"
                    min="0"
                    max="100"
                    class="w-full rounded-lg text-center outline-none focus:outline-none focus:ring-0"
                    placeholder="0">
            </td>

            <!-- Score -->
            <td class="border p-2">
                <input
                    type="text"
                    name="performances[${index}][score]"
                    value="0"
                    readonly
                    class="w-full rounded-lg bg-gray-100 text-center border-0">
            </td>

            <!-- Action -->
            <td class="border text-center">

                <button
                    type="button"
                    class="delete-row text-red-600 hover:text-red-700 cursor-pointer">

                    <i data-lucide="trash-2" class="w-5 h-5 mx-auto"></i>

                </button>

            </td>
        `;

        tbody.appendChild(row);

        refreshIcons();

    });

    // ========================================
    // Delete Row
    // ========================================

    tbody.addEventListener("click", (e) => {

        const deleteBtn = e.target.closest(".delete-row");

        if (!deleteBtn) return;

        deleteBtn.closest("tr").remove();

        reIndexRows();

        updateSummary();

    });

    // ========================================
    // Auto Calculate Score
    // ========================================

    tbody.addEventListener("input", (e) => {

        if (!e.target.name.includes("achievement_percent")) {

            return;

        }

        let percent = Number(e.target.value);

        if (percent < 0) percent = 0;

        if (percent > 100) percent = 100;

        e.target.value = percent;

        const row = e.target.closest("tr");

        const score = calculateRowScore(percent);

        row.querySelector("input[name*='score']").value = score;

        updateSummary();

    });

});

// ========================================
// Re-index Rows
// ========================================

function reIndexRows() {

    const rows = document.querySelectorAll("#performanceTableBody tr");

    rows.forEach((row, index) => {

        row.querySelector(".row-number").textContent = index + 1;

        row.querySelector("textarea[name*='activity']")
            .name = `performances[${index}][activity]`;

        row.querySelector("textarea[name*='indicator']")
            .name = `performances[${index}][indicator]`;

        row.querySelector("input[name*='achievement_percent']")
            .name = `performances[${index}][achievement_percent]`;

        row.querySelector("input[name*='score']")
            .name = `performances[${index}][score]`;

    });

}

// ========================================
// Row Score
// ========================================

function calculateRowScore(percent) {

    percent = Number(percent);

    if (isNaN(percent)) {

        return 0;

    }

    return Number((percent * 20 / 100).toFixed(2));

}

// ========================================
// Total Score
// ========================================

function calculateTotalScore() {

    let total = 0;

    document
        .querySelectorAll("input[name*='score']")
        .forEach(input => {

            total += Number(input.value) || 0;

        });

    return Number(total.toFixed(2));

}

// ========================================
// Work Performance Score
// ========================================

function calculateWorkPerformanceScore(total) {

    if (total > 0 && total <= 60) {

        return 0;

    }

    if (total > 60 && total <= 70) {

        return 15;

    }

    if (total > 70 && total <= 80) {

        return 30;

    }

    if (total > 80 && total <= 90) {

        return 45;

    }

    if (total > 90 && total <= 100) {

        return 60;

    }

    return 0;

}

// ========================================
// Update Summary
// ========================================

function updateSummary() {

    const total = calculateTotalScore();

    const workScore = calculateWorkPerformanceScore(total);

    document.getElementById("totalActivityScore").textContent =
        `${total.toFixed(2)} / 100`;

    document.getElementById("workPerformanceScore").textContent =
        `${workScore} / 60`;

}
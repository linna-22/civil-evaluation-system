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

        const index = currentRows;
        const rowNumber = currentRows + 1;

        const row = document.createElement("tr");

        row.innerHTML = `
            <td class="border text-center row-number font-medium">
                ${rowNumber}
            </td>

            <td class="border p-2">
                <textarea
                    name="performances[${index}][activity]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសកម្មភាព..."></textarea>
            </td>

            <td class="border p-2">
                <textarea
                    name="performances[${index}][indicator]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសូចនាករសមិទ្ធកម្ម..."></textarea>
            </td>

            <td class="border p-2">

                <input
                    type="number"
                    name="performances[${index}][achievement_percent]"
                    min="0"
                    max="100"
                    value="0"
                    class="achievement-input w-full rounded-lg text-center outline-none focus:outline-none focus:ring-0"
                    placeholder="0">

            </td>

            <td class="border p-2">

                <input
                    type="text"
                    name="performances[${index}][score]"
                    value="0"
                    readonly
                    data-score
                    class="score-input w-full rounded-lg bg-gray-100 text-center border-0">

            </td>

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

        updateScores();

    });


    // ========================================
    // Delete Row
    // ========================================

    tbody.addEventListener("click", (e) => {

        const deleteBtn = e.target.closest(".delete-row");

        if (!deleteBtn) {
            return;
        }

        deleteBtn.closest("tr").remove();

        reIndexRows();

        updateScores();

    });


    // ========================================
    // Achievement Input
    // ========================================

    tbody.addEventListener("input", (e) => {

        if (!e.target.name?.includes("achievement_percent")) {
            return;
        }

        let percent = Number(e.target.value);

        if (percent < 0) {
            percent = 0;
        }

        if (percent > 100) {
            percent = 100;
        }

        e.target.value = percent;

        updateScores();

    });

});


// ========================================
// Re-index Rows
// ========================================

function reIndexRows() {

    const rows = document.querySelectorAll(
        "#performanceTableBody tr"
    );

    rows.forEach((row, index) => {

        const rowNumber = row.querySelector(".row-number");

        const activity = row.querySelector(
            "textarea[name*='activity']"
        );

        const indicator = row.querySelector(
            "textarea[name*='indicator']"
        );

        const achievement = row.querySelector(
            "input[name*='achievement_percent']"
        );

        const score = row.querySelector(
            "input[name*='score']"
        );


        if (rowNumber) {
            rowNumber.textContent = index + 1;
        }

        if (activity) {
            activity.name =
                `performances[${index}][activity]`;
        }

        if (indicator) {
            indicator.name =
                `performances[${index}][indicator]`;
        }

        if (achievement) {
            achievement.name =
                `performances[${index}][achievement_percent]`;
        }

        if (score) {
            score.name =
                `performances[${index}][score]`;
        }

    });

}


// ========================================
// Calculate Activity Weight
// ========================================

function calculateActivityWeight(totalRows) {

    if (totalRows === 0) {
        return 0;
    }

    return 100 / totalRows;

}


// ========================================
// Calculate Row Contribution
// ========================================

function calculateRowScore(achievementPercent, activityWeight) {

    const achievement = Number(achievementPercent) || 0;

    const score =
        activityWeight * (achievement / 100);

    return Number(score.toFixed(2));

}


// ========================================
// Calculate Total Achievement
// ========================================

function calculateTotalScore() {

    const rows = document.querySelectorAll(
        "#performanceTableBody tr"
    );

    const totalRows = rows.length;

    if (totalRows === 0) {
        return 0;
    }

    const activityWeight =
        calculateActivityWeight(totalRows);

    let total = 0;

    rows.forEach(row => {

        const achievementInput =
            row.querySelector(
                "input[name*='achievement_percent']"
            );

        const scoreInput =
            row.querySelector(
                "input[name*='score']"
            );

        const achievement =
            Number(achievementInput?.value) || 0;

        const rowScore =
            calculateRowScore(
                achievement,
                activityWeight
            );

        if (scoreInput) {
            scoreInput.value = rowScore.toFixed(2);
        }

        total += rowScore;

    });

    return Number(total.toFixed(2));

}


// ========================================
// Final Work Performance Score
// ========================================

function calculateWorkPerformanceScore(total) {

    if (total < 60) {

        return 0;

    }

    if (total < 70) {

        return 15;

    }

    if (total < 80) {

        return 30;

    }

    if (total < 90) {

        return 45;

    }

    return 60;

}


// ========================================
// Update Scores
// ========================================

function updateScores() {

    const totalAchievement =
        calculateTotalScore();

    const workPerformanceScore =
        calculateWorkPerformanceScore(
            totalAchievement
        );


    // Total achievement
    const totalScoreElement =
        document.getElementById("totalScore");

    if (totalScoreElement) {

        totalScoreElement.textContent =
            totalAchievement.toFixed(2);

    }


    // Optional final score element
    const workPerformanceElement =
        document.getElementById(
            "workPerformanceScore"
        );

    if (workPerformanceElement) {

        workPerformanceElement.textContent =
            workPerformanceScore;

    }

}
const performanceForm = document.getElementById("performanceForm");

if (performanceForm) {

    performanceForm.addEventListener("submit", async (e) => {

        e.preventDefault();

        const submitButton = document.getElementById("nextUserBtn");

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add("opacity-50", "cursor-not-allowed");
        }

        try {

            const formData = new FormData(performanceForm);

            const response = await fetch(
                performanceForm.action,
                {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json",
                    }
                }
            );

            const result = await response.json();

            if (!response.ok) {
                throw new Error(
                    result.message || "មានបញ្ហាក្នុងការរក្សាទុកទិន្នន័យ។"
                );
            }

            if (result.success && result.redirect) {

                window.location.href = result.redirect;

                return;
            }

            throw new Error(
                result.message || "មិនអាចបន្តបានទេ។"
            );

        } catch (error) {

            console.error(error);

            Swal.fire({
                icon: "error",
                title: "មានបញ្ហា",
                text: error.message,
                confirmButtonColor: "#2563eb"
            });

            if (submitButton) {
                submitButton.disabled = false;
                submitButton.classList.remove(
                    "opacity-50",
                    "cursor-not-allowed"
                );
            }
        }

    });

}
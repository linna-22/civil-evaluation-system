import Swal from "sweetalert2";
import { refreshIcons } from "../../utils/lucide";

document.addEventListener("DOMContentLoaded", () => {

    const tbody =
        document.getElementById("performanceTableBody");

    const addPerformanceBtn =
        document.getElementById("addPerformanceBtn");


    if (!tbody) {
        return;
    }


    // =====================================================
    // Calculate Row Score
    // =====================================================

    function calculateRowScore(percent) {

        const rowCount =
            tbody.querySelectorAll("tr").length;


        if (rowCount === 0) {
            return 0;
        }


        percent =
            Number(percent) || 0;


        // Each row gets an equal percentage weight
        const weight =
            100 / rowCount;


        return Number(
            (
                percent * weight / 100
            ).toFixed(2)
        );

    }


    // =====================================================
    // Recalculate All Row Scores
    // =====================================================

    function recalculateAllRowScores() {

        const rows =
            tbody.querySelectorAll("tr");


        rows.forEach(row => {

            const achievementInput =
                row.querySelector(
                    'input[name*="[achievement_percent]"]'
                );

            const scoreInput =
                row.querySelector(
                    'input[name*="[score]"]'
                );


            if (!achievementInput || !scoreInput) {
                return;
            }


            const percent =
                Number(
                    achievementInput.value
                ) || 0;


            scoreInput.value =
                calculateRowScore(percent);

        });

    }


    // =====================================================
    // Add Performance Row
    // =====================================================

    function addPerformanceRow(
        data = {},
        canDelete = true
    ) {

        const index =
            tbody.querySelectorAll("tr").length;


        const achievement =
            data.achievement_percent ?? "";


        const row =
            document.createElement("tr");


        row.innerHTML = `

            <td class="border text-center row-number font-medium">
                ${index + 1}
            </td>


            <!-- Activity -->

            <td class="border p-2">

                <textarea
                    name="performances[${index}][activity]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសកម្មភាព..."
                >${data.activity ?? ""}</textarea>

            </td>


            <!-- Indicator -->

            <td class="border p-2">

                <textarea
                    name="performances[${index}][indicator]"
                    rows="2"
                    class="w-full rounded-lg resize-none outline-none focus:outline-none focus:ring-0"
                    placeholder="បញ្ចូលសូចនាករសមិទ្ធកម្ម..."
                >${data.indicator ?? ""}</textarea>

            </td>


            <!-- Achievement -->

            <td class="border p-2">

                <input
                    type="number"
                    name="performances[${index}][achievement_percent]"
                    min="0"
                    max="100"
                    value="${achievement}"
                    class="w-full rounded-lg text-center outline-none focus:outline-none focus:ring-0"
                    placeholder="0"
                >

            </td>


            <!-- Score -->

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


            <!-- Action -->

            <td class="border text-center">

                ${
                    canDelete
                        ? `
                            <button
                                type="button"
                                class="delete-row text-red-600 hover:text-red-700 cursor-pointer">

                                <i
                                    data-lucide="trash-2"
                                    class="w-5 h-5 mx-auto">
                                </i>

                            </button>
                        `
                        : `
                            <span class="text-gray-300">
                                —
                            </span>
                        `
                }

            </td>

        `;


        tbody.appendChild(row);


        // Recalculate because number of rows changed
        recalculateAllRowScores();


        refreshIcons();

    }


    // =====================================================
    // Add Button
    // =====================================================

    if (addPerformanceBtn) {

        addPerformanceBtn.addEventListener(
            "click",
            () => {

                const rowCount =
                    tbody.querySelectorAll("tr").length;


                if (rowCount >= 5) {

                    Swal.fire({

                        icon: "warning",

                        title: "មិនអាចបន្ថែមបានទេ",

                        text:
                            "សកម្មភាពអាចមាន៥ជាអតិបរមា។",

                        confirmButtonColor:
                            "#2563eb"

                    });

                    return;

                }


                addPerformanceRow({}, true);

            }
        );

    }


    // =====================================================
    // Achievement Input
    // =====================================================

    tbody.addEventListener(
        "input",
        event => {

            if (
                !event.target.name.includes(
                    "achievement_percent"
                )
            ) {
                return;
            }


            let percent =
                Number(event.target.value);


            if (percent < 0) {
                percent = 0;
            }


            if (percent > 100) {
                percent = 100;
            }


            event.target.value =
                percent;


            // Recalculate all rows
            // because the total depends on row count

            recalculateAllRowScores();

        }
    );


    // =====================================================
    // Delete Row
    // =====================================================

    tbody.addEventListener(
        "click",
        event => {

            const deleteButton =
                event.target.closest(
                    ".delete-row"
                );


            if (!deleteButton) {
                return;
            }


            deleteButton
                .closest("tr")
                .remove();


            reIndexRows();


            // Recalculate because
            // number of rows changed

            recalculateAllRowScores();

        }
    );


    // =====================================================
    // Re-index Rows
    // =====================================================

    function reIndexRows() {

        tbody
            .querySelectorAll("tr")
            .forEach((row, index) => {

                row.querySelector(
                    ".row-number"
                ).textContent =
                    index + 1;


                row.querySelector(
                    "textarea[name*='[activity]']"
                ).name =
                    `performances[${index}][activity]`;


                row.querySelector(
                    "textarea[name*='[indicator]']"
                ).name =
                    `performances[${index}][indicator]`;


                row.querySelector(
                    "input[name*='[achievement_percent]']"
                ).name =
                    `performances[${index}][achievement_percent]`;


                row.querySelector(
                    "input[name*='[score]']"
                ).name =
                    `performances[${index}][score]`;

            });

    }


    // =====================================================
    // Get Table Data
    // =====================================================

    function getData() {

        const performances = [];


        tbody
            .querySelectorAll("tr")
            .forEach(row => {

                const activity =
                    row.querySelector(
                        'textarea[name*="[activity]"]'
                    )?.value || "";


                const indicator =
                    row.querySelector(
                        'textarea[name*="[indicator]"]'
                    )?.value || "";


                const achievement =
                    row.querySelector(
                        'input[name*="[achievement_percent]"]'
                    )?.value || "";


                if (
                    activity.trim() === "" &&
                    indicator.trim() === "" &&
                    achievement === ""
                ) {
                    return;
                }


                performances.push({

                    activity,

                    indicator,

                    achievement_percent:
                        achievement === ""
                            ? null
                            : Number(achievement)

                });

            });


        return performances;

    }


    // =====================================================
    // Load Table Data
    // =====================================================

    function loadData(performances = []) {

        tbody.innerHTML = "";


        // No saved data
        // Create one default row

        if (!performances.length) {

            addPerformanceRow({}, false);

            return;

        }


        // Load saved rows

        performances.forEach(
            (performance, index) => {

                addPerformanceRow(
                    performance,
                    index !== 0
                );

            }
        );


        // Make sure all loaded rows
        // have the correct weight

        recalculateAllRowScores();

    }


    // =====================================================
    // Public Table API
    // =====================================================

    window.workPerformanceTable = {

        addRow:
            addPerformanceRow,

        getData,

        loadData,

        recalculateScores:
            recalculateAllRowScores

    };


    // =====================================================
    // Initial Default Row
    // =====================================================

    addPerformanceRow({}, false);

});
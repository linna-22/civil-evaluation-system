export function renderDepartmentResultRow(result, no) {

     console.log("DEPARTMENT RESULT:", result);
    console.log("OVERTIME:", result.overtime_hours);
    const user =
        result.evaluation_period_user?.user;

    const hasRemark =
        result.remarks && result.remarks.trim() !== "";

    return `
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

            <td class="px-6 py-4">
                ${no}
            </td>

            <td class="px-6 py-4">
                ${user?.name_kh ?? "មិនមាន"}
            </td>

            <td class="px-6 py-4">
                ${user?.gender === 'male'
                    ? 'ប្រុស'
                    : user?.gender === 'female'
                        ? 'ស្រី'
                        : 'មិនមាន'
                }
            </td>

            <td class="px-6 py-4 text-center">
                ${user?.position ?? "មិនមាន"}
            </td>
            <td class="px-6 py-4 text-center">
                ${result.work_performance_score ?? "0.00"}
            </td>

            <td class="px-6 py-4 text-center">
                <div class="flex flex-col items-center gap-1">
                    <span class="font-medium text-gray-800">
                        ${result.attendance_score ?? "0.00"}
                    </span>

                    <span class="text-xs text-gray-500">
                        ម៉ោងលើស: ${
                            Number(result.overtime_hours ?? 0)
                                .toLocaleString("en-US", {
                                    maximumFractionDigits: 2,
                                })
                        } ម៉ោង
                    </span>
                </div>
            </td>

            <td class="px-6 py-4 text-center">
                ${result.behavior_score ?? "0.00"}
            </td>

            <td class="px-6 py-4 text-center font-bold">
                ${result.total_score ?? "0.00"}
            </td>
            <td class="px-6 py-4">
                ${hasRemark
                    ? `<span class="text-sm text-gray-700">${result.remarks}</span>`
                    : ""
                }
            </td>
            <td class="px-6 py-5 text-center">

                <div class="relative inline-block text-left">
                    <button
                        type="button"
                        class="btn-department-result-action
                               inline-flex items-center justify-center
                               w-10 h-10
                               rounded-full
                               border border-blue-200
                               bg-blue-50
                               text-blue-600
                               hover:bg-blue-100
                               hover:border-blue-300
                               transition"
                        data-user-id="${user?.user_id ?? ""}"
                        aria-expanded="false"
                        aria-label="សកម្មភាព"
                    >
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div
                        class="department-result-action-menu
                               hidden
                               absolute right-0 z-50 mt-2
                               w-48
                               rounded-xl
                               border border-gray-200
                               bg-white
                               shadow-lg
                               overflow-hidden"
                    >
                        <a
                            href="/report/${window.departmentEvaluationPeriodId}/user/${user?.user_id}/print"
                            target="_blank"
                            class="flex items-center gap-3
                                   px-4 py-3
                                   text-sm text-gray-700
                                   hover:bg-gray-50
                                   transition"
                        >
                            <i
                                data-lucide="file-down"
                                class="w-4 h-4 text-red-600"
                            ></i>

                            <span>ទាញយក PDF</span>
                        </a>
                        <a
                            href="/report/${window.departmentEvaluationPeriodId}/user/${user?.user_id}/word"
                            class="flex items-center gap-3
                                   px-4 py-3
                                   text-sm text-gray-700
                                   hover:bg-gray-50
                                   transition"
                        >
                            <i
                                data-lucide="file-text"
                                class="w-4 h-4 text-blue-600"
                            ></i>

                            <span>ទាញយក Word</span>
                        </a>

                    </div>

                </div>

            </td>

        </tr>
    `;
}
// ==========================================================
// Department Result Action Dropdown
// ==========================================================

document.addEventListener("click", function (event) {

    const actionButton = event.target.closest(
        ".btn-department-result-action"
    );

    // ------------------------------------------------------
    // Clicked an Action button
    // ------------------------------------------------------
    if (actionButton) {

        const actionContainer = actionButton.closest(".relative");

        const menu = actionContainer?.querySelector(
            ".department-result-action-menu"
        );

        if (!menu) {
            return;
        }

        // Close all other dropdowns first
        document
            .querySelectorAll(".department-result-action-menu")
            .forEach((dropdown) => {

                if (dropdown !== menu) {
                    dropdown.classList.add("hidden");
                }
            });

        // Toggle current dropdown
        menu.classList.toggle("hidden");

        // Update aria state
        const isOpen = !menu.classList.contains("hidden");

        actionButton.setAttribute(
            "aria-expanded",
            isOpen ? "true" : "false"
        );

        return;
    }

    // ------------------------------------------------------
    // Clicked outside the dropdown
    // ------------------------------------------------------
    if (
        !event.target.closest(
            ".department-result-action-menu"
        )
    ) {

        document
            .querySelectorAll(".department-result-action-menu")
            .forEach((dropdown) => {
                dropdown.classList.add("hidden");
            });

        document
            .querySelectorAll(".btn-department-result-action")
            .forEach((button) => {
                button.setAttribute("aria-expanded", "false");
            });
    }
});
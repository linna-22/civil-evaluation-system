import DataTable from "../../components/data-table/DataTable";
import { refreshIcons } from "../../utils/lucide";
import Swal from "sweetalert2";

const body = document.querySelector("#evaluation-outcome-table-body");

if (body && window.evaluationOutcome) {
    const { periodId, type } = window.evaluationOutcome;

    const dataUrl = `/evaluation-results/${type}/${periodId}/data`;

    const escapeHtml = (value) => {
        const div = document.createElement("div");
        div.textContent = value ?? "";
        return div.innerHTML;
    };

    const number = (value) => {
        const numericValue = Number(value ?? 0);

        if (!Number.isFinite(numericValue)) {
            return "0";
        }

        if (Number.isInteger(numericValue)) {
            return String(numericValue);
        }

        return numericValue
            .toFixed(2)
            .replace(/0+$/, "")
            .replace(/\.$/, "");
    };

    const renderRemarkButton = (result, user) => {
        if (type !== "overall") {
            return "";
        }

        const remark = String(result.remarks ?? "").trim();
        const hasRemark = remark !== "";

        return `
            <td class="px-6 py-4 whitespace-nowrap">
                <button
                    type="button"
                    class="btn-outcome-remark px-3 py-2 rounded-lg text-sm font-medium transition
                        ${hasRemark
                            ? "bg-blue-50 text-blue-600 hover:bg-blue-100"
                            : "bg-gray-50 text-gray-600 hover:bg-gray-100"
                        }"
                    data-id="${escapeHtml(result.evaluation_summary_id)}"
                    data-name-kh="${escapeHtml(user?.name_kh ?? "មិនមាន")}"\n                    data-remark="${escapeHtml(remark)}"
                >
                    ${hasRemark ? "✎ កែប្រែ" : "+ បន្ថែម"}
                </button>
            </td>
        `;
    };

    const renderRow = (result, no) => {
        const user = result.evaluation_period_user?.user;
        const userId = user?.user_id ?? "";
        const name = user?.name_kh ?? "មិនមាន";
        const gender = user?.gender === "male"
            ? "ប្រុស"
            : user?.gender === "female"
                ? "ស្រី"
                : "—";

        let actionUrl = "#";
        let actionText = "ពិនិត្យ";
        let actionIcon = "eye";

        if (type === "work-performance") {
            actionUrl = `/department-evaluation-results/${periodId}/user/${userId}/work-performance/edit`;
            actionText = "កែប្រែ";
            actionIcon = "pencil";
        } else if (type === "attendance") {
            actionUrl = `/department-evaluation-results/${periodId}/user/${userId}/attendance/edit`;
            actionText = "កែប្រែ";
            actionIcon = "pencil";
        } else if (type === "behavior") {
            actionUrl = `/department-evaluation-results/${periodId}/user/${userId}/behavior/review`;
            actionText = "ពិនិត្យ/កែប្រែ";
            actionIcon = "pencil";
        } else if (type === "overall") {
            actionUrl = `/department-evaluation-results/${periodId}/user/${userId}/review`;
        }

        const score = type === "work-performance"
            ? `${number(result.work_performance_score)} / 60`
            : type === "attendance"
                ? `${number(result.attendance_score)} / 20`
                : type === "behavior"
                    ? `${number(result.behavior_score)} / 20`
                    : `${number(result.total_score)} / 100`;

        const overallScores = type === "overall"
            ? `
                <td class="px-6 py-4 text-center">${number(result.work_performance_score)} / 60</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex flex-col items-center gap-1">
                        <span class="font-medium text-gray-800">
                            ${number(result.attendance_score)} / 20
                        </span>
                        <span class="text-xs text-gray-500">
                            ម៉ោងលើស: ${Number(result.overtime_hours ?? 0).toLocaleString("en-US", {
                                maximumFractionDigits: 2,
                            })} ម៉ោង
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">${number(result.behavior_score)} / 20</td>
                <td class="px-6 py-4 text-center font-bold text-blue-700">${number(result.total_score)} / 100</td>
            `
            : `<td class="px-6 py-4 text-center font-bold text-blue-700">${score}</td>`;

        return `
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="px-6 py-4">${no}</td>
                <td class="px-6 py-4">${escapeHtml(user?.id_code ?? "—")}</td>
                <td class="px-6 py-4 font-semibold text-gray-800">${escapeHtml(name)}</td>
                <td class="px-6 py-4">${gender}</td>
                <td class="px-6 py-4">${escapeHtml(user?.position ?? "—")}</td>
                ${overallScores}
                ${renderRemarkButton(result, user)}
                <td class="px-6 py-4 text-center">
                    <a href="${actionUrl}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold">
                        <i data-lucide="${actionIcon}" class="w-4 h-4"></i>
                        ${actionText}
                    </a>
                </td>
            </tr>
        `;
    };

    const table = new DataTable({
        url: dataUrl,
        body: "#evaluation-outcome-table-body",
        pagination: "#evaluation-outcome-pagination",
        search: "#evaluation-outcome-search",
        perPage: "#evaluation-outcome-per-page",
        render: renderRow,
        onLoaded: () => refreshIcons(),
    });

    const officeSelect = document.querySelector("#evaluation-outcome-office");

    officeSelect?.addEventListener("change", (event) => {
        table.state.setFilter("office_id", event.target.value);
        table.load();
    });

    // ==========================================================
    // Overall Result Remarks
    // Reuses the same remarks modal already used by the old
    // department evaluation results page.
    // ==========================================================

    if (type === "overall") {
        const modal = document.querySelector("#remarks-modal");
        const backdrop = document.querySelector("#remarks-modal-backdrop");
        const closeButton = document.querySelector("#remarks-modal-close");
        const cancelButton = document.querySelector("#remarks-modal-cancel");
        const title = document.querySelector("#remarks-modal-title");
        const employeeName = document.querySelector("#remarks-modal-employee");
        const input = document.querySelector("#remarks-input");
        const summaryId = document.querySelector("#remarks-evaluation-summary-id");
        const selectedValue = document.querySelector("#remarks-selected-value");
        const manualInputWrapper = document.querySelector("#remarks-manual-input-wrapper");
        const remarkOptions = document.querySelectorAll(".remark-option");
        const saveButton = document.querySelector("#remarks-modal-save");

        const predefinedRemarks = [
            "ល្អ",
            "ល្អណាស់",
            "ល្អបង្គួរ",
            "មធ្យម",
            "ខ្សោយ",
        ];

        const selectRemarkOption = (value, { clearInput = true } = {}) => {
            remarkOptions.forEach((button) => {
                const selected = button.dataset.value === value;

                button.classList.toggle("border-blue-500", selected);
                button.classList.toggle("bg-blue-50", selected);
                button.classList.toggle("text-blue-600", selected);
                button.classList.toggle("border-gray-300", !selected);
                button.classList.toggle("text-gray-700", !selected);
            });

            selectedValue.value = value;

            if (value === "manual") {
                if (clearInput) {
                    input.value = "";
                }
                manualInputWrapper.classList.remove("hidden");
                input.focus();
            } else {
                manualInputWrapper.classList.add("hidden");
            }
        };

        const resetOptions = () => {
            remarkOptions.forEach((button) => {
                button.classList.remove(
                    "border-blue-500",
                    "bg-blue-50",
                    "text-blue-600"
                );
                button.classList.add("border-gray-300", "text-gray-700");
            });
        };

        const closeModal = () => {
            if (!modal) return;

            modal.classList.add("hidden");
            modal.setAttribute("aria-hidden", "true");
            input.value = "";
            summaryId.value = "";
            selectedValue.value = "";
            manualInputWrapper.classList.add("hidden");
            resetOptions();
        };

        document.addEventListener("click", (event) => {
            const button = event.target.closest(".btn-outcome-remark");

            if (!button || !modal) return;

            const remark = String(button.dataset.remark ?? "").trim();

            summaryId.value = button.dataset.id ?? "";
            employeeName.textContent = `មន្ត្រី: ${button.dataset.nameKh ?? "មិនមាន"}`;
            input.value = remark;
            title.textContent = remark ? "កែប្រែមូលវិចារណ៍" : "បន្ថែមមូលវិចារណ៍";

            resetOptions();

            if (predefinedRemarks.includes(remark)) {
                selectRemarkOption(remark);
            } else if (remark) {
                selectRemarkOption("manual", { clearInput: false });
            } else {
                selectedValue.value = "";
                manualInputWrapper.classList.add("hidden");
            }

            modal.classList.remove("hidden");
            modal.setAttribute("aria-hidden", "false");
        });

        remarkOptions.forEach((button) => {
            button.addEventListener("click", () => {
                selectRemarkOption(button.dataset.value);
            });
        });

        closeButton?.addEventListener("click", closeModal);
        cancelButton?.addEventListener("click", closeModal);
        backdrop?.addEventListener("click", closeModal);

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && modal && !modal.classList.contains("hidden")) {
                closeModal();
            }
        });

        saveButton?.addEventListener("click", async () => {
            const id = summaryId.value;
            const selected = selectedValue.value;
            const remark = selected === "manual"
                ? input.value.trim()
                : selected;

            if (!id) {
                return;
            }

            saveButton.disabled = true;
            saveButton.textContent = "កំពុងរក្សាទុក...";

            try {
                const response = await fetch(
                    `/department-evaluation-results/remarks/${id}`,
                    {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content"),
                        },
                        body: JSON.stringify({
                            remarks: remark || null,
                        }),
                    }
                );

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || "Failed to save remark.");
                }

                closeModal();
                await table.load();

                await Swal.fire({
                    icon: "success",
                    title: "ជោគជ័យ",
                    text: "មូលវិចារណ៍ត្រូវបានរក្សាទុកដោយជោគជ័យ។",
                    confirmButtonText: "យល់ព្រម",
                });
            } catch (error) {
                await Swal.fire({
                    icon: "error",
                    title: "មានបញ្ហា",
                    text: error.message || "មិនអាចរក្សាទុកមូលវិចារណ៍បានទេ។",
                    confirmButtonText: "យល់ព្រម",
                });
            } finally {
                saveButton.disabled = false;
                saveButton.textContent = "រក្សាទុក";
            }
        });
    }

    table.load();
}

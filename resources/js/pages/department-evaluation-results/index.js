import DataTable from "../../components/data-table/DataTable";
import { renderDepartmentResultRow } from "./row-renderer";
import { registerDepartmentEvents } from "./event";

const tableBody = document.querySelector("#department-result-table-body");

if (tableBody) {

    const periodId = window.departmentEvaluationPeriodId;

    const table = new DataTable({
        url: `/department-evaluation-results/${periodId}/data`,
        body: "#department-result-table-body",
        pagination: "#department-result-pagination",
        search: "#department-result-search",
        perPage: "#department-result-per-page",
        render: renderDepartmentResultRow,
    });

    registerDepartmentEvents(table);

    table.load();
    // ==========================================
    // Office Filter
    // ==========================================

    const officeSelect =
        document.querySelector("#department-result-office");

    officeSelect?.addEventListener("change", (event) => {

        table.state.setFilter(
            "office_id",
            event.target.value
        );

        table.load();
    });

    // ==========================================================
    // Bulk PDF / Word Export
    // ==========================================================

    const pdfButton = document.querySelector(
        "#department-result-download-pdf"
    );

    const wordButton = document.querySelector(
        "#department-result-download-word"
    );

    const searchInput = document.querySelector(
        "#department-result-search"
    );

    // PDF
    pdfButton?.addEventListener("click", () => {

        const search = searchInput?.value?.trim() || "";
        const officeId = officeSelect?.value || "";
        const params = new URLSearchParams();
        if (search) {
            params.set("search", search);
        }
        if (officeId) {
            params.set("office_id", officeId);
        }
        const url =
            `/department-evaluation-results/${periodId}/download/pdf` +
            (params.toString() ? `?${params.toString()}` : "");

        window.open(url, "_blank");
    });

    // Word
    wordButton?.addEventListener("click", () => {

        const search = searchInput?.value?.trim() || "";

        const params = new URLSearchParams();

        if (search) {
            params.set("search", search);
        }

        const url =
            `/department-evaluation-results/${periodId}/download/word` +
            (params.toString() ? `?${params.toString()}` : "");

        window.location.href = url;
    });
}
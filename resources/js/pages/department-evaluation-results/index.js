import DataTable from "../../components/data-table/DataTable";
import { renderDepartmentResultRow } from "./row-renderer";

const tableBody = document.querySelector(
    "#department-result-table-body"
);

if (tableBody) {

    const periodId =
        window.departmentEvaluationPeriodId;

    const table = new DataTable({

        url: `/department-evaluation-results/${periodId}/data`,

        body: "#department-result-table-body",

        pagination: "#department-result-pagination",

        search: "#department-result-search",

        perPage: "#department-result-per-page",

        render: renderDepartmentResultRow,

    });

    table.load();
}
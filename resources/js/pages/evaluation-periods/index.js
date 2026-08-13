import DataTable from "../../components/data-table/DataTable";
import { renderEvaluationPeriodRow } from "./row-renderer";
import { registerEvaluationPeriodEvents } from "./events";

const tableBody = document.querySelector("#evaluation-period-table-body");

if (tableBody) {

    const table = new DataTable({
        url: "/evaluation-periods/data",
        body: "#evaluation-period-table-body",
        pagination: "#evaluation-period-pagination",
        search: "#evaluation-period-search",
        perPage: "#evaluation-period-per-page",
        render: renderEvaluationPeriodRow,
    });

    table.load();

    registerEvaluationPeriodEvents();

}
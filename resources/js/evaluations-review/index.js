import DataTable from "../components/data-table/DataTable";
import { renderEvaluationReviewRow } from "./row-renderer";

const tableBody = document.querySelector(
    "#evaluation-review-table-body"
);

if (tableBody) {
    const table = new DataTable({
        url: "/evaluations/review/data",
        body: "#evaluation-review-table-body",
        pagination: "#evaluation-review-pagination",
        render: renderEvaluationReviewRow,
    });

    table.load();
}
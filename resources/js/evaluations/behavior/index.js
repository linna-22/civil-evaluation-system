import DataTable from "../../components/data-table/DataTable";
import { renderBehaviorRow } from "./row-renderer";

const tableBody = document.querySelector("#behavior-table-body");
const startEvaluationButton = document.querySelector("#startEvaluationButton");
const viewEvaluationButton = document.querySelector("#viewEvaluationButton");

if (tableBody) {
    const table = new DataTable({
        url: "/evaluations/behavior/data",
        body: "#behavior-table-body",
        pagination: "#behavior-pagination",
        render: renderBehaviorRow,

        onLoaded: (response) => {
            if (!startEvaluationButton || !viewEvaluationButton) {
                return;
            }

            console.log("has_pending:", response.has_pending);

            if (response.has_pending === true) {
                // Still have pending evaluations
                startEvaluationButton.style.display = "inline-flex";
                viewEvaluationButton.style.display = "none";
            } else {
                // All evaluations are submitted
                startEvaluationButton.style.display = "none";
                viewEvaluationButton.style.display = "inline-flex";
            }
        },
    });

    table.load();
}
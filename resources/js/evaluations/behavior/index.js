import DataTable from "../../components/data-table/DataTable";
import { renderBehaviorRow } from "./row-renderer";


const tableBody =
    document.querySelector("#behavior-table-body");


if (tableBody) {

    const table = new DataTable({

        url: "/evaluations/behavior/data",

        body: "#behavior-table-body",

        pagination: "#behavior-pagination",

        render: renderBehaviorRow,

    });


    table.load();

}
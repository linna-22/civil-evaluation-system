import DataTable from "../../components/data-table/DataTable";
import { renderDepartmentRow } from "./row-renderer";
import { registerDepartmentEvents } from "./events";

let table = null;
const tableBody = document.querySelector("#department-table-body");

if (tableBody) {

    
    const table = new DataTable({
        url: "/departments/data",
        body: "#department-table-body",
        pagination: "#department-pagination",
        search: "#department-search",
        perPage: "#department-per-page",
        render: renderDepartmentRow,
    });

    table.load();

    registerDepartmentEvents(table);

}
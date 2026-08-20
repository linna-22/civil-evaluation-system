import DataTable from "../../components/data-table/DataTable";
import { renderOfficeRow } from "./row-renderer";
import { registerOfficeEvents } from "./events";

let table = null;
const tableBody = document.querySelector("#office-table-body");

if (tableBody) {

    
    const table = new DataTable({
        url: "/offices/data",
        body: "#office-table-body",
        pagination: "#office-pagination",
        search: "#office-search",
        perPage: "#office-per-page",
        render: renderOfficeRow,
    });

    table.load();

    registerOfficeEvents(table);

}
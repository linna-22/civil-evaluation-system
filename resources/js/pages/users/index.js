import DataTable from "../../components/data-table/DataTable";
import { renderUserRow } from "./row-renderer";
import { registerUserEvents } from "./events";

const tableBody = document.querySelector("#user-table-body");

if (tableBody) {

    const table = new DataTable({
        url: "/users/data",
        body: "#user-table-body",
        pagination: "#user-pagination",
        search: "#user-search",
        perPage: "#user-per-page",
        render: renderUserRow,
    });

    table.load();

    registerUserEvents();

}
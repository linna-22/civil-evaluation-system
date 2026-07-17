import DataTable from "../../components/data-table/DataTable";
import { renderOrganizationRow } from "./row-renderer";
import { registerOrganizationEvents } from "./events";

const tableBody = document.querySelector("#organization-table-body");

if (tableBody) {

    const table = new DataTable({
        url: "/organizations/data",
        body: "#organization-table-body",
        pagination: "#organization-pagination",
        search: "#organization-search",
        perPage: "#organization-per-page",
        render: renderOrganizationRow,
    });

    table.load();

    registerOrganizationEvents();

}
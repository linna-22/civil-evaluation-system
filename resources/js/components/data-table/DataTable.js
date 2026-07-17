import { get } from "../../utils/api";
import Pagination from "./Pagination";
import State from "./State";
import Search from "./Search";
import PerPage from "./PerPage";
import { refreshIcons } from "../../utils/lucide";


export default class DataTable {

    constructor(options) {

    this.url = options.url;

    this.body = document.querySelector(options.body);

    this.render = options.render;

    this.state = new State();

    this.pagination = new Pagination({

        container: options.pagination,

        onPageChange: (page) => {
            this.state.setPage(page);
            this.load();
        }

    });
    this.search = new Search({

    input: options.search,

    onSearch: (keyword) => {

        this.state.setSearch(keyword);

        this.load();

    }

});
this.perPage = new PerPage({

    select: options.perPage,

    onChange: (value) => {

        this.state.setPerPage(value);

        this.load();

    }

});

}

    async load(page = 1) {

        this.showLoading();

        try {

            const response = await get(`${this.url}?${this.state.toQueryString()}`);
            this.renderRows(response.data.data);
            refreshIcons();
            this.pagination.render(response.data);

        } catch (error) {

            this.showError(error.message);

        }

    }

    renderRows(rows) {

        this.body.innerHTML = rows
            .map(row => this.render(row))
            .join("");

    }

    showLoading() {

        this.body.innerHTML = `
            <tr>
                <td colspan="6" class="py-10 text-center text-gray-400">
                    កំពុងទាញយកទិន្នន័យ...
                </td>
            </tr>
        `;

    }

    showError(message) {

        this.body.innerHTML = `
            <tr>
                <td colspan="6" class="py-10 text-center text-red-500">
                    ${message}
                </td>
            </tr>
        `;

    }

}
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


        // ==========================================
        // Pagination
        // ==========================================

        this.pagination = new Pagination({

            container: options.pagination,

            onPageChange: (page) => {

                this.state.setPage(page);

                this.load();

            }

        });


        // ==========================================
        // Search
        // ==========================================

        this.search = new Search({

            input: options.search,

            onSearch: (keyword) => {

                this.state.setSearch(keyword);

                this.load();

            }

        });


        // ==========================================
        // Per Page
        // ==========================================

        this.perPage = new PerPage({

            select: options.perPage,

            onChange: (value) => {

                this.state.setPerPage(value);

                this.load();

            }

        });


        // ==========================================
        // Create Loading Overlay
        // ==========================================

        this.createLoadingOverlay();

    }


    // ==========================================
    // Create Loading Overlay
    // ==========================================

    createLoadingOverlay() {

        const tableContainer =
            this.body.closest(".data-table-scroll");

        if (!tableContainer) {
            return;
        }


        // Prevent duplicate loader

        if (
            tableContainer.querySelector(
                ".data-table-loading"
            )
        ) {
            return;
        }


        const loader =
            document.createElement("div");


        loader.className = `
            data-table-loading
            hidden
        `;


        loader.innerHTML = `
            <div class="flex flex-col items-center gap-3">

                <div class="
                    w-8
                    h-8
                    border-4
                    border-gray-200
                    border-t-blue-600
                    rounded-full
                    animate-spin
                "></div>

                <span class="
                    text-sm
                    text-gray-500
                ">
                    កំពុងទាញទិន្នន័យ...
                </span>

            </div>
        `;


        tableContainer.appendChild(loader);

    }


    // ==========================================
    // Load Data
    // ==========================================

    async load(page = 1) {

        this.showLoading();


        try {

            const response = await get(
                `${this.url}?${this.state.toQueryString()}`
            );


            this.renderRows(
                response.data.data
            );


            refreshIcons();


            this.pagination.render(
                response.data
            );


        } catch (error) {

            this.showError(
                error.message
            );

        } finally {

            this.hideLoading();

        }

    }


    // ==========================================
    // Render Rows
    // ==========================================

    renderRows(rows) {

        this.body.innerHTML = rows
            .map(row => this.render(row))
            .join("");

    }


    // ==========================================
    // Show Loading
    // ==========================================

    showLoading() {

        const loader =
            this.body
                .closest(".data-table-scroll")
                ?.querySelector(
                    ".data-table-loading"
                );


        if (loader) {

            loader.classList.remove("hidden");

        }

    }


    // ==========================================
    // Hide Loading
    // ==========================================

    hideLoading() {

        const loader =
            this.body
                .closest(".data-table-scroll")
                ?.querySelector(
                    ".data-table-loading"
                );


        if (loader) {

            loader.classList.add("hidden");

        }

    }


    // ==========================================
    // Show Error
    // ==========================================

    showError(message) {

        this.body.innerHTML = `
            <tr>

                <td
                    colspan="6"
                    class="py-10 text-center text-red-500"
                >
                    ${message}
                </td>

            </tr>
        `;

    }

}
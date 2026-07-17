export default class State {

    constructor() {

        this.page = 1;
        this.search = "";
        this.sortBy = null;
        this.sortDirection = "asc";
        this.perPage = 5;
        this.filters = {};

    }

    setPage(page) {
        this.page = page;
    }

    setSearch(keyword) {
        this.search = keyword;
        this.page = 1; // Reset to first page whenever search changes
    }

    setPerPage(value) {
        this.perPage = value;
        this.page = 1;
    }

    setSort(column, direction = "asc") {
        this.sortBy = column;
        this.sortDirection = direction;
    }

    setFilter(key, value) {
        this.filters[key] = value;
        this.page = 1;
    }

    toQueryString() {

        const params = new URLSearchParams();

        params.set("page", this.page);
        params.set("per_page", this.perPage);

        if (this.search) {
            params.set("search", this.search);
        }

        if (this.sortBy) {
            params.set("sort_by", this.sortBy);
            params.set("sort_direction", this.sortDirection);
        }

        Object.entries(this.filters).forEach(([key, value]) => {
            if (value !== null && value !== "") {
                params.set(key, value);
            }
        });

        return params.toString();
    }

}
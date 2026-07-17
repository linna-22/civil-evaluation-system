export default class Search {

    constructor(options) {

        this.input = document.querySelector(options.input);

        this.onSearch = options.onSearch;

        this.delay = options.delay ?? 300;

        this.timeout = null;

        this.init();

    }

    init() {

        if (!this.input) {
            return;
        }

        this.input.addEventListener("input", (event) => {

            clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {

                this.onSearch(event.target.value);

            }, this.delay);

        });

    }

}
export default class PerPage {

    constructor(options) {

        this.select = document.querySelector(options.select);

        this.onChange = options.onChange;

        this.init();

    }

    init() {

        if (!this.select) {
            return;
        }

        this.select.addEventListener("change", (event) => {

            this.onChange(event.target.value);

        });

    }

}
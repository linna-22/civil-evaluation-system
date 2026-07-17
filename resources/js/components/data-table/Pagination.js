export default class Pagination {
    constructor(options) {
        this.container = document.querySelector(options.container);
        this.onPageChange = options.onPageChange;
    }

    render(meta) {
        // Clear old pagination
        this.container.innerHTML = "";

        // No pagination needed
        if (meta.last_page <= 1) {
            return;
        }

        // Previous button
        this.container.appendChild(
            this.createButton(
                "«",
                meta.current_page - 1,
                meta.current_page === 1
            )
        );

        // Page numbers
        for (let page = 1; page <= meta.last_page; page++) {
            this.container.appendChild(
                this.createButton(
                    page,
                    page,
                    false,
                    page === meta.current_page
                )
            );
        }

        // Next button
        this.container.appendChild(
            this.createButton(
                "»",
                meta.current_page + 1,
                meta.current_page === meta.last_page
            )
        );
    }

    createButton(label, page, disabled = false, active = false) {
        const button = document.createElement("button");

        button.textContent = label;

        button.className = `
            min-w-10
            h-10
            px-3
            rounded-lg
            border
            transition
            ${
                active
                    ? "bg-blue-600 text-white border-blue-600"
                    : "bg-white border-gray-300 hover:bg-gray-100"
            }
        `;

        if (disabled) {
            button.disabled = true;
            button.classList.add("opacity-50", "cursor-not-allowed");
        }

        button.addEventListener("click", () => {
            this.onPageChange(page);
        });

        return button;
    }
}
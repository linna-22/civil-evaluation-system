import { refreshIcons } from "../../utils/lucide";

export default class Pagination {

    constructor(options) {

        this.container = document.querySelector(options.container);

        this.onPageChange = options.onPageChange;

    }


    render(meta) {

        // Clear old pagination
        this.container.innerHTML = "";


        // ==========================================
        // Bottom Pagination Layout
        // ==========================================

        const wrapper = document.createElement("div");

        wrapper.className = `
            w-full
            flex
            justify-between
            items-center
            gap-4
        `;


        // ==========================================
        // Showing Information - Left
        // ==========================================

        const info = document.createElement("div");

        info.className = `
            text-sm
            text-gray-500
            whitespace-nowrap
        `;

        const from = meta.from ?? 0;

        const to = meta.to ?? 0;

        const total = meta.total ?? 0;

        info.textContent =
            `កំពុងបង្ហាញ ${from} ដល់ ${to} នៃ ${total} ទិន្នន័យ`;

        wrapper.appendChild(info);


        // ==========================================
        // Pagination Buttons - Right
        // ==========================================

        const pagination = document.createElement("div");

        pagination.className = `
            flex
            items-center
            gap-2
        `;


        // ==========================================
        // Previous Button
        // ==========================================

        pagination.appendChild(

            this.createButton(

                `<i data-lucide="arrow-left"></i>`,

                meta.current_page - 1,

                meta.current_page === 1

            )

        );


        // ==========================================
        // Page Numbers
        // ==========================================

        for (
            let page = 1;
            page <= meta.last_page;
            page++
        ) {

            pagination.appendChild(

                this.createButton(

                    page,

                    page,

                    false,

                    page === meta.current_page

                )

            );

        }


        // ==========================================
        // Next Button
        // ==========================================

        pagination.appendChild(

            this.createButton(

                `<i data-lucide="arrow-right"></i>`,

                meta.current_page + 1,

                meta.current_page === meta.last_page

            )

        );


        wrapper.appendChild(pagination);

        this.container.appendChild(wrapper);


        // ==========================================
        // Refresh Lucide Icons
        // ==========================================

        refreshIcons();

    }


    // ==========================================
    // Create Button
    // ==========================================

    createButton(

        label,

        page,

        disabled = false,

        active = false

    ) {

        const button = document.createElement("button");


        button.type = "button";


        // IMPORTANT:
        // Use innerHTML because previous/next
        // buttons contain Lucide <i> elements.

        button.innerHTML = label;


        button.className = `
            min-w-10
            h-10
            px-3
            rounded-lg
            border
            transition
            text-sm
            font-medium
            inline-flex
            items-center
            justify-center
            ${
                active
                    ? "bg-blue-600 text-white border-blue-600"
                    : "bg-white text-gray-700 border-gray-300 hover:bg-gray-100"
            }
        `;


        // ==========================================
        // Disabled
        // ==========================================

        if (disabled) {

            button.disabled = true;

            button.classList.add(
                "opacity-50",
                "cursor-not-allowed"
            );

        }


        // ==========================================
        // Click
        // ==========================================

        button.addEventListener("click", () => {

            if (!disabled) {

                this.onPageChange(page);

            }

        });


        return button;

    }

}
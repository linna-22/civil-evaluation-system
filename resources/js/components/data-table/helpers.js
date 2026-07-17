export function statusBadge(status) {

    const active = String(status).trim().toLowerCase() === "active";

    return `
        <span
            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium
            ${active
            ? "bg-green-100 text-green-700"
            : "bg-red-100 text-red-700"}">

            <span
                class="h-2 w-2 rounded-full
                ${active
            ? "bg-green-500"
            : "bg-red-500"}">
            </span>

            ${active ? "សកម្ម" : "អសកម្ម"}

        </span>
    `;

}

export function actionButtons(id, showDelete = false) {

    let html = `
        <div class="flex items-center justify-center gap-2">

            <button
                type="button"
                class="btn-edit
                    flex items-center justify-center
                    h-9 w-9
                    rounded-lg
                    bg-amber-100
                    text-amber-600
                    hover:bg-amber-200
                    cursor-pointer"
                data-id="${id}">

                <i data-lucide="square-pen" class="w-4 h-4"></i>

            </button>
    `;

    if (showDelete) {

        html += `
            <button
                type="button"
                class="btn-delete
                    flex items-center justify-center
                    h-9 w-9
                    rounded-lg
                    bg-red-100
                    text-red-600
                    hover:bg-red-200
                    cursor-pointer"
                data-id="${id}">

                <i data-lucide="trash-2" class="w-4 h-4"></i>

            </button>
        `;

    }

    html += `</div>`;

    return html;

}
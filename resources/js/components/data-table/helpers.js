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
export function EvaluationPeriodstatusBadge(status) {

    const open = String(status).trim().toLowerCase() === "open";

    return `
        <span
            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium
            ${open
            ? "bg-green-100 text-green-700"
            : "bg-red-100 text-red-700"}">

            <span
                class="h-2 w-2 rounded-full
                ${open
            ? "bg-green-500"
            : "bg-red-500"}">
            </span>

            ${open ? "កំពុងបើកការវាយតម្លៃ" : "បានបិទការវាយតម្លៃ"}

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
export function evaluationPeriodActionButtons(id, status) {

    let buttons = `
        <!-- View -->
        <button
            type="button"
            class="btn-view-evaluation
                   p-2
                   rounded-lg
                   bg-blue-50
                   text-blue-500
                   hover:bg-blue-100
                   hover:text-blue-600
                   cursor-pointer
                   transition"
            data-id="${id}"
            title="មើលលម្អិត">

            <i
                data-lucide="eye"
                class="w-4 h-4">
            </i>

        </button>
    `;


    // ==========================================
    // Open Period Actions
    // ==========================================

    if (status === "open") {

        buttons += `
            <!-- Edit -->
            <button
                type="button"
                class="btn-edit
                       p-2
                       rounded-lg
                       bg-amber-100
                       text-amber-600
                       hover:bg-amber-200
                       cursor-pointer
                       transition"
                data-id="${id}"
                title="កែប្រែ">

                <i
                    data-lucide="square-pen"
                    class="w-4 h-4">
                </i>

            </button>


            <!-- Close -->
            <button
                type="button"
                class="btn-close-evaluation
                       p-2
                       rounded-lg
                       bg-red-100
                       text-red-600
                       hover:bg-red-200
                       cursor-pointer
                       transition"
                data-id="${id}"
                title="បិទការវាយតម្លៃ">

                <i
                    data-lucide="lock"
                    class="w-4 h-4">
                </i>

            </button>
        `;

    }


    return `
        <div class="flex justify-center items-center gap-2">
            ${buttons}
        </div>
    `;
}
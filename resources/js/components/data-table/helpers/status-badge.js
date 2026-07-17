export function statusBadge(status) {

    const active = status === "active";

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

            ${active ? "Active" : "Inactive"}

        </span>
    `;
}
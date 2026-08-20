import {
    statusBadge,
    actionButtons
} from "../../components/data-table/helpers";
export function renderOfficeRow(office, no) {

    return `
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

            <td class="px-6 py-4">${no}</td>

            <td class="px-6 py-4 font-medium">
                ${office.office_code}
            </td>

            <td class="px-6 py-4">
                ${office.office_name_kh}
            </td>

            <td class="px-6 py-4">
                ${office.office_name_en}
            </td>
            <td class="px-6 py-4">
                ${statusBadge(office.status)}
            </td>

            <td class="px-6 py-4">
                ${actionButtons(office.office_id, true)}
            </td>

        </tr>
    `;
}
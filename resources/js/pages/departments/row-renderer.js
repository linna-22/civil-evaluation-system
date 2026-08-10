import {
    statusBadge,
    actionButtons
} from "../../components/data-table/helpers";
export function renderDepartmentRow(department) {

    return `
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

            <td class="px-6 py-4">${department.department_id}</td>

            <td class="px-6 py-4 font-medium">
                ${department.department_code}
            </td>

            <td class="px-6 py-4">
                ${department.department_name_kh}
            </td>

            <td class="px-6 py-4">
                ${department.department_name_en}
            </td>
            <td class="px-6 py-4">
                ${statusBadge(department.status)}
            </td>

            <td class="px-6 py-4">
                ${actionButtons(department.department_id, false)}
            </td>

        </tr>
    `;
}
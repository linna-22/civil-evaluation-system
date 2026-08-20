import {
    statusBadge,
    actionButtons
} from "../../components/data-table/helpers";
export function renderOrganizationRow(organization, no) {

    return `
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

            <td class="px-6 py-4">${no}</td>

            <td class="px-6 py-4 font-medium">
                ${organization.org_code}
            </td>

            <td class="px-6 py-4">
                ${organization.org_name_kh}
            </td>

            <td class="px-6 py-4">
                ${organization.org_name_en}
            </td>
            <td class="px-6 py-4">
                ${statusBadge(organization.status)}
            </td>

            <td class="px-6 py-4">
                ${actionButtons(organization.organization_id)}
            </td>

        </tr>
    `;
}
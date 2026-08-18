import {
    statusBadge,
    actionButtons
} from "../../components/data-table/helpers";
export function renderUserRow(user, no) {

    return `
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

            <td class="px-6 py-4">${no}</td>

            <td class="px-6 py-4">
                ${user.name_kh}
            </td>

            <td class="px-6 py-4">
                ${user.name_en}
            </td>
            <td class="px-6 py-4">
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-600 text-white text-xs font-medium"
                    title="${user.organization?.org_name_kh ?? '-'}"
                >
                    ${
                        user.organization?.org_name_kh.length > 20
                            ? user.organization.org_name_kh.substring(0, 20) + "..."
                            : user.organization?.org_name_kh ?? "-"
                    }
                </span>
            </td>
            <td class="px-6 py-4">
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-600 text-white text-xs font-medium"
                    title="${user.department?.department_name_kh ?? 'ជាថ្នាក់ដឹកនាំ'}"
                >
                    ${
                        user.department?.department_name_kh.length > 20
                            ? user.department.department_name_kh.substring(0, 20) + "..."
                            : user.department?.department_name_kh ?? "ជាថ្នាក់ដឹកនាំ"
                    }
                </span>
            </td>
            <td class="px-6 py-4">
                ${statusBadge(user.status)}
            </td>

            <td class="px-6 py-4">
                ${actionButtons(user.user_id)}
            </td>

        </tr>
    `;
}